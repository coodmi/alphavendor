<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'hero_title',
        'hero_subtitle',
        'address_title',
        'address_line1',
        'address_line2',
        'address_line3',
        'phone_title',
        'phone_primary',
        'phone_secondary',
        'phone_hours',
        'email_title',
        'email_info',
        'email_support',
        'email_sales',
        'hours_title',
        'hours_weekdays',
        'hours_weekdays_time',
        'hours_weekend',
        'form_title',
        'map_title',
        'map_embed_url',
        'social_title',
        'social_description',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_youtube',
        'faq_title',
        'faq_subtitle',
        'cta_title',
        'cta_subtitle',
        'cta_email_text',
        'cta_email_link',
        'cta_phone_text',
        'cta_phone_link',
    ];

    /**
     * Get the contact page content (singleton pattern)
     */
    public static function getContent()
    {
        $content = self::first();
        
        if (!$content) {
            $content = self::create([
                'hero_title' => 'Contact Us',
                'hero_subtitle' => 'We\'d love to hear from you. Get in touch with our team',
            ]);
        }
        
        return $content;
    }
}
