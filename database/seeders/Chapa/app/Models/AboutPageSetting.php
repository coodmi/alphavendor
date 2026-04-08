<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_stats',
        'story_badge',
        'story_title',
        'story_content',
        'story_image',
        'mission_text',
        'vision_text',
        'why_choose_us',
        'cta_title',
        'cta_description',
        'is_active'
    ];

    protected $casts = [
        'hero_stats' => 'array',
        'why_choose_us' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active about page settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get formatted data for frontend
     */
    public function getFormattedData()
    {
        return [
            'hero' => [
                'badge' => $this->hero_badge,
                'title' => $this->hero_title,
                'subtitle' => $this->hero_subtitle,
                'description' => $this->hero_description,
                'stats' => $this->hero_stats ?? [
                    ['value' => '৮+', 'label' => 'বছরের অভিজ্ঞতা', 'sublabel' => '২০১৫ সাল থেকে'],
                    ['value' => '৫০K+', 'label' => 'সন্তুষ্ট গ্রাহক', 'sublabel' => 'বিশ্বস্ত পার্টনারশিপ'],
                    ['value' => '১০০+', 'label' => 'কর্পোরেট ক্লায়েন্ট', 'sublabel' => 'প্রিমিয়াম সেবা'],
                    ['value' => '৯৯.৮%', 'label' => 'সন্তুষ্টির হার', 'sublabel' => 'গুণগত নিশ্চয়তা'],
                ],
            ],
            'story' => [
                'badge' => $this->story_badge,
                'title' => $this->story_title,
                'content' => $this->story_content,
                'image' => $this->story_image ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop',
            ],
            'mission' => $this->mission_text ?? 'বাংলাদেশের প্রতিটি ব্যবসায়ী ও ব্যক্তির কাছে সাশ্রয়ী মূল্যে বিশ্বমানের প্রিন্টিং সেবা পৌঁছে দেওয়া।',
            'vision' => $this->vision_text ?? '২০৩০ সালের মধ্যে দক্ষিণ এশিয়ার শীর্ষস্থানীয় ডিজিটাল প্রিন্টিং প্ল্যাটফর্ম হিসেবে প্রতিষ্ঠিত হওয়া।',
            'why_choose_us' => $this->why_choose_us ?? [
                ['title' => 'দ্রুততম ডেলিভারি', 'description' => '২৪ ঘন্টার মধ্যে জরুরি অর্ডার এবং নিয়মিত অর্ডারে ৩-৫ দিনের গ্যারান্টি'],
                ['title' => '১০০% কোয়ালিটি গ্যারান্টি', 'description' => 'সন্তুষ্ট না হলে সম্পূর্ণ রিফান্ড অথবা ফ্রি রিপ্রিন্টের নিশ্চয়তা'],
                ['title' => 'সাশ্রয়ী মূল্য', 'description' => 'বাজারের সবচেয়ে প্রতিযোগিতামূলক দামে প্রিমিয়াম কোয়ালিটির সেবা'],
            ],
            'cta' => [
                'title' => $this->cta_title,
                'description' => $this->cta_description ?? 'বিশ্বমানের প্রিন্টিং সেবা পেতে আজই আমাদের সাথে যোগাযোগ করুন',
            ],
        ];
    }
}
