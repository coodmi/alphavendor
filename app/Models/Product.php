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
        'brand_id',
        'special_offer_id',
        'shipping_method_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'old_price',
        'stock',
        'minimum_order',
        'supplier_location',
        'supplier_location_id',
        'sku',
        'status',
        'rating',
        'reviews_count',
        'is_featured',
        'badge',
        'brand',
        'certifications',
        'exporter_rating',
        'free_shipping',
        'offer_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'free_shipping' => 'boolean',
        'certifications' => 'array',
        'exporter_rating' => 'decimal:2',
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
     * Get the brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the special offer
     */
    public function specialOffer()
    {
        return $this->belongsTo(SpecialOffer::class);
    }

    /**
     * Get the shipping method
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * Get the supplier location
     */
    public function supplierLocation()
    {
        return $this->belongsTo(SupplierLocation::class);
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

    /**
     * Get the product attributes
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->withPivot('value');
    }

    /**
     * Get the product attribute values
     */
    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Get the reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews for this product
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    /**
     * Update product rating and reviews count
     */
    public function updateRatingStats()
    {
        $approvedReviews = $this->approvedReviews;

        $this->rating = $approvedReviews->avg('rating') ?? 0;
        $this->reviews_count = $approvedReviews->count();

        $this->save();
    }

    /**
     * Get the offer for this product
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Get the order items for this product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the wishlist items for this product
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
