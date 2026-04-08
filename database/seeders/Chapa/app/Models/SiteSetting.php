<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear the cache for this setting
        Cache::forget("site_setting_{$key}");
    }

    /**
     * Get all settings as an associative array.
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('all_site_settings', 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('all_site_settings');
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("site_setting_{$setting->key}");
        }
    }
}
