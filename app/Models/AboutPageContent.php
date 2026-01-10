<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get content by key
     */
    public static function getContent($key, $default = null)
    {
        $content = self::where('key', $key)->first();
        return $content ? $content->value : $default;
    }

    /**
     * Get all content as key-value pairs
     */
    public static function getAllContent()
    {
        return self::all()->pluck('value', 'key')->toArray();
    }
}
