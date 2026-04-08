<?php

namespace App\Models;

use App\Traits\HasSlugFromTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasSlugFromTitle, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'format',
        'price',
        'base_price',
        'min_quantity',
        'min_pages',
        'max_pages',
        'rating',
        'popularity',
        'stock',
        'badge',
        'config_options',
        'image',
        'is_active',
        'is_shop_only',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'stock' => 'boolean',
        'is_active' => 'boolean',
        'is_shop_only' => 'boolean',
        'config_options' => 'array',
        'min_quantity' => 'integer',
        'min_pages' => 'integer',
        'max_pages' => 'integer',
    ];

    protected $appends = ['formatted_price', 'image_url'];

    /**
     * Eager load relationships by default to prevent N+1 queries.
     */
    protected $with = ['category'];

    /**
     * Get the formatted price attribute.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '৳' . number_format($this->price, 2);
    }

    /**
     * Get the full image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return 'https://placehold.co/400x400?text=' . urlencode($this->title);
        }

        // If it's already a full URL, return as is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // If it's a storage path, return the full URL
        if (str_starts_with($this->image, 'products/')) {
            return asset('storage/' . $this->image);
        }

        // Otherwise, assume it's a public asset
        return asset($this->image);
    }

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for in-stock products
    public function scopeInStock($query)
    {
        return $query->where('stock', true);
    }

    /**
     * Scope for popular products (ordered by popularity).
     */
    public function scopePopular($query)
    {
        return $query->orderBy('popularity', 'desc');
    }

    /**
     * Scope for products by category.
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for products with price range.
     */
    public function scopePriceRange($query, ?float $minPrice = null, ?float $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope for latest products.
     */
    public function scopeLatest($query, int $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope for products with search term.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
