<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'parent_category_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Set the category name and auto-generate slug
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get products in this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products count
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->where('status', 'active')->count();
    }

    /**
     * Get the vendor who owns this category
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the parent category (admin category)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    /**
     * Get child categories (vendor custom categories)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    /**
     * Check if this is an admin category
     */
    public function isAdminCategory()
    {
        return is_null($this->vendor_id);
    }

    /**
     * Get all products including from child categories
     */
    public function getAllProducts()
    {
        $productIds = $this->products()->pluck('id');
        
        // Get products from child categories
        $childCategories = $this->children()->pluck('id');
        if ($childCategories->isNotEmpty()) {
            $childProductIds = Product::whereIn('category_id', $childCategories)->pluck('id');
            $productIds = $productIds->merge($childProductIds);
        }
        
        return Product::whereIn('id', $productIds);
    }
}
