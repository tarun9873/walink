<?php

namespace App\Http\Controllers;

use App\Models\WaLink;
use App\Models\WaLinkClick;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WaLinkController extends Controller
{
    public function __construct()
    {
        // Protect everything except the public redirect route
        $this->middleware('auth')->except('redirect');
        
        // Apply subscription middleware to all methods except redirect
        $this->middleware('subscription')->except('redirect');
        
        // Apply link limit middleware only to create and store
        $this->middleware('link.limit')->only(['create', 'store']);
    }

    /**
     * List links for logged-in user (paginated)
     */
    public function index()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription;
        $remainingLinks = $user->remaining_links;
        
        $links = $user
            ->waLinks()
            ->select('id','name','slug','phone','message','clicks','is_active','created_at')
            ->latest()
            ->paginate(12);

        return view('wa_links.index', compact('links', 'subscription', 'remainingLinks'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $remainingLinks = auth()->user()->remaining_links;
        return view('wa_links.create', compact('remainingLinks'));
    }

    /**
     * Store new link
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Double-check link limit (middleware already checks, but this is backup)
        if (!$user->canCreateMoreLinks()) {
            return redirect()->route('pricing')
                ->with('error', 'You have reached your link limit. Please upgrade your plan.');
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
        $reserved = ['login','register','admin','api','home','dashboard', 'pricing', 'subscribe', 'subscription'];
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
            'is_active'=> true,
        ]);

        return redirect()->route('wa-links.index')->with('success','WhatsApp link created successfully!');
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
            'name'    => ['required','string','max:100'],
            'phone'   => ['required','string','max:32'],
            'message' => ['nullable','string','max:1000'],
            'slug'    => ['required','string','max:100','regex:/^[A-Za-z0-9\-]+$/'],
            'is_active'=> ['nullable','boolean'],
        ], [
            'slug.regex' => 'Slug may contain only letters, numbers and hyphens.'
        ]);

        $slug = Str::slug($request->slug);

        // reserved slugs & uniqueness excluding current record
        $reserved = ['login','register','admin','api','home','dashboard', 'pricing', 'subscribe', 'subscription'];
        if (in_array(strtolower($slug), $reserved) || WaLink::where('slug', $slug)->where('id','!=',$waLink->id)->exists()) {
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

        return redirect()->route('wa-links.index')->with('success','Link updated successfully!');
    }

    /**
     * Delete link (hard delete)
     * If you prefer soft delete, implement SoftDeletes in model & migration.
     */
    public function destroy(WaLink $waLink)
    {
        $this->authorize('delete', $waLink);
        $waLink->delete();
        return redirect()->route('wa-links.index')->with('success','Link deleted successfully!');
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
            Log::error("Error redirecting slug {$slug}: ".$e->getMessage(), ['slug'=>$slug,'ip'=>request()->ip()]);
            return redirect()->route('wa-links.notfound');
        }
    }

    /**
     * Show analytics for a specific WhatsApp link
     */
    public function analytics($id)
    {
        $waLink = WaLink::findOrFail($id);
        
           // Check if user owns this link (manual check without policy)
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
            // Check if WaLinkClick model exists
            if (!class_exists(WaLinkClick::class)) {
                return;
            }

            $agent = $this->getUserAgent();
            
            WaLinkClick::create([
                'wa_link_id' => $waLink->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'country' => $this->getCountryFromIP(request()->ip()),
                'city' => $this->getCityFromIP(request()->ip()),
                'referrer' => request()->header('referer'),
                'device_type' => $agent['device_type'],
                'browser' => $agent['browser'],
                'platform' => $agent['platform'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error tracking click: ' . $e->getMessage());
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
        return view('wa-links.notfound');
    }
}