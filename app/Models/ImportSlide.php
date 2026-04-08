<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportSlide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'cta_text',
        'cta_link',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
