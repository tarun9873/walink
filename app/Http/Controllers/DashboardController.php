<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaLink;
use App\Models\Subscription;
use App\Models\Plan;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current logged in user ID
        $userId = auth()->id();
        
        // Get user's active subscription
        $subscription = Subscription::where('user_id', $userId)
            ->active()
            ->with('plan') // Eager load plan relationship
            ->first();
        
        // Get plan limits
        if ($subscription && $subscription->plan) {
            $planLimit = $subscription->plan->links_limit;
            $planName = $subscription->plan->name;
        } else {
            // Default limit if no active subscription
            $planLimit = 0; // Free plan limit
            $planName = 'Free Plan';
        }
        
        // Get actual data from WaLink model for current user
        $totalLinks = WaLink::where('user_id', $userId)->count();
        $usedLinks = WaLink::where('user_id', $userId)->where('is_active', 1)->count();
        $remainingLinks = max(0, $planLimit - $usedLinks);
        $usagePercentage = $planLimit > 0 ? ($usedLinks / $planLimit) * 100 : 0;
        
        // Get subscription expiry info
        $expiryDate = $subscription ? $subscription->expires_at : null;
        $daysRemaining = $subscription ? $subscription->daysRemaining() : 0;
        
        // Get user info
        $user = auth()->user();

        return view('dashboard', compact(
            'totalLinks',
            'usedLinks',
            'remainingLinks',
            'usagePercentage',
            'planLimit',
            'subscription',
            'expiryDate',
            'daysRemaining',
            'planName',
            'user'
        ));
    }
}