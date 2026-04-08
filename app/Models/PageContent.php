<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'title',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get content by page type
     */
    public static function getByType($type)
    {
        return self::where('page_type', $type)
            ->where('is_active', true)
            ->first();
    }
}
