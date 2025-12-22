<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaLink;
use App\Models\CallLink;        // 🔥 ADD
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * 🔢 TOTAL USED LINKS (WA + CALL)
     */
    private function totalUsedLinks($userId)
    {
        return
            WaLink::where('user_id', $userId)->where('is_active', 1)->count()
          + CallLink::where('user_id', $userId)->where('is_active', 1)->count();
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ================= SUBSCRIPTION =================
        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('plan')
            ->first();

        // ================= PLAN LIMIT =================
        if ($subscription && $subscription->plan) {
            $planLimit  = $subscription->plan->links_limit;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalLimit = $planLimit + $extraLinks;
            $planName   = $subscription->plan->name;
        } else {
            // Free user
            $planLimit  = 1;
            $extraLinks = 0;
            $totalLimit = 1;
            $planName   = 'Free Plan';
        }

        // ================= LINKS COUNT (FIXED) =================
        $activeLinksCount = $this->totalUsedLinks($user->id);

        // Optional: total created links (WA + Call, active + inactive)
        $totalLinks =
            WaLink::where('user_id', $user->id)->count()
          + CallLink::where('user_id', $user->id)->count();

        $remainingLinks     = max(0, $totalLimit - $activeLinksCount);
        $canCreateMoreLinks = $activeLinksCount < $totalLimit;

        // ================= USAGE % =================
        $totalUsagePercentage = $totalLimit > 0
            ? min(100, ($activeLinksCount / $totalLimit) * 100)
            : 0;

        // ================= EXPIRY =================
        $expiryDate = $subscription ? $subscription->expires_at : null;

        $daysRemaining = 0;
        if ($expiryDate) {
            $daysRemaining = now()->diffInDays(Carbon::parse($expiryDate), false);
            if ($daysRemaining < 0) $daysRemaining = 0;
        }

        // ================= BREAKDOWN =================
        $planLinksUsed  = min($activeLinksCount, $planLimit);
        $extraLinksUsed = max(0, $activeLinksCount - $planLimit);

        // ================= RECENT LINKS =================
        // Only WhatsApp links shown here (as per your existing UI)
        $recentLinks = WaLink::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'subscription',
            'planName',
            'planLimit',
            'extraLinks',
            'totalLimit',
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