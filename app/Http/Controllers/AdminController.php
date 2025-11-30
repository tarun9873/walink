<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            'duration_days' => 'required|integer|min:1',
            'extra_links' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $plan = Plan::findOrFail($request->plan_id);

            // Deactivate any existing active subscription
            Subscription::where('user_id', $user->id)
                       ->where('status', 'active')
                       ->update(['status' => 'inactive']);

            // Create new subscription with all required fields
            $subscriptionData = [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($request->duration_days),
                'ends_at' => now()->addDays($request->duration_days),
                'status' => 'active',
                'assigned_by_admin' => true,
                'admin_id' => Auth::id(),
                'extra_links' => $request->extra_links ?? 0,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Create new subscription
            $subscription = Subscription::create($subscriptionData);

            DB::commit();

            return redirect()->route('admin.users')->with('success', "{$plan->name} plan successfully assigned to {$user->name} for {$request->duration_days} days.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign plan: ' . $e->getMessage(), [
                'user_id' => $request->user_id,
                'plan_id' => $request->plan_id,
                'error' => $e->getTraceAsString()
            ]);
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
        
        if (!$user->activeSubscription) {
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
            'ends_at' => $newExpiryDate,
            'updated_at' => now()
        ]);

        return back()->with('success', "Plan extended by {$request->extension_days} days. New expiry: {$newExpiryDate->format('d M Y')}");
    }

    // Extend Plan (Alternative method with User object)
    public function extendUserPlan(User $user, Request $request)
    {
        $request->validate(['extend_days' => 'required|integer|min:1']);
        
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            return back()->with('error', 'User has no active subscription!');
        }
        
        // Extend both expires_at and ends_at
        $newExpiryDate = $subscription->ends_at ? $subscription->ends_at->addDays($request->extend_days) : now()->addDays($request->extend_days);
        
        $subscription->update([
            'ends_at' => $newExpiryDate,
            'expires_at' => $newExpiryDate,
            'updated_at' => now()
        ]);
        
        return back()->with('success', 'Plan extended successfully!');
    }

    // Add Links to User - FIXED VERSION
     public function addLinks(User $user, Request $request)
    {
        Log::info('🟢 ADD LINKS STARTED', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'request_data' => $request->all()
        ]);

        // Validate request
        $request->validate([
            'additional_links' => 'required|integer|min:1'
        ]);

        try {
            // Get active subscription
            $subscription = $user->activeSubscription;
            
            if (!$subscription) {
                Log::error('❌ No active subscription found', ['user_id' => $user->id]);
                return back()->with('error', 'User has no active subscription!');
            }

            Log::info('📋 Subscription Details', [
                'subscription_id' => $subscription->id,
                'current_extra_links' => $subscription->extra_links,
                'status' => $subscription->status
            ]);

            // Get current values
            $currentExtraLinks = $subscription->extra_links ?? 0;
            $additionalLinks = $request->additional_links;
            $newExtraLinks = $currentExtraLinks + $additionalLinks;

            Log::info('🔢 Calculation', [
                'current' => $currentExtraLinks,
                'additional' => $additionalLinks,
                'new_total' => $newExtraLinks
            ]);

            // METHOD 1: Direct database update (Most reliable)
            $affectedRows = DB::table('subscriptions')
                ->where('id', $subscription->id)
                ->update([
                    'extra_links' => $newExtraLinks,
                    'updated_at' => now()
                ]);

            Log::info('💾 Database Update Result', [
                'affected_rows' => $affectedRows,
                'subscription_id' => $subscription->id
            ]);

            if ($affectedRows > 0) {
                // Refresh to verify
                $subscription->refresh();
                
                Log::info('✅ SUCCESS: Links added', [
                    'user_id' => $user->id,
                    'links_added' => $additionalLinks,
                    'final_extra_links' => $subscription->extra_links
                ]);
                
                return back()->with('success', "{$additionalLinks} links added successfully! Total extra links: {$subscription->extra_links}");
            } else {
                Log::error('❌ FAILED: No rows affected in database');
                return back()->with('error', 'Failed to update database. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('💥 EXCEPTION in addLinks', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $user->id
            ]);
            
            return back()->with('error', 'System error: ' . $e->getMessage());
        }
    }

    // Upgrade User Plan
    public function upgradePlan(User $user, Request $request)
    {
        $request->validate(['new_plan_id' => 'required|exists:plans,id']);
        
        $subscription = $user->activeSubscription;
        
        if (!$subscription) {
            return back()->with('error', 'User has no active subscription!');
        }
        
        $subscription->update([
            'plan_id' => $request->new_plan_id,
            'updated_at' => now()
        ]);
        
        return back()->with('success', 'Plan upgraded successfully!');
    }

    // Cancel User Subscription
    public function cancelSubscription($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->activeSubscription) {
            return back()->with('error', 'User does not have an active subscription.');
        }

        $user->activeSubscription->update([
            'status' => 'cancelled',
            'ends_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    // Cancel Subscription (Alternative method with User object)
    public function cancelUserSubscription(User $user)
    {
        $subscription = $user->activeSubscription;
        
        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled', 
                'ends_at' => now(),
                'updated_at' => now()
            ]);
            return back()->with('success', 'Subscription cancelled successfully!');
        }
        
        return back()->with('error', 'No active subscription found!');
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
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Plan created successfully.');
    }

    // Toggle Plan Status
    public function togglePlanStatus($planId)
    {
        $plan = Plan::findOrFail($planId);
        $plan->update([
            'is_active' => !$plan->is_active,
            'updated_at' => now()
        ]);

        $status = $plan->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Plan {$status} successfully.");
    }

    // Admin User Management Dashboard
    public function userManagement()
    {
        $users = User::with(['activeSubscription.plan'])->get();
        $plans = Plan::where('is_active', true)->get();
        
        return view('admin.user-management', compact('users', 'plans'));
    }

    // NEW: Debug method to check database structure
    public function checkDatabase()
    {
        $subscriptionColumns = Schema::getColumnListing('subscriptions');
        $hasExtraLinks = Schema::hasColumn('subscriptions', 'extra_links');
        
        return response()->json([
            'subscription_columns' => $subscriptionColumns,
            'has_extra_links_column' => $hasExtraLinks,
            'sample_subscription' => Subscription::first()?->toArray()
        ]);
    }

    // NEW: Manual fix for extra_links column
    public function fixExtraLinksColumn()
    {
        try {
            if (!Schema::hasColumn('subscriptions', 'extra_links')) {
                Schema::table('subscriptions', function ($table) {
                    $table->integer('extra_links')->default(0)->after('plan_id');
                });
                return response()->json(['success' => true, 'message' => 'extra_links column added successfully']);
            } else {
                return response()->json(['success' => true, 'message' => 'extra_links column already exists']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // NEW: Test method to verify addLinks functionality
    public function testAddLinks($userId, $links)
    {
        try {
            $user = User::findOrFail($userId);
            $subscription = $user->activeSubscription;

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription found for user'
                ]);
            }

            $oldValue = $subscription->extra_links ?? 0;
            $newValue = $oldValue + $links;

            $subscription->update([
                'extra_links' => $newValue,
                'updated_at' => now()
            ]);

            $subscription->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Links added successfully',
                'data' => [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'subscription_id' => $subscription->id,
                    'old_extra_links' => $oldValue,
                    'added_links' => $links,
                    'new_extra_links' => $subscription->extra_links
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ]);
        }
    }
}