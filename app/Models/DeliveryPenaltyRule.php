<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPenaltyRule extends Model
{
    protected $fillable = [
        'days_threshold',
        'penalty_amount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'penalty_amount' => 'decimal:2',
    ];

    /**
     * Get all active rules ordered by threshold ascending.
     * The highest matching threshold wins.
     */
    public static function activeRules()
    {
        return self::where('is_active', true)->orderBy('days_threshold')->get();
    }

    /**
     * Find the applicable penalty for a given number of late days.
     * Returns the rule with the highest threshold that is <= $daysLate.
     */
    public static function findApplicable(int $daysLate): ?self
    {
        return self::where('is_active', true)
            ->where('days_threshold', '<=', $daysLate)
            ->orderByDesc('days_threshold')
            ->first();
    }
}
