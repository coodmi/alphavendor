<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VendorBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'bg_color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($badge) {
            if (empty($badge->slug)) {
                $badge->slug = Str::slug($badge->name);
            }
        });

        static::updating(function ($badge) {
            if ($badge->isDirty('name') && empty($badge->slug)) {
                $badge->slug = Str::slug($badge->name);
            }
        });
    }

    /**
     * Scope for active badges
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered badges
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get vendors with this badge
     */
    public function vendors()
    {
        return $this->hasMany(User::class, 'vendor_badge_id');
    }
}
