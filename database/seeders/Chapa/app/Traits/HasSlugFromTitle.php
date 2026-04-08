<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlugFromTitle
{
    /**
     * Boot the HasSlugFromTitle trait for a model.
     */
    protected static function bootHasSlugFromTitle(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->title);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && ! $model->isDirty('slug')) {
                $model->slug = static::generateUniqueSlug($model->title);
            }
        });
    }

    /**
     * Generate a unique slug for the model.
     */
    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug exists and append number if it does
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
