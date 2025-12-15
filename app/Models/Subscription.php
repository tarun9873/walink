<?php
// app/Models/Subscription.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'is_active',
        'status',
        'stripe_subscription_id',
        'stripe_price_id',
        'trial_ends_at',
        'extra_links'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isValid()
    {
        return $this->is_active && 
               $this->status === 'active' && 
               $this->expires_at->isFuture();
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function daysRemaining()
    {
        return max(0, Carbon::now()->diffInDays($this->expires_at, false));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now());
    }
    public function getDaysRemainingAttribute()
    {
        $expiry = Carbon::parse($this->expires_at);
        $now = Carbon::now();
        
        if ($now->greaterThan($expiry)) {
            return 0;
        }
        
        return $now->diffInDays($expiry);
    }
    
    /**
     * Check if subscription is expired
     */
    public function getIsExpiredAttribute()
    {
        return Carbon::now()->greaterThan(Carbon::parse($this->expires_at));
    }
}