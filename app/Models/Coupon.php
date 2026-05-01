<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_purchase', 'max_discount',
        'usage_limit', 'used_count', 'per_user_limit',
        'start_date', 'end_date', 'is_active', 'description',
        'product_id', 'category_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Check if coupon is applicable to a given product
     */
    public function appliesToProduct($productId, $categoryId = null): bool
    {
        // No restriction — applies to everything
        if (!$this->product_id && !$this->category_id) {
            return true;
        }
        // Product-specific
        if ($this->product_id && $this->product_id == $productId) {
            return true;
        }
        // Category-specific
        if ($this->category_id && $categoryId && $this->category_id == $categoryId) {
            return true;
        }
        return false;
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy($userId)
    {
        if (!$this->per_user_limit) {
            return true;
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        return $userUsageCount < $this->per_user_limit;
    }

    public function calculateDiscount($subtotal)
    {
        if ($this->min_purchase && $subtotal < $this->min_purchase) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return $discount;
        }

        // Fixed discount
        return min($this->value, $subtotal);
    }
}
