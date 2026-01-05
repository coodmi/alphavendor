<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'vendor_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'old_price',
        'stock',
        'sku',
        'status',
        'rating',
        'reviews_count',
        'is_featured',
        'badge',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    /**
     * Set the product name and auto-generate slug
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the vendor
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        return 0;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->stock > 0 && $this->status === 'active';
    }
}
