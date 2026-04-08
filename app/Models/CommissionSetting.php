<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    protected $fillable = [
        'category_id',
        'seller_type',
        'commission_rate',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get commission rate for a specific category and seller type
     */
    public static function getCommissionRate($categoryId, $sellerType)
    {
        $commission = self::where('category_id', $categoryId)
            ->where('seller_type', $sellerType)
            ->where('is_active', true)
            ->first();

        return $commission ? $commission->commission_rate : 10; // Default 10%
    }

    /**
     * Get all commission rates for a category (all seller types)
     */
    public static function getCategoryCommissions($categoryId)
    {
        return self::where('category_id', $categoryId)
            ->where('is_active', true)
            ->get()
            ->keyBy('seller_type');
    }
}
