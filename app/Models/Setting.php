<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Get a setting value by key.
     */
    public static function get($key, $default = null)
    {
        $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value.
     */
    public static function set($key, $value, $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("setting_{$key}");
        Cache::forget("settings_group_{$group}");

        return $setting;
    }

    /**
     * Get all settings by group.
     */
    public static function getByGroup($group)
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return static::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get payment settings (bKash, Nagad numbers).
     */
    public static function getPaymentSettings()
    {
        return [
            'bkash_number' => static::get('bkash_number', ''),
            'bkash_name' => static::get('bkash_name', ''),
            'bkash_type' => static::get('bkash_type', 'personal'),
            'bkash_enabled' => static::get('bkash_enabled', '1'),
            'nagad_number' => static::get('nagad_number', ''),
            'nagad_name' => static::get('nagad_name', ''),
            'nagad_type' => static::get('nagad_type', 'personal'),
            'nagad_enabled' => static::get('nagad_enabled', '1'),
            'rocket_number' => static::get('rocket_number', ''),
            'rocket_name' => static::get('rocket_name', ''),
            'rocket_enabled' => static::get('rocket_enabled', '0'),
        ];
    }

    /**
     * Save payment settings.
     */
    public static function savePaymentSettings($data)
    {
        $keys = [
            'bkash_number', 'bkash_name', 'bkash_type', 'bkash_enabled',
            'nagad_number', 'nagad_name', 'nagad_type', 'nagad_enabled',
            'rocket_number', 'rocket_name', 'rocket_enabled'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                static::set($key, $data[$key], 'payment');
            }
        }

        // Clear cache
        Cache::forget('settings_group_payment');
    }

    /**
     * Get a setting value directly from the database (bypassing cache).
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value directly in the database (bypassing cache).
     */
    public static function setValue($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
