<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function waLinks()
    {
        return $this->hasMany(WaLink::class);
    }

    // Get ACTIVE subscription (with expiry check)
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->with('plan');
    }

    // ============================================
    // HELPER METHODS - FIXED VERSION
    // ============================================
    
    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription()
    {
        return \App\Models\Subscription::where('user_id', $this->id)
            ->where('status', 'active')
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Check if user can create more links
     * FIXED: Free users can create 1 link
     */
    public function canCreateMoreLinks()
    {
        // Count only ACTIVE links
        $currentLinksCount = $this->waLinks()->where('is_active', 1)->count();
        
        // Log for debugging
        \Log::info('User canCreateMoreLinks check', [
            'user_id' => $this->id,
            'active_links_count' => $currentLinksCount,
            'has_active_subscription' => $this->hasActiveSubscription()
        ]);
        
        // If user has active subscription
        if ($this->hasActiveSubscription()) {
            $subscription = $this->activeSubscription()->first();
            
            if (!$subscription || !$subscription->plan) {
                // Fallback: free user can create 1 link
                return $currentLinksCount < 1;
            }
            
            $planLimit = $subscription->plan->links_limit;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalAllowed = $planLimit + $extraLinks;
            
            return $currentLinksCount < $totalAllowed;
        }
        
        // Free user (no active subscription) - can create 1 link
        return $currentLinksCount < 1;
    }

    /**
     * Get active links count
     */
    public function getActiveLinksCountAttribute()
    {
        return $this->waLinks()->where('is_active', 1)->count();
    }

    /**
     * Get remaining links count
     * FIXED: Free users get 1 - current links
     */
    public function getRemainingLinksAttribute()
    {
        $currentLinksCount = $this->active_links_count;
        
        // If user has active subscription
        if ($this->hasActiveSubscription()) {
            $subscription = $this->activeSubscription()->first();
            
            if (!$subscription || !$subscription->plan) {
                // Fallback: free user can create 1 link
                return max(0, 1 - $currentLinksCount);
            }
            
            $planLimit = $subscription->plan->links_limit;
            $extraLinks = $subscription->extra_links ?? 0;
            $totalAllowed = $planLimit + $extraLinks;
            
            return max(0, $totalAllowed - $currentLinksCount);
        }
        
        // Free user - can create 1 link
        return max(0, 1 - $currentLinksCount);
    }

    /**
     * Get total allowed links (plan + extra)
     */
    public function getTotalAllowedLinksAttribute()
    {
        if ($this->hasActiveSubscription()) {
            $subscription = $this->activeSubscription()->first();
            
            if (!$subscription || !$subscription->plan) {
                return 1; // Free plan limit is 1
            }
            
            return $subscription->plan->links_limit + ($subscription->extra_links ?? 0);
        }
        
        return 1; // Free plan limit is 1
    }

    /**
     * Get subscription expiry date
     */
    public function getSubscriptionExpiryAttribute()
    {
        $subscription = $this->activeSubscription()->first();
        return $subscription ? $subscription->expires_at : null;
    }

    /**
     * Get days remaining in subscription
     */
    public function getSubscriptionDaysRemainingAttribute()
    {
        $subscription = $this->activeSubscription()->first();
        
        if (!$subscription) {
            return 0;
        }
        
        $expiry = Carbon::parse($subscription->expires_at);
        $now = Carbon::now();
        
        if ($now->greaterThan($expiry)) {
            return 0;
        }
        
        return $now->diffInDays($expiry);
    }

    /**
     * Get current plan name
     */
    public function getCurrentPlanNameAttribute()
    {
        if ($this->hasActiveSubscription()) {
            $subscription = $this->activeSubscription()->first();
            return $subscription && $subscription->plan ? $subscription->plan->name : 'Free Plan';
        }
        
        return 'Free Plan';
    }

    /**
     * Get plan limit
     */
    public function getPlanLimitAttribute()
    {
        if ($this->hasActiveSubscription()) {
            $subscription = $this->activeSubscription()->first();
            return $subscription && $subscription->plan ? $subscription->plan->links_limit : 1;
        }
        
        return 1; // Free plan limit is 1
    }

    /**
     * Get extra links
     */
    public function getExtraLinksAttribute()
    {
        if ($this->hasActiveSubscription()) {
            $subscription = $this->activeSubscription()->first();
            return $subscription ? $subscription->extra_links : 0;
        }
        
        return 0;
    }

    /**
     * Check if user is admin (based on email)
     */
    public function getIsAdminAttribute()
    {
        return in_array($this->email, ['ak3400988@gmail.com', 'admin@example.com']);
    }

    /**
     * Get subscription status for display
     */
    public function getSubscriptionStatusAttribute()
    {
        if ($this->hasActiveSubscription()) {
            $daysRemaining = $this->subscription_days_remaining;
            
            if ($daysRemaining <= 3) {
                return [
                    'status' => 'expiring_soon',
                    'message' => "Subscription expires in {$daysRemaining} days",
                    'color' => 'warning'
                ];
            }
            
            return [
                'status' => 'active',
                'message' => "Active ({$daysRemaining} days remaining)",
                'color' => 'success'
            ];
        }
        
        // Check if user has any expired subscription
        $hasExpired = \App\Models\Subscription::where('user_id', $this->id)
            ->where('status', 'active')
            ->where('expires_at', '<=', Carbon::now())
            ->exists();
            
        if ($hasExpired) {
            return [
                'status' => 'expired',
                'message' => 'Subscription expired (1 link only)',
                'color' => 'danger'
            ];
        }
        
        return [
            'status' => 'free',
            'message' => 'Free Plan (1 link only)',
            'color' => 'secondary'
        ];
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentageAttribute()
    {
        $totalAllowed = $this->total_allowed_links;
        $activeCount = $this->active_links_count;
        
        if ($totalAllowed <= 0) {
            return 0;
        }
        
        $percentage = ($activeCount / $totalAllowed) * 100;
        return min(100, $percentage);
    }
// User.php में add करें
public function callLinks()
{
    return $this->hasMany(CallLink::class);
}

public function active_call_links_count()
{
    return $this->callLinks()->where('is_active', true)->count();
}
    /**
     * Get subscription history
     */
    public function subscriptionHistory()
    {
        return $this->subscriptions()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}