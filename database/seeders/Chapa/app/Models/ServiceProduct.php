<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceProduct extends Model
{
    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && ! $product->isDirty('slug')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    protected static function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($id, fn ($query) => $query->where('id', '!=', $id))
            ->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function configOptions()
    {
        return $this->hasMany(ServiceConfigOption::class)->orderBy('display_order');
    }
}
