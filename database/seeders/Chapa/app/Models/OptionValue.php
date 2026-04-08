<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionValue extends Model
{
    protected $fillable = [
        'option_id',
        'label_en',
        'label_bn',
        'description',
        'price_modifier',
        'is_default',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the option this value belongs to.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(GlobalServiceOption::class, 'option_id');
    }

    /**
     * Get the localized label based on current app locale.
     */
    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        
        if ($locale === 'bn' && !empty($this->attributes['label_bn'])) {
            return $this->attributes['label_bn'];
        }
        
        return $this->attributes['label_en'];
    }

    /**
     * Get formatted price with Bangla currency symbol.
     */
    public function getFormattedPriceAttribute(): string
    {
        $modifier = $this->price_modifier;
        
        if ($modifier == 0) {
            return 'Free';
        }
        
        if ($modifier > 0) {
            return '+৳' . number_format($modifier, 2);
        }
        
        return '-৳' . number_format(abs($modifier), 2);
    }

    /**
     * Scope to get only active values.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
