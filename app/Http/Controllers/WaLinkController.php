<?php

namespace App\Http\Controllers;

use App\Models\WaLink;
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
            // Optionally cache the lookup: Cache::remember("wa_link:{$slug}", 60, fn() => WaLink::where('slug',$slug)->first());
            $link = WaLink::where('slug', $slug)->first();

            if (!$link || ! $link->is_active) {
                Log::info("Missing or inactive link visited: {$slug}", ['ip' => request()->ip()]);
                return redirect()->route('wa-links.notfound');
            }

            // increment clicks (atomic)
            $link->increment('clicks');

            return redirect()->away($link->full_url);
        } catch (\Throwable $e) {
            Log::error("Error redirecting slug {$slug}: ".$e->getMessage(), ['slug'=>$slug,'ip'=>request()->ip()]);
            return redirect()->route('wa-links.notfound');
        }
    }
}