<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\WaLink;
use App\Models\CallLink;
use App\Models\WaLinkClick;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;



class WaLinkController extends Controller
{

    private function totalUsedLinks($userId)
    {
        return
            WaLink::where('user_id', $userId)->where('is_active', 1)->count()
            + CallLink::where('user_id', $userId)->where('is_active', 1)->count();
    }

    public function __construct()
    {
        // Protect everything except the public redirect route
        $this->middleware('auth')->except('redirect', 'notfound');

        // Apply subscription middleware to store method
        $this->middleware('subscription')->only('store');

        // Apply link limit middleware to create and store
        $this->middleware('link.limit')->only(['create', 'store']);
    }



    /**
     * List links for logged-in user (paginated)
     */
    public function index(Request $request)
{
    $user = auth()->user();

    // ================= SUBSCRIPTION =================
    $subscription = Subscription::where('user_id', $user->id)
        ->where('status', 'active')
        ->where('expires_at', '>', now())
        ->with('plan')
        ->first();

    // ================= REMAINING LINKS =================
    $remainingLinks = $user->remaining_links ?? 0;

    // ================= LINKS QUERY =================
    $query = $user->waLinks()
        ->select(
            'id',
            'name',
            'slug',
            'phone',
            'message',
            'clicks',
            'is_active',
            'created_at'
        );

    // 🔍 SEARCH FILTER (NAME + URL/SLUG ONLY)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        });
    }

    // ================= PAGINATION =================
    $links = $query
        ->latest()
        ->paginate(15)
        ->withQueryString(); // 🔥 search pagination fix

