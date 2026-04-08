<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodCommissionSetting extends Model
{
    protected $fillable = [
        'commission_rate',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2'
    ];

    /**
     * Get active COD commission rate
     */
    public static function getActiveRate()
    {
        $setting = self::where('is_active', true)->first();
        return $setting ? $setting->commission_rate : 0;
    }

    /**
     * Check if COD commission is enabled
     */
    public static function isEnabled()
    {
        return self::where('is_active', true)->exists();
    }
}
