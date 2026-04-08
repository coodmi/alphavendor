<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'content',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get setting by section key
     */
    public static function getSection($sectionKey)
    {
        return static::where('section_key', $sectionKey)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Update or create section
     */
    public static function updateSection($sectionKey, $content)
    {
        return static::updateOrCreate(
            ['section_key' => $sectionKey],
            ['content' => $content, 'is_active' => true]
        );
    }
}