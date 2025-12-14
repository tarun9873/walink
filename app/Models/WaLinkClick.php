<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaLinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'wa_link_id',
        'ip_address',
        'user_agent', 
        'country',
        'city',
        'referrer',
        'device_type',
        'browser',
        'platform'
    ];

    /**
     * Relationship with WaLink
     */
    public function waLink(): BelongsTo
    {
        return $this->belongsTo(WaLink::class, 'wa_link_id');
    }
}