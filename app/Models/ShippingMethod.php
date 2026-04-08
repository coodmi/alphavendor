<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'zone',
        'description',
        'cost',
        'estimated_days_min',
        'estimated_days_max',
        'free_shipping_threshold',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active shipping methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered shipping methods
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get shipping methods by zone
     */
    public static function getByZone($zone)
    {
        return self::active()->where('zone', $zone)->ordered()->get();
    }

    /**
     * Get all active zones
     */
    public static function getZones()
    {
        return [
            'Dhaka City' => 'Dhaka City',
            'Dhaka Suburbs' => 'Dhaka Suburbs',
            'Major Cities' => 'Major Cities',
            'Other Areas' => 'Other Areas',
        ];
    }
}
