<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        // REMOVE THIS LINE: 'password' => 'hashed', // ❌ Remove this line
    ];

    // Subscription relationships
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function waLinks()
    {
        return $this->hasMany(WaLink::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->active()->latest();
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription()->exists();
    }

    public function canCreateMoreLinks()
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $subscription = $this->activeSubscription;
        $currentLinksCount = $this->waLinks()->count();
        
        // NEW: Plan links + Subscription extra links combine karo
        $totalAllowed = $subscription->plan->links_limit + $subscription->extra_links;
        return $currentLinksCount < $totalAllowed;
    }

    public function getRemainingLinksAttribute()
    {
        if (!$this->hasActiveSubscription()) {
            return 0;
        }

        $subscription = $this->activeSubscription;
        $currentLinksCount = $this->waLinks()->count();
        
        // NEW: Plan links + Subscription extra links combine karo
        $totalAllowed = $subscription->plan->links_limit + $subscription->extra_links;
        return max(0, $totalAllowed - $currentLinksCount);
    }
}