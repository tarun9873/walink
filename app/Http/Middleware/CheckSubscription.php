<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\WaLink;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please login to access this feature.',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login');
        }

        // Get active subscription with expiry check
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        // Count active links for this user
        $activeLinksCount = WaLink::where('user_id', $user->id)
            ->where('is_active', 1)
            ->count();

        // Determine limits
        if (!$subscription) {
            // Free plan - no active subscription
            $maxAllowedLinks = 1;
            
            if ($activeLinksCount >= $maxAllowedLinks) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Subscription Expired!  Your subscription has expired. You can only create 1 links.',
                        'redirect' => route('pricing')
                    ], 403);
                }
                
                return redirect()->route('dashboard')
                    ->with('error', 'Subscription Expired!  Your subscription has expired. You can only create 1 links.');
            }
        } else {
            // Has active subscription
            $plan = Plan::find($subscription->plan_id);
            $planLimit = $plan ? $plan->links_limit : 5;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalLimit = $planLimit + $extraLinks;
            
            if ($activeLinksCount >= $totalLimit) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'आपकी लिंक सीमा पूरी हो गई है! अधिक लिंक के लिए अपग्रेड करें।',
                        'redirect' => route('pricing')
                    ], 403);
                }
                
                return redirect()->route('dashboard')
                    ->with('error', 'आपकी लिंक सीमा पूरी हो गई है! अधिक लिंक के लिए अपग्रेड करें।');
            }
        }

        return $next($request);
    }
}