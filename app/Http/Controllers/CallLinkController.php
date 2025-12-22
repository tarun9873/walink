<?php

namespace App\Http\Controllers;

use App\Models\CallLink;
use App\Models\WaLink;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CallLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('redirect', 'notfound');
    }

    /**
     * 🔢 TOTAL USED LINKS (WA + CALL)
     */
    private function totalUsedLinks($userId)
    {
        return
            WaLink::where('user_id', $userId)->where('is_active', 1)->count()
          + CallLink::where('user_id', $userId)->where('is_active', 1)->count();
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();

        $remainingLinks = 0;

        if ($subscription) {
            $planLimit  = $subscription->plan->links_limit;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalAllowed = $planLimit + $extraLinks;

            $usedLinks = $this->totalUsedLinks($user->id);
            $remainingLinks = max(0, $totalAllowed - $usedLinks);
        } else {
            // Free user
            $usedLinks = $this->totalUsedLinks($user->id);
            $remainingLinks = max(0, 1 - $usedLinks);
        }

        $countries = Cache::remember('country_codes', 86400, function () {
            $json = file_get_contents(
                'https://gist.githubusercontent.com/anubhavshrimal/75f6183458db8c453306f93521e93d37/raw/f77e7598a8503f1f70528ae1cbf9f66755698a16/CountryCodes.json'
            );
            return json_decode($json, true);
        });

        return view('call_links.calllink', compact('remainingLinks', 'countries'));
    }

    /**
     * Store call link
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();

        $usedLinks = $this->totalUsedLinks($user->id);

        if ($subscription) {
            $totalAllowed = $subscription->plan->links_limit + ($subscription->extra_links ?? 0);

            if ($usedLinks >= $totalAllowed) {
                return back()->with(
                    'error',
                    'You have reached your link limit.'
                )->withInput();
            }
        } else {
            if ($usedLinks >= 1) {
                return back()->with(
                    'error',
                    'Free users can create only 1 link.'
                )->withInput();
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:call_links,slug',
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
        ]);

        $slug = Str::slug($request->slug);

        $reserved = ['login','register','admin','api','home','dashboard','pricing','subscribe','subscription','wa','call'];
        if (in_array(strtolower($slug), $reserved)) {
            return back()->withErrors(['slug' => 'This slug is reserved'])->withInput();
        }

        $phone = preg_replace('/\D+/', '', $request->phone);
        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number'])->withInput();
        }

        CallLink::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'slug' => $slug,
            'phone' => $phone,
            'country_code' => $request->country_code,
            'full_url' => "tel:+" . $request->country_code . $phone,
            'is_active' => true,
            'clicks' => 0,
        ]);

        return redirect()
            ->route('admin.call-links.index')
            ->with('success', 'Call link created successfully!');
    }

    /**
     * List links
     */
    public function index()
    {
        $user = auth()->user();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();

        $remainingLinks = 0;

        if ($subscription) {
            $totalAllowed = $subscription->plan->links_limit + ($subscription->extra_links ?? 0);
            $usedLinks = $this->totalUsedLinks($user->id);
            $remainingLinks = max(0, $totalAllowed - $usedLinks);
        }

        $monthlyClicks = CallLink::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->sum('clicks');

        $links = CallLink::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('call_links.index', compact(
            'links',
            'subscription',
            'remainingLinks',
            'monthlyClicks'
        ));
    }

    /**
     * Edit
     */
    public function edit(CallLink $callLink)
    {
        if ($callLink->user_id !== auth()->id()) {
            abort(403);
        }

        $countries = Cache::remember('country_codes', 86400, function () {
            $json = file_get_contents(
                'https://gist.githubusercontent.com/anubhavshrimal/75f6183458db8c453306f93521e93d37/raw/f77e7598a8503f1f70528ae1cbf9f66755698a16/CountryCodes.json'
            );
            return json_decode($json, true);
        });

        $remainingLinks = 0;

        return view('call_links.calllink', compact('callLink', 'remainingLinks', 'countries'));
    }

    /**
     * Update
     */
    public function update(Request $request, CallLink $callLink)
    {
        if ($callLink->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:call_links,slug,' . $callLink->id,
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
        ]);

        $slug = Str::slug($request->slug);

        $phone = preg_replace('/\D+/', '', $request->phone);

        $callLink->update([
            'name' => $request->name,
            'slug' => $slug,
            'phone' => $phone,
            'country_code' => $request->country_code,
            'full_url' => "tel:+" . $request->country_code . $phone,
        ]);

        return redirect()
            ->route('admin.call-links.index')
            ->with('success', 'Call link updated successfully!');
    }

    /**
     * Delete
     */
    public function destroy(CallLink $callLink)
    {
        if ($callLink->user_id !== auth()->id()) {
            abort(403);
        }

        $callLink->delete();

        return back()->with('success', 'Call link deleted successfully!');
    }

    /**
     * Redirect
     */
    public function redirect($slug)
    {
        $link = CallLink::where('slug', $slug)->where('is_active', 1)->first();

        if (!$link) {
            return redirect()->route('call-links.notfound');
        }

        $link->increment('clicks');

        return redirect()->away($link->full_url);
    }

    public function notfound()
    {
        return view('call_links.notfound');
    }
}