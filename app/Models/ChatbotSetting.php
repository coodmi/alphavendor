<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get setting by key
     */
    public static function getSetting($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return self::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Update or create setting
     */
    public static function updateSetting($key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}
