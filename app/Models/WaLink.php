<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','phone','message','slug','full_url','is_active'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}