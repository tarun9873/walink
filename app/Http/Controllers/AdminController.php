<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\WaLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Admin Dashboard
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => Subscription::where('status', 'active')
                ->where('expires_at', '>', now())
                ->count(),
            'total_plans' => Plan::where('is_active', true)->count(),
            'revenue_today' => Subscription::whereDate('created_at', today())->count() * 299,
            'revenue_this_month' => $this->calculateMonthlyRevenue(),
            'total_links' => \App\Models\WaLink::count(),
            'active_links' => \App\Models\WaLink::where('is_active', true)->count(),
        ];

        $recentSubscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        // Add days remaining to each subscription
        foreach ($recentSubscriptions as $subscription) {
            if ($subscription->expires_at) {
                $expiry = Carbon::parse($subscription->expires_at);
                $now = Carbon::now();
                $subscription->days_remaining = $now->greaterThan($expiry) ? 0 : $now->diffInDays($expiry);
            } else {
                $subscription->days_remaining = null;
            }
        }

        // Get recent payments if Payment model exists
        try {
            $recentPayments = class_exists('\App\Models\Payment') 
                ? \App\Models\Payment::with(['user', 'plan'])
                    ->where('status', 'success')
                    ->latest()
                    ->take(5)
                    ->get()
                : collect();
        } catch (\Exception $e) {
            $recentPayments = collect();
        }

        return view('admin.dashboard', compact(
            'stats', 
            'recentSubscriptions',
            'recentPayments'
        ));
    }

    // Calculate monthly revenue
    private function calculateMonthlyRevenue()
    {
        return Subscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() * 299;
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
        $plans = Plan::orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
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
            $expiresAt = now()->addDays($request->duration_days);
            
            $subscriptionData = [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => $expiresAt,
                'ends_at' => $expiresAt,
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

            // Calculate days remaining for the new subscription
            $daysRemaining = Carbon::now()->diffInDays($expiresAt);

            return redirect()->route('admin.users')->with('success', 
                "{$plan->name} plan successfully assigned to {$user->name} for {$request->duration_days} days. " .
                "($daysRemaining days remaining)"
            );

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

    


public function transferLinks(Request $request, User $user)
{
    $request->validate([
        'to_user_id'  => 'required|exists:users,id',
        'links_count' => 'required|integer|min:1',
    ]);

    $fromUser = $user;
    $toUser   = User::findOrFail($request->to_user_id);
    $count    = $request->links_count;

    // ❌ Same user check
    if ($fromUser->id === $toUser->id) {
        return back()->with('error', 'Same user me transfer allowed nahi.');
    }

    // Active subscriptions
    $fromSub = $fromUser->activeSubscription;
    $toSub   = $toUser->activeSubscription;

    if (!$fromSub || !$toSub) {
        return back()->with('error', 'Dono users ke paas active plan hona chahiye.');
    }

    // =============================
    // 🔢 CURRENT EFFECTIVE LIMIT
    // =============================
    $fromEffective = $fromSub->plan->links_limit + ($fromSub->extra_links ?? 0);

    if ($fromEffective < $count) {
        return back()->with('error', 'Source user ke paas itni plan limit nahi hai.');
    }

    // =============================
    // 🛑 SAFETY CHECK (MOST IMPORTANT)
    // =============================
    // Transfer ke baad user over-limit to nahi ho jayega?
    $usedLinks   = $fromUser->waLinks()->count();
    $futureLimit = $fromEffective - $count;

    if ($usedLinks > $futureLimit) {
        return back()->with(
            'error',
            "Transfer allowed nahi.
            User already {$usedLinks} links use kar chuka hai,
            transfer ke baad limit {$futureLimit} ho jayegi."
        );
    }

    DB::beginTransaction();
    try {

        // 🔻 SOURCE USER: plan limit kam (negative extra_links allowed)
        $fromSub->update([
            'extra_links' => ($fromSub->extra_links ?? 0) - $count,
        ]);

        // 🔺 TARGET USER: plan limit badhao
        $toSub->update([
            'extra_links' => ($toSub->extra_links ?? 0) + $count,
        ]);

        DB::commit();

        return back()->with(
            'success',
            "Plan limit successfully transferred ✅
            {$count} links shifted from {$fromUser->email} to {$toUser->email}.
            Used links untouched ✔"
        );

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
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

        $daysRemaining = Carbon::now()->diffInDays($newExpiryDate);

        return back()->with('success', 
            "Plan extended by {$request->extension_days} days. " .
            "New expiry: {$newExpiryDate->format('d M Y')} ($daysRemaining days remaining)"
        );
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
        
        $daysRemaining = Carbon::now()->diffInDays($newExpiryDate);
        
        return back()->with('success', 
            "Plan extended by {$request->extend_days} days. " .
            "($daysRemaining days remaining)"
        );
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
        
        $newPlan = Plan::findOrFail($request->new_plan_id);
        
        $subscription->update([
            'plan_id' => $request->new_plan_id,
            'updated_at' => now()
        ]);
        
        // Calculate days remaining if expiry exists
        $daysRemaining = null;
        if ($subscription->expires_at) {
            $daysRemaining = Carbon::now()->diffInDays($subscription->expires_at);
        }
        
        return back()->with('success', 
            "Plan upgraded to {$newPlan->name} successfully!" . 
            ($daysRemaining ? " ($daysRemaining days remaining)" : "")
        );
    }

    // Cancel User Subscription
    public function cancelSubscription($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->activeSubscription) {
            return back()->with('error', 'User does not have an active subscription.');
        }

        $planName = $user->activeSubscription->plan->name ?? 'Unknown Plan';
        
        $user->activeSubscription->update([
            'status' => 'cancelled',
            'ends_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', "{$planName} subscription cancelled successfully.");
    }

    // Cancel Subscription (Alternative method with User object)
    public function cancelUserSubscription(User $user)
    {
        $subscription = $user->activeSubscription;
        
        if ($subscription) {
            $planName = $subscription->plan->name ?? 'Unknown Plan';
            $subscription->update([
                'status' => 'cancelled', 
                'ends_at' => now(),
                'updated_at' => now()
            ]);
            return back()->with('success', "{$planName} subscription cancelled successfully!");
        }
        
        return back()->with('error', 'No active subscription found!');
    }

    // Create New Plan - UPDATED VERSION
    public function createPlan(Request $request)
    {
        \Log::info('🎯 CREATE PLAN FORM DATA:', $request->all());
        
        $request->validate([
            'name' => 'required|string|max:255|unique:plans,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'links_limit' => 'required|integer|min:1',
            'duration_days' => 'required|integer|min:1',
            'billing_cycle' => 'required|in:month,year,custom',
            'features' => 'required|array',
            'is_active' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        try {
            // Generate a unique slug
            $slug = \Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Plan::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Convert features array properly
            $featuresArray = [];
            if (is_array($request->features) && isset($request->features[0])) {
                $featuresString = $request->features[0];
                $featuresArray = array_filter(array_map('trim', explode("\n", $featuresString)));
            }

            $planData = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'links_limit' => $request->links_limit,
                'duration_days' => $request->duration_days,
                'billing_cycle' => $request->billing_cycle,
                'features' => $featuresArray,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                'is_popular' => $request->has('is_popular') ? (bool)$request->is_popular : false,
                'sort_order' => $request->sort_order ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            \Log::info('📦 PLAN DATA TO SAVE:', $planData);

            $plan = Plan::create($planData);

            \Log::info('✅ PLAN CREATED SUCCESSFULLY:', ['plan_id' => $plan->id]);

            return redirect()->route('admin.plans')->with('success', 'Plan created successfully.');

        } catch (\Exception $e) {
            \Log::error('❌ PLAN CREATION FAILED:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to create plan: ' . $e->getMessage())
                         ->withInput();
        }
    }

    // Delete Plan
    public function deletePlan($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            
            // Check if plan has any subscriptions
            $subscriptionCount = $plan->subscriptions()->count();
            
            if ($subscriptionCount > 0) {
                return redirect()->route('admin.plans')
                    ->with('error', "Cannot delete plan '{$plan->name}'. It has {$subscriptionCount} active subscription(s).");
            }
            
            $planName = $plan->name;
            $plan->delete();
            
            return redirect()->route('admin.plans')
                ->with('success', "Plan '{$planName}' deleted successfully.");
                
        } catch (\Exception $e) {
            \Log::error('Plan deletion failed: ' . $e->getMessage());
            return redirect()->route('admin.plans')
                ->with('error', 'Failed to delete plan: ' . $e->getMessage());
        }
    }

    // Toggle Plan Status
    public function togglePlanStatus($planId)
    {
        $plan = Plan::findOrFail($planId);
        $oldStatus = $plan->is_active;
        $newStatus = !$oldStatus;
        
        $plan->update([
            'is_active' => $newStatus,
            'updated_at' => now()
        ]);

        $status = $newStatus ? 'activated' : 'deactivated';
        return back()->with('success', "Plan '{$plan->name}' {$status} successfully.");
    }

    // Admin User Management Dashboard
    public function userManagement()
    {
        $users = User::with(['activeSubscription.plan'])->get();
        $plans = Plan::where('is_active', true)->get();
        
        return view('admin.user-management', compact('users', 'plans'));
    }

    // Subscriptions Management
    public function subscriptions()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->paginate(20);
            
        // Add days remaining to each subscription
        foreach ($subscriptions as $subscription) {
            if ($subscription->expires_at) {
                $expiry = Carbon::parse($subscription->expires_at);
                $now = Carbon::now();
                $subscription->days_remaining = $now->greaterThan($expiry) ? 0 : $now->diffInDays($expiry);
            } else {
                $subscription->days_remaining = null;
            }
        }
        
        return view('admin.subscriptions', compact('subscriptions'));
    }

    // View User Details
    public function viewUser($id)
    {
        $user = User::with([
            'subscriptions.plan',
            'waLinks'
        ])->findOrFail($id);
        
        // Calculate subscription days remaining
        if ($user->activeSubscription && $user->activeSubscription->expires_at) {
            $expiry = Carbon::parse($user->activeSubscription->expires_at);
            $now = Carbon::now();
            $user->activeSubscription->days_remaining = $now->greaterThan($expiry) ? 0 : $now->diffInDays($expiry);
        }
        
        $plans = Plan::where('is_active', true)->get();
        
        return view('admin.view-user', compact('user', 'plans'));
    }

    // Get Revenue Chart Data (AJAX)
    public function getRevenueData(Request $request)
    {
        $range = $request->input('range', 'monthly');

        switch ($range) {
            case 'weekly':
                $data = Subscription::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) * 299 as revenue')
                    )
                    ->where('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            case 'monthly':
                $data = Subscription::select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                        DB::raw('COUNT(*) * 299 as revenue')
                    )
                    ->where('created_at', '>=', now()->subMonths(6))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                break;

            case 'yearly':
                $data = Subscription::select(
                        DB::raw('YEAR(created_at) as year'),
                        DB::raw('COUNT(*) * 299 as revenue')
                    )
                    ->where('created_at', '>=', now()->subYears(3))
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                break;

            default:
                $data = collect();
        }

        return response()->json([
            'labels' => $data->pluck($range === 'monthly' ? 'month' : ($range === 'yearly' ? 'year' : 'date'))->toArray(),
            'revenue' => $data->pluck('revenue')->toArray(),
        ]);
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