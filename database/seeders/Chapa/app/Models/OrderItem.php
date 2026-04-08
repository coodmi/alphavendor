<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_title',
        'product_name',
        'product_type',
        'product_image',
        'format',
        'quantity',
        'price',
        'configuration',
        'design_files',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'configuration' => 'array',
        'design_files' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(OrderItemOption::class);
    }

    /**
     * Get the total price for this order item.
     */
    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Scope for items by product.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Get configuration attribute (parse JSON).
     *
     * @param  string|null  $value
     * @return array
     */
    public function getConfigurationAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get design files attribute (parse JSON).
     *
     * @param  string|null  $value
     * @return array
     */
    public function getDesignFilesAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
}
