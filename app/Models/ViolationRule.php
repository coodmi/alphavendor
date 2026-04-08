<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationRule extends Model
{
    protected $fillable = [
        'rule_code',
        'rule_name_en',
        'rule_name_bn',
        'description',
        'penalty_type',
        'penalty_amount',
        'is_active'
    ];

    protected $casts = [
        'penalty_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get violations for this rule
     */
    public function violations()
    {
        return $this->hasMany(SellerViolation::class, 'rule_id');
    }

    /**
     * Calculate penalty amount
     */
    public function calculatePenalty($baseAmount = null)
    {
        if ($this->penalty_type === 'fixed') {
            return $this->penalty_amount;
        }
        
        // Percentage-based penalty
        if ($baseAmount) {
            return ($baseAmount * $this->penalty_amount) / 100;
        }
        
        return 0;
    }

    /**
     * Get active rules
     */
    public static function getActiveRules()
    {
        return self::where('is_active', true)->get();
    }
}