    return view('wa_links.index', compact(
        'links',
        'subscription',
        'remainingLinks'
    ));
}

    

    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();

        // 🔹 Get active subscription FIRST
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();

        // 🔹 Combined used links (WA + Call)
        $usedLinks = $this->totalUsedLinks($user->id);

        // 🔹 Total allowed links
        if ($subscription) {
            $totalAllowed = $subscription->plan->links_limit
                + ($subscription->extra_links ?? 0);
        } else {
            // Free / expired user
            $totalAllowed = 1;
        }

        // 🔹 Remaining links
        $remainingLinks = max(0, $totalAllowed - $usedLinks);

        // 🔹 Final safety check
        if ($remainingLinks <= 0) {
            return redirect()->route('pricing')
                ->with('error', 'You have reached your link limit. Please upgrade your plan.');
        }

        return view('wa_links.create', compact('remainingLinks'));
    }


    /**
     * Store new link
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // TRIPLE CHECK: Verify user can create more links
        if (!$user->canCreateMoreLinks()) {
            $activeCount = $user->active_links_count;
            $totalAllowed = $user->total_allowed_links;

            if ($user->hasActiveSubscription()) {
                $message = "⚠️ आपकी लिंक सीमा पूरी हो गई है! ({$activeCount}/{$totalAllowed})";
            } else {
                $message = "❌ आपकी सब्सक्रिप्शन समाप्त हो गई है! आप केवल 1 लिंक बना सकते हैं।";
            }

            return redirect()->route('dashboard')
                ->with('error', $message)
                ->with('limit_reached', true);
        }

        // DOUBLE CHECK: Verify subscription and link limit (additional check)
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        // Count active links
        $activeLinksCount = $this->totalUsedLinks($user->id);


        // Determine limits
        if (!$subscription) {
            // Free plan - expired subscription
            $maxAllowed = 1;

            if ($activeLinksCount >= $maxAllowed) {
                return redirect()->route('dashboard')
                    ->with('error', '❌ आपकी सब्सक्रिप्शन समाप्त हो गई है! आप केवल 1 लिंक बना सकते हैं।')
                    ->with('subscription_expired', true);
            }
        } else {
            // Active subscription
            $plan = Plan::find($subscription->plan_id);
            $planLimit = $plan ? $plan->links_limit : 1;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalLimit = $planLimit + $extraLinks;

            if ($activeLinksCount >= $totalLimit) {
                return redirect()->route('dashboard')
                    ->with('error', '⚠️ आपकी लिंक सीमा पूरी हो गई है! अधिक लिंक के लिए अपग्रेड करें।')
                    ->with('limit_reached', true);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:wa_links,slug',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
        ], [
            'slug.regex' => 'Slug may contain only letters, numbers and hyphens.'
        ]);

        $slug = Str::slug($request->slug);

        // reserved slugs and uniqueness
        $reserved = ['login', 'register', 'admin', 'api', 'home', 'dashboard', 'pricing', 'subscribe', 'subscription'];
        if (in_array(strtolower($slug), $reserved) || WaLink::where('slug', $slug)->exists()) {
            return back()->withErrors(['slug' => 'This slug is reserved or already taken'])->withInput();
        }

        // normalize phone to digits only
        $phone = preg_replace('/\D+/', '', $request->phone);
        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number'])->withInput();
        }

        $encodedMessage = $request->message ? urlencode($request->message) : '';
        $full_url = $encodedMessage ? "https://wa.me/{$phone}?text={$encodedMessage}" : "https://wa.me/{$phone}";

        WaLink::create([
            'user_id'  => auth()->id(),
            'name'     => $request->name,
            'phone'    => $phone,
            'message'  => $request->message,
            'slug'     => $slug,
            'full_url' => $full_url,
            'is_active' => true,
        ]);

        return redirect()->route('wa-links.index')->with('success', 'WhatsApp link created successfully!');
    }

    /**
     * Edit form
     */
    public function edit(WaLink $waLink)
    {
        $this->authorize('update', $waLink);
        $remainingLinks = auth()->user()->remaining_links;
        return view('wa_links.edit', compact('waLink', 'remainingLinks'));
    }

    /**
     * Update existing link (phone editable)
     */
    public function update(Request $request, WaLink $waLink)
    {
        $this->authorize('update', $waLink);

        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'phone'   => ['required', 'string', 'max:32'],
            'message' => ['nullable', 'string', 'max:1000'],
            'slug'    => ['required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\-]+$/'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'slug.regex' => 'Slug may contain only letters, numbers and hyphens.'
        ]);

        $slug = Str::slug($request->slug);

        // reserved slugs & uniqueness excluding current record
        $reserved = ['login', 'register', 'admin', 'api', 'home', 'dashboard', 'pricing', 'subscribe', 'subscription'];
        if (in_array(strtolower($slug), $reserved) || WaLink::where('slug', $slug)->where('id', '!=', $waLink->id)->exists()) {
            return back()->withErrors(['slug' => 'This slug is reserved or already taken'])->withInput();
        }

        // normalize phone
        $phone = preg_replace('/\D+/', '', $request->phone);
        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number'])->withInput();
        }

        // update fields
        $waLink->name = $request->name;
        $waLink->phone = $phone;
        $waLink->message = $request->message;
        $waLink->slug = $slug;
        $waLink->is_active = $request->has('is_active') ? (bool)$request->is_active : $waLink->is_active;

        // rebuild full_url
        $encodedMessage = $waLink->message ? urlencode($waLink->message) : '';
        $waLink->full_url = $encodedMessage ? "https://wa.me/{$waLink->phone}?text={$encodedMessage}" : "https://wa.me/{$waLink->phone}";

        $waLink->save();

        return redirect()->route('wa-links.index')->with('success', 'Link updated successfully!');
    }

    /**
     * Delete link (hard delete)
     */
    public function destroy(WaLink $waLink)
    {
        $this->authorize('delete', $waLink);
        $waLink->delete();
        return redirect()->route('wa-links.index')->with('success', 'Link deleted successfully!');
    }

    /**
     * Public redirect by slug — friendly handling for missing/inactive
     */
    public function redirect($slug)
    {

        try {
            $link = WaLink::where('slug', $slug)->first();

            if (!$link || ! $link->is_active) {
                Log::info("Missing or inactive link visited: {$slug}", ['ip' => request()->ip()]);
                return redirect()->route('wa-links.notfound');
            }

            // Track the click with detailed analytics
            $this->trackClick($link);

            // increment clicks (atomic)
            $link->increment('clicks');

            return redirect()->away($link->full_url);
        } catch (\Throwable $e) {
            Log::error("Error redirecting slug {$slug}: " . $e->getMessage(), ['slug' => $slug, 'ip' => request()->ip()]);
            return redirect()->route('wa-links.notfound');
        }
    }

    /**
     * Show analytics for a specific WhatsApp link
     */
    public function analytics($id)
    {
        $waLink = WaLink::findOrFail($id);

        // Check if user owns this link
        if (auth()->id() !== $waLink->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if clicks relationship exists
        if (!method_exists($waLink, 'clicks')) {
            // Return empty data if relationship doesn't exist
            $dailyClicks = collect();
            $countryClicks = collect();
            $deviceClicks = collect();
            $totalClicks = $waLink->clicks;
            $uniqueVisitors = $waLink->clicks;
        } else {
            // Get daily clicks
            $dailyClicks = $waLink->clicks()
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get();

            // Get clicks by country
            $countryClicks = $waLink->clicks()
                ->selectRaw('country, COUNT(*) as count')
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('count', 'DESC')
                ->get();

            $cityClicks = WaLinkClick::where('wa_link_id', $waLink->id)
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->select('city', DB::raw('COUNT(*) as count'))
                ->groupBy('city')
                ->orderByDesc('count')
                ->get();


            // Get clicks by device
            $deviceClicks = $waLink->clicks()
                ->selectRaw('device_type, COUNT(*) as count')
                ->whereNotNull('device_type')
                ->groupBy('device_type')
                ->orderBy('count', 'DESC')
                ->get();

            $totalClicks = $waLink->clicks()->count();
            $uniqueVisitors = $waLink->clicks()->distinct('ip_address')->count('ip_address');
        }

        return view('wa_links.analytics', compact(
            'waLink',
            'dailyClicks',
            'countryClicks',
            'cityClicks', // 👈 ADD THIS
            'deviceClicks',
            'totalClicks',
            'uniqueVisitors'
        ));
    }

    /**
     * Track individual click with details
     */
    private function trackClick(WaLink $waLink)
    {
        try {
            if (!class_exists(WaLinkClick::class)) {
                return;
            }

            // ✅ REAL IP
            $ip = request()->header('CF-Connecting-IP')
                ?? request()->header('X-Forwarded-For')
                ?? request()->ip();

            if (strpos($ip, ',') !== false) {
                $ip = explode(',', $ip)[0];
            }

            // 🌍 GEO DATA
            $location = GeoIP::getLocation($ip)->toArray();

            // 📱 USER AGENT
            $agent = $this->getUserAgent();

            WaLinkClick::create([
                'wa_link_id' => $waLink->id,
                'ip_address' => $ip,

                // 🔥 COUNTRY FORCE
                'country' => $location['country_name']
                    ?? $location['country']
                    ?? 'Unknown',

                // 🔥 CITY (fallbacks)
                'city' => $location['city']
                    ?? $location['district']
                    ?? $location['state_prov']
                    ?? 'Unknown',

                'user_agent' => request()->userAgent(),
                'referrer' => request()->headers->get('referer'),
                'device_type' => $agent['device_type'] ?? 'Unknown',
                'browser' => $agent['browser'] ?? 'Unknown',
                'platform' => $agent['platform'] ?? 'Unknown',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Click tracking error: ' . $e->getMessage());
        }
    }


    /**
     * Get user agent details
     */
    private function getUserAgent()
    {
        $userAgent = request()->userAgent();

        // Basic device detection
        $deviceType = 'Desktop';
        if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i', $userAgent)) {
            $deviceType = 'Mobile';
        } elseif (preg_match('/(tablet|ipad|playbook|silk)/i', $userAgent)) {
            $deviceType = 'Tablet';
        }

        // Basic browser detection
        $browser = 'Unknown';
        if (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/edge/i', $userAgent)) {
            $browser = 'Edge';
        }

        // Basic platform detection
        $platform = 'Unknown';
        if (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'MacOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad/i', $userAgent)) {
            $platform = 'iOS';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform
        ];
    }

    /**
     * Get country from IP (basic implementation)
     */
    private function getCountryFromIP($ip)
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Localhost';
        }
        return 'Unknown';
    }

    /**
     * Get city from IP (basic implementation)
     */
    private function getCityFromIP($ip)
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Localhost';
        }
        return 'Unknown';
    }

    /**
     * Show not found page for invalid links
     */
    public function notfound()
    {
        return view('wa_links.notfound');
    }
}