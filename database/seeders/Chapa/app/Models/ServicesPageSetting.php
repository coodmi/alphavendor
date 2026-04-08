<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesPageSetting extends Model
{
    protected $fillable = ['section', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Get settings for a specific section
     */
    public static function getSection(string $section): ?object
    {
        $setting = self::where('section', $section)->first();
        
        if (!$setting) {
            return (object)[];
        }

        return (object)$setting->content;
    }

    /**
     * Update settings for a specific section
     */
    public static function updateSection(string $section, array $content): void
    {
        self::updateOrCreate(
            ['section' => $section],
            ['content' => $content]
        );
    }
}
