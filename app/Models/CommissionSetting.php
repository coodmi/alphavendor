<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    protected $fillable = [
        'type', 'category_id', 'product_id', 'commission_rate', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getCommissionRate($productId, $categoryId)
    {
        // Check product-specific commission first
        $productCommission = self::where('type', 'product')
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->first();

        if ($productCommission) {
            return $productCommission->commission_rate;
        }

        // Check category-specific commission
        $categoryCommission = self::where('type', 'category')
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->first();

        if ($categoryCommission) {
            return $categoryCommission->commission_rate;
        }

        // Fall back to global commission
        $globalCommission = self::where('type', 'global')
            ->where('is_active', true)
            ->first();

        return $globalCommission ? $globalCommission->commission_rate : 10; // Default 10%
    }
}
