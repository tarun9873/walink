<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaLink;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get active subscription (not expired)
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();
            
        // Calculate link limits - FIXED HERE: 5 को 1 किया
        if ($subscription && $subscription->plan) {
            // Active subscription
            $planLimit = $subscription->plan->links_limit;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalLimit = $planLimit + $extraLinks;
            $planName = $subscription->plan->name;
        } else {
            // Free plan - NO active subscription
            $planLimit = 1; // 👈 यहाँ 5 की जगह 1 किया
            $extraLinks = 0;
            $totalLimit = $planLimit;
            $planName = 'Free Plan';
        }
        
        // Get links data
        $totalLinks = WaLink::where('user_id', $user->id)->count();
        $activeLinksCount = WaLink::where('user_id', $user->id)
            ->where('is_active', 1)
            ->count();
            
        $remainingLinks = max(0, $totalLimit - $activeLinksCount);
        $canCreateMoreLinks = $activeLinksCount < $totalLimit;
        
        // Calculate usage percentages
        $totalUsagePercentage = $totalLimit > 0 ? min(100, ($activeLinksCount / $totalLimit) * 100) : 0;
        
        // Get subscription expiry info
        $expiryDate = $subscription ? $subscription->expires_at : null;
        
        // Calculate days remaining
        $daysRemaining = 0;
        if ($expiryDate) {
            $daysRemaining = now()->diffInDays(Carbon::parse($expiryDate), false);
            if ($daysRemaining < 0) $daysRemaining = 0;
        }
        
        // Calculate breakdown of used links
        $planLinksUsed = min($activeLinksCount, $planLimit);
        $extraLinksUsed = max(0, $activeLinksCount - $planLimit);
        
        // Get recent links
        $recentLinks = WaLink::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'subscription',
            'planName',
            'planLimit', // ✅ अब यह 1 होगा free users के लिए
            'extraLinks',
            'totalLimit', // ✅ अब यह 1 होगा free users के लिए
            'totalLinks',
            'activeLinksCount',
            'remainingLinks',
            'canCreateMoreLinks',
            'totalUsagePercentage',
            'expiryDate',
            'daysRemaining',
            'planLinksUsed',
            'extraLinksUsed',
            'recentLinks'
        ));
    }
}