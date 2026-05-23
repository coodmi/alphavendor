<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistrictDeliveryCharge extends Model
{
    protected $fillable = [
        'district',
        'division',
        'base_charge',
        'is_active',
    ];

    protected $casts = [
        'base_charge' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public static function baseChargeForDistrict(string $district): float
    {
        $normalized = static::normalizeDistrict($district);

        $row = static::query()
            ->where('is_active', true)
            ->get()
            ->first(fn ($charge) => static::normalizeDistrict($charge->district) === $normalized);

        return $row ? (float) $row->base_charge : 120.0;
    }

    public static function normalizeDistrict(string $district): string
    {
        return strtolower(trim($district));
    }
}
