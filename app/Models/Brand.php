<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = [
        'vendor_id',
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
