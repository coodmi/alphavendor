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

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function parent()
    {
        return $this->belongsTo(Brand::class, 'parent_brand_id');
    }

    public function children()
    {
        return $this->hasMany(Brand::class, 'parent_brand_id');
    }

    public function isAdminBrand(): bool
    {
        return is_null($this->vendor_id);
    }
}
