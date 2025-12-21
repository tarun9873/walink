<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_link_id',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'referrer',
        'device_type',
        'browser',
        'platform'
    ];

    public function callLink()
    {
        return $this->belongsTo(CallLink::class);
    }
}