<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug', 
        'phone',
        'message',
        'full_url',
        'clicks',
        'is_active'
    ];

    /**
     * Relationship with click tracking
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(WaLinkClick::class, 'wa_link_id');
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get daily clicks count
     */
    public function getDailyClicks()
    {
        return $this->clicks()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
    }

    /**
     * Get clicks by country
     */
    public function getClicksByCountry()
    {
        return $this->clicks()
            ->selectRaw('country, COUNT(*) as count')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('count', 'DESC')
            ->get();
    }

    /**
     * Get clicks by device
     */
    public function getClicksByDevice()
    {
        return $this->clicks()
            ->selectRaw('device_type, COUNT(*) as count')
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderBy('count', 'DESC')
            ->get();
    }
}