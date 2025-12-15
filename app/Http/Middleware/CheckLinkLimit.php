<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\WaLink;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLinkLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get active subscription
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
            
        // Count active links
        $activeLinksCount = WaLink::where('user_id', $user->id)
            ->where('is_active', 1)
            ->count();
            
        // Determine limits
        if (!$subscription) {
            // Free plan
            $maxLinks = 5;
            
            if ($activeLinksCount >= $maxLinks) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'आप केवल 5 लिंक बना सकते हैं। सब्सक्रिप्शन अपग्रेड करें।',
                        'max_limit' => $maxLinks,
                        'current_count' => $activeLinksCount
                    ], 403);
                }
                
                return redirect()->route('dashboard')
                    ->with('error', 'आप केवल 5 लिंक बना सकते हैं। सब्सक्रिप्शन अपग्रेड करें।')
                    ->with('limit_reached', true);
            }
        } else {
            // Paid subscription
            $plan = Plan::find($subscription->plan_id);
            $planLimit = $plan ? $plan->links_limit : 5;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalLimit = $planLimit + $extraLinks;
            
            if ($activeLinksCount >= $totalLimit) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'आपकी लिंक सीमा पूरी हो गई है।',
                        'max_limit' => $totalLimit,
                        'current_count' => $activeLinksCount
                    ], 403);
                }
                
                return redirect()->route('dashboard')
                    ->with('error', 'आपकी लिंक सीमा पूरी हो गई है।')
                    ->with('limit_reached', true);
            }
        }
        
        // Add link count info to request for use in controller
        $request->merge([
            'user_link_count' => $activeLinksCount,
            'user_max_limit' => $subscription ? ($planLimit + $extraLinks) : 5
        ]);
        
        return $next($request);
    }
}