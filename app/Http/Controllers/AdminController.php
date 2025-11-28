<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // Admin Dashboard
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_plans' => Plan::where('is_active', true)->count(),
            'revenue_today' => Subscription::whereDate('created_at', today())->count() * 299,
        ];

        $recentSubscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSubscriptions'));
    }

    // Users Management
    public function users()
    {
        $users = User::with(['subscriptions.plan'])
                    ->latest()
                    ->paginate(10);

        return view('admin.users', compact('users'));
    }

    // Plans Management
    public function plans()
    {
        $plans = Plan::latest()->get();
        return view('admin.plans', compact('plans'));
    }

    // Assign Plan Form
    public function assignPlanForm($userId = null)
    {
        $users = User::all();
        $plans = Plan::where('is_active', true)->get();
        $selectedUser = $userId ? User::find($userId) : null;

        return view('admin.assign-plan', compact('users', 'plans', 'selectedUser'));
    }

    // Assign Plan to User - FIXED VERSION
    public function assignPlan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'duration_days' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $plan = Plan::findOrFail($request->plan_id);

            // Deactivate any existing active subscription
            $user->subscriptions()->where('status', 'active')->update(['status' => 'inactive']);

            // Create new subscription with all required fields
            $subscriptionData = [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($request->duration_days),
                'ends_at' => now()->addDays($request->duration_days),
                'status' => 'active',
                'assigned_by_admin' => true,
                'notes' => $request->notes
            ];

            // Create new subscription
            $subscription = Subscription::create($subscriptionData);

            DB::commit();

            return redirect()->route('admin.users')->with('success', "{$plan->name} plan successfully assigned to {$user->name} for {$request->duration_days} days.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign plan: ' . $e->getMessage());
        }
    }

    // Extend User Plan
    public function extendPlan(Request $request, $userId)
    {
        $request->validate([
            'extension_days' => 'required|integer|min:1'
        ]);

        $user = User::findOrFail($userId);
        
        if (!$user->hasActiveSubscription()) {
            return back()->with('error', 'User does not have an active subscription.');
        }

        $subscription = $user->activeSubscription;
        
        // Check if expires_at exists and update it
        if ($subscription->expires_at) {
            $newExpiryDate = $subscription->expires_at->addDays($request->extension_days);
        } else {
            $newExpiryDate = now()->addDays($request->extension_days);
        }
        
        $subscription->update([
            'expires_at' => $newExpiryDate,
            'ends_at' => $newExpiryDate
        ]);

        return back()->with('success', "Plan extended by {$request->extension_days} days. New expiry: {$newExpiryDate->format('d M Y')}");
    }

    // Cancel User Subscription
    public function cancelSubscription($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->hasActiveSubscription()) {
            return back()->with('error', 'User does not have an active subscription.');
        }

        $user->activeSubscription->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    // Create New Plan
    public function createPlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:month,year',
            'links_limit' => 'required|integer|min:1',
            'features' => 'required|array',
            'is_popular' => 'boolean'
        ]);

        Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle,
            'links_limit' => $request->links_limit,
            'features' => $request->features,
            'is_popular' => $request->is_popular ?? false,
            'is_active' => true
        ]);

        return back()->with('success', 'Plan created successfully.');
    }

    // Toggle Plan Status
    public function togglePlanStatus($planId)
    {
        $plan = Plan::findOrFail($planId);
        $plan->update([
            'is_active' => !$plan->is_active
        ]);

        $status = $plan->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Plan {$status} successfully.");
    }
}