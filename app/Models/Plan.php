<?php
// app/Models/Plan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'links_limit',
        'duration_days',
        'description',
        'is_active',
        'features',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getPriceFormattedAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getDurationFormattedAttribute()
    {
        if ($this->duration_days >= 365) {
            $years = $this->duration_days / 365;
            return $years == 1 ? '1 Year' : $years . ' Years';
        }
        
        if ($this->duration_days >= 30) {
            $months = $this->duration_days / 30;
            return $months == 1 ? '1 Month' : $months . ' Months';
        }
        
        return $this->duration_days . ' Days';
    }

    public function isFree()
    {
        return $this->price == 0;
    }
}