<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'special_offer_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the special offer
     */
    public function specialOffer()
    {
        return $this->belongsTo(SpecialOffer::class);
    }

    /**
     * Get the banner link (auto-generates from special offer if set)
     */
    public function getLinkUrlAttribute()
    {
        if ($this->special_offer_id && $this->specialOffer) {
            return route('special-offers.show', $this->specialOffer->slug);
        }
        
        return $this->link ?? '#';
    }

    /**
     * Scope to get only active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the image URL (handles both URLs and storage paths)
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
