<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Count of products should be loaded efficiently.
     *
     * @var array
     */
    protected $withCount = ['products'];

    // Relationship with products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relationship with active products only.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    /**
     * Scope for active categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for categories with active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('products', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Scope for categories ordered by name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}
