<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_slider',
        'headline',
        'how_to_order',
        'best_sellers',
        'testimonials',
        'offer_banner',
        'trust_section',
        'features',
        'seo_settings',
        'is_active'
    ];

    protected $casts = [
        'hero_slider' => 'array',
        'headline' => 'array',
        'how_to_order' => 'array',
        'best_sellers' => 'array',
        'testimonials' => 'array',
        'offer_banner' => 'array',
        'trust_section' => 'array',
        'features' => 'array',
        'seo_settings' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active home page settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get formatted home page data for frontend
     */
    public function getFormattedData()
    {
        return [
            'hero_slider' => $this->hero_slider ?? [
                'slides' => [],
                'stats' => [
                    'percentage' => '93',
                    'label' => 'of our customers would buy again',
                    'reviews_count' => '256,839',
                ],
            ],
            'headline' => $this->headline ?? [
                'title' => 'আপনার স্বপ্নকে বাস্তবে রূপান্তরিত করুন',
                'description' => 'চাপাখানা হলো আপনার বিশ্বস্ত প্রিন্টিং সঙ্গী। উচ্চমানের প্রিন্টিং সেবা, দ্রুত ডেলিভারি এবং প্রতিযোগিতামূলক মূল্যে আমরা আপনার ব্যবসায়িক লক্ষ্য অর্জনে সহায়তা করি।',
            ],
            'how_to_order' => $this->how_to_order ?? [
                'title' => '০ টাকা বিনিয়োগে শুরু করুন',
                'steps' => [
                    ['number' => '১', 'title' => 'পণ্য নির্বাচন করুন', 'description' => '১০০০+ উচ্চমানের পণ্য থেকে আপনার পছন্দের পণ্য বেছে নিন'],
                    ['number' => '২', 'title' => 'ডিজাইন যুক্ত করুন', 'description' => 'সহজ এবং মজাদার উপায়ে আপনার পণ্যের ডিজাইন করুন!'],
                    ['number' => '৩', 'title' => 'বিক্রয় শুরু করুন', 'description' => 'আপনি লাভের মার্জিন নির্ধারণ করুন, উৎপাদন ও ডেলিভারি আমরা করবো'],
                ],
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'video_poster' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=1200&h=1200&fit=crop',
            ],
            'best_sellers' => $this->best_sellers ?? [
                'title' => 'জনপ্রিয় পণ্য',
                'products' => [],
            ],
            'testimonials' => $this->testimonials ?? [
                'title' => 'গ্রাহকদের মতামত',
                'subtitle' => 'সারা বাংলাদেশ জুড়ে হাজারো ব্যবসায়ীর বিশ্বস্ত সঙ্গী',
                'items' => [],
            ],
            'offer_banner' => $this->offer_banner ?? [
                'title' => 'বিশেষ অফার!',
                'subtitle' => 'প্রথম অর্ডারে পাচ্ছেন ২০% ছাড়',
                'description' => 'নতুন গ্রাহকরা সকল প্রিন্টিং সার্ভিসে বিশেষ ছাড় উপভোগ করতে পারবেন। সীমিত সময়ের অফার!',
                'cta_text' => 'এখনই অফার নিন',
                'cta_url' => '/shop',
            ],
            'trust_section' => $this->trust_section ?? [
                'title' => 'যারা আমাদের বিশ্বাস করেন',
                'subtitle' => 'শত শত প্রতিষ্ঠান তাদের প্রিন্টিং এর জন্য আমাদের উপর আস্থা রাখেন',
                'brands' => [],
            ],
            'features' => $this->features ?? [],
            'seo_settings' => $this->seo_settings ?? [
                'title' => 'Chapakhana - Every page tells your story',
                'description' => 'Quality print and marketing materials delivered with care',
                'keywords' => 'printing, books, magazines, catalogs, business cards, banners',
            ],
        ];
    }
}
