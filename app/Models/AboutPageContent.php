<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'logo',
        'hero_title',
        'hero_subtitle',
        'hero_cta_primary_text',
        'hero_cta_primary_link',
        'hero_cta_secondary_text',
        'hero_cta_secondary_link',
        'story_title',
        'story_paragraph_1',
        'story_paragraph_2',
        'story_paragraph_3',
        'mission_title',
        'mission_content',
        'vision_title',
        'vision_content',
        'stats_vendors',
        'stats_products',
        'stats_customers',
        'stats_countries',
        'cta_title',
        'cta_subtitle',
        'cta_primary_text',
        'cta_primary_link',
        'cta_secondary_text',
        'cta_secondary_link',
    ];

    /**
     * Get the about page content (singleton pattern)
     */
    public static function getContent()
    {
        $content = self::first();
        
        if (!$content) {
            $content = self::create([
                'hero_title' => 'About AlphaVendor',
                'hero_subtitle' => 'Your trusted partner in global commerce, connecting businesses worldwide',
            ]);
        }
        
        return $content;
    }
}
