<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class GlobalServiceOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_bn',
        'type',
        'display_order',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Option type constants
    const TYPE_RADIO = 'radio';
    const TYPE_BUTTON_GROUP = 'button_group';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_NUMBER = 'number';
    const TYPE_TEXT = 'text';
    const TYPE_FILE = 'file';
    const TYPE_DATE = 'date';
    const TYPE_COLOR = 'color';

    /**
     * Get all option values for this option.
     */
    public function values(): HasMany
    {
        return $this->hasMany(OptionValue::class, 'option_id')->orderBy('display_order');
    }

    /**
     * Get all category assignments for this option.
     */
    public function categoryAssignments(): HasMany
    {
        return $this->hasMany(OptionCategoryAssignment::class, 'option_id');
    }

    /**
     * Get all categories this option is assigned to.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'option_category_assignments',
            'option_id',
            'category_id'
        );
    }

    /**
     * Get the localized name based on current app locale.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        
        if ($locale === 'bn' && !empty($this->name_bn)) {
            return $this->name_bn;
        }
        
        return $this->name_en;
    }

    /**
     * Check if this option is applicable to a specific category.
     */
    public function isApplicableToCategory(int $categoryId): bool
    {
        // Check if option applies to all categories
        if ($this->categoryAssignments()->where('applies_to_all', true)->exists()) {
            return true;
        }

        // Check if option is assigned to this specific category
        return $this->categoryAssignments()
            ->where('category_id', $categoryId)
            ->exists();
    }

    /**
     * Get only active values for this option.
     */
    public function getActiveValues(): Collection
    {
        return $this->values()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Scope to get only active options.
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
