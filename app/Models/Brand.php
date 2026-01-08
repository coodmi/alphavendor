<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = [
        'vendor_id',
        'parent_brand_id',
        'name',
        'slug',
        'description',
        'logo',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Set the brand name and auto-generate slug
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the parent brand
     */
    public function parent()
    {
        return $this->belongsTo(Brand::class, 'parent_brand_id');
    }

    /**
     * Get the child brands
     */
    public function children()
    {
        return $this->hasMany(Brand::class, 'parent_brand_id');
    }

    /**
     * Check if this is an admin brand
     */
    public function isAdminBrand()
    {
        return is_null($this->vendor_id);
    }

    /**
     * Get all products including from child brands
     */
    public function getAllProducts()
    {
        $products = $this->products;
        foreach ($this->children as $child) {
            $products = $products->merge($child->products);
        }
        return $products;
    }

    /**
     * Get products for this brand
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the vendor who owns this brand
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
