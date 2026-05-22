<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function resolveIdFromSlug(?string $slug): ?int
    {
        if (! $slug) {
            return null;
        }

        $normalized = $slug === 'order' ? 'orders' : $slug;

        return static::where('slug', $normalized)->value('id')
            ?? static::where('slug', $slug)->value('id');
    }
}
