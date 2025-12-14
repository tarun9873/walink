<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaLink;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Get current logged in user
        $user = Auth::user();
        
        // Check if user exists
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get user's active subscription with plan
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('plan')
            ->first();
        
        // Get plan limits and extra links
        if ($subscription && $subscription->plan) {
            $planLimit = $subscription->plan->links_limit;
            $planName = $subscription->plan->name;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalLimit = $planLimit + $extraLinks;
        } else {
            // Default values if no active subscription
            $planLimit = 5; // Default free plan links
            $planName = 'Free Plan';
            $extraLinks = 0;
            $totalLimit = $planLimit;
        }
        
        // Get actual data from WaLink model for current user
        $totalLinks = WaLink::where('user_id', $user->id)->count();
        $usedLinks = WaLink::where('user_id', $user->id)->where('is_active', 1)->count();
        $remainingLinks = max(0, $totalLimit - $usedLinks);
        
        // Calculate usage percentages
        $planUsagePercentage = $planLimit > 0 ? min(100, ($usedLinks / $planLimit) * 100) : 0;
        $totalUsagePercentage = $totalLimit > 0 ? min(100, ($usedLinks / $totalLimit) * 100) : 0;
        
        // Get subscription expiry info
        $expiryDate = $subscription ? $subscription->expires_at : null;
        $daysRemaining = $subscription ? $subscription->daysRemaining() : 0;
        
        // Calculate breakdown of used links
        $planLinksUsed = min($usedLinks, $planLimit);
        $extraLinksUsed = max(0, $usedLinks - $planLimit);

        return view('dashboard', compact(
            'totalLinks',
            'usedLinks',
            'remainingLinks',
            'planUsagePercentage',
            'totalUsagePercentage',
            'planLimit',
            'extraLinks',
            'totalLimit',
            'subscription',
            'expiryDate',
            'daysRemaining',
            'planName',
            'user',
            'planLinksUsed',
            'extraLinksUsed'
        ));
    }
}