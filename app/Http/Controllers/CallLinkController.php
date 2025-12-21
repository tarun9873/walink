<?php

namespace App\Http\Controllers;

use App\Models\CallLink;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CallLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('redirect', 'notfound');
    }

    /**
     * Show create form for call link
     */
    public function create()
    {
        $user = auth()->user();
        
        // Check subscription
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
            
        // Calculate remaining links
        $remainingLinks = 0;
        if ($subscription) {
            $plan = Plan::find($subscription->plan_id);
            $planLimit = $plan ? $plan->links_limit : 1;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalAllowed = $planLimit + $extraLinks;
            
            $activeLinksCount = CallLink::where('user_id', $user->id)
                ->where('is_active', 1)
                ->count();
                
            $remainingLinks = max(0, $totalAllowed - $activeLinksCount);
        } else {
            // Free user - max 1 link
            $activeLinksCount = CallLink::where('user_id', $user->id)
                ->where('is_active', 1)
                ->count();
                
            $remainingLinks = max(0, 1 - $activeLinksCount);
        }
        
        return view('call_links.calllink', compact('remainingLinks'));
    }

    /**
     * Store new call link
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check subscription
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
            
        // Check link limit
        $activeLinksCount = CallLink::where('user_id', $user->id)
            ->where('is_active', 1)
            ->count();
            
        if ($subscription) {
            $plan = Plan::find($subscription->plan_id);
            $planLimit = $plan ? $plan->links_limit : 1;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalAllowed = $planLimit + $extraLinks;
            
            if ($activeLinksCount >= $totalAllowed) {
                return back()->with('error', 'You have reached your link limit. Please upgrade your plan.')
                             ->withInput();
            }
        } else {
            // Free user - max 1 link
            if ($activeLinksCount >= 1) {
                return back()->with('error', 'Free users can create only 1 link. Please subscribe to create more links.')
                             ->withInput();
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:call_links,slug',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
        ]);

        $slug = Str::slug($request->slug);

        // Reserved slugs
        $reserved = ['login','register','admin','api','home','dashboard','pricing','subscribe','subscription','wa','call'];
        if (in_array(strtolower($slug), $reserved)) {
            return back()->withErrors(['slug' => 'This slug is reserved'])->withInput();
        }

        // Check if slug already exists
        if (CallLink::where('slug', $slug)->exists()) {
            return back()->withErrors(['slug' => 'This slug is already taken'])->withInput();
        }

        // Normalize phone
        $phone = preg_replace('/\D+/', '', $request->phone);
        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number'])->withInput();
        }

        // Create tel: link
        $full_url = "tel:+" . $request->country_code . $phone;

        CallLink::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'slug' => $slug,
            'phone' => $phone,
            'country_code' => $request->country_code,
            'full_url' => $full_url,
            'is_active' => true,
            'clicks' => 0,
        ]);

        return redirect()->route('admin.call-links.index')->with('success', 'Call link created successfully!');
    }

    /**
     * List all call links
     */
    public function index()
{
    $user = auth()->user();
    
    $subscription = Subscription::where('user_id', $user->id)
        ->where('status', 'active')
        ->where('expires_at', '>', now())
        ->with('plan')
        ->first();
        
    // Calculate remaining links
    $remainingLinks = 0;
    if ($subscription) {
        $plan = $subscription->plan;
        $planLimit = $plan ? $plan->links_limit : 1;
        $extraLinks = $subscription->extra_links ?? 0;
        $totalAllowed = $planLimit + $extraLinks;
        
        $activeLinksCount = CallLink::where('user_id', $user->id)
            ->where('is_active', 1)
            ->count();
            
        $remainingLinks = max(0, $totalAllowed - $activeLinksCount);
    }
    
    // Calculate monthly clicks
    $monthlyClicks = CallLink::where('user_id', $user->id)
        ->whereMonth('created_at', now()->month)
        ->sum('clicks');
    
    $links = CallLink::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('call_links.index', compact('links', 'subscription', 'remainingLinks', 'monthlyClicks'));
}

    /**
     * Edit call link
     */
    public function edit(CallLink $callLink)
    {
        // Authorization check
        if ($callLink->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $remainingLinks = 0; // Not needed for edit
        
        // FIXED: Changed from 'wa_links.calllink' to 'call_links.calllink'
        return view('call_links.calllink', compact('callLink', 'remainingLinks'));
    }

    /**
     * Update call link
     */
    public function update(Request $request, CallLink $callLink)
    {
        // Authorization check
        if ($callLink->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:call_links,slug,' . $callLink->id,
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->slug);

        // Reserved slugs
        $reserved = ['login','register','admin','api','home','dashboard','pricing','subscribe','subscription','wa','call'];
        if (in_array(strtolower($slug), $reserved)) {
            return back()->withErrors(['slug' => 'This slug is reserved'])->withInput();
        }

        // Check if slug already exists (excluding current)
        if (CallLink::where('slug', $slug)->where('id', '!=', $callLink->id)->exists()) {
            return back()->withErrors(['slug' => 'This slug is already taken'])->withInput();
        }

        // Normalize phone
        $phone = preg_replace('/\D+/', '', $request->phone);
        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number'])->withInput();
        }

        // Update call link
        $callLink->update([
            'name' => $request->name,
            'slug' => $slug,
            'phone' => $phone,
            'country_code' => $request->country_code,
            'full_url' => "tel:+" . $request->country_code . $phone,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $callLink->is_active,
        ]);

        return redirect()->route('call-links.index')->with('success', 'Call link updated successfully!');
    }

    /**
     * Delete call link
     */
    public function destroy(CallLink $callLink)
    {
        // Authorization check
        if ($callLink->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $callLink->delete();

        return redirect()->route('admin.call-links.index')->with('success', 'Call link deleted successfully!');
    }

    /**
     * Redirect to call link
     */
    public function redirect($slug)
    {
        $link = CallLink::where('slug', $slug)->first();

        if (!$link || !$link->is_active) {
            Log::info("Missing or inactive call link: {$slug}");
            return redirect()->route('call-links.notfound');
        }

        // Increment clicks
        $link->increment('clicks');

        return redirect()->away($link->full_url);
    }

    /**
     * Show analytics
     */
    public function analytics(CallLink $callLink)
    {
        // Authorization check
        if ($callLink->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('call_links.analytics', compact('callLink'));
    }

    /**
     * Not found page
     */
    public function notfound()
    {
        return view('call_links.notfound');
    }
}