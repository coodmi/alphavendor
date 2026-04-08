<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SpecialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'badge_text',
        'badge_color',
        'image',
        'start_date',
        'end_date',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {
            if (empty($offer->slug)) {
                $offer->slug = Str::slug($offer->name);
            }
        });

        static::updating(function ($offer) {
            if ($offer->isDirty('name') && empty($offer->slug)) {
                $offer->slug = Str::slug($offer->name);
            }
        });
    }

    /**
     * Scope for active offers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for current offers (within date range)
     */
    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->where(function ($q2) use ($now) {
                $q2->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })->where(function ($q2) use ($now) {
                $q2->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            });
        });
    }

    /**
     * Scope for ordered offers
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get products with this special offer
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'special_offer_id');
    }

    /**
     * Check if offer is currently valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return str_starts_with($this->image, 'http')
            ? $this->image
            : asset('storage/' . $this->image);
    }
}
