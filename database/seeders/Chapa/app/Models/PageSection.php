<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'section_key',
        'title',
        'content',
        'is_active',
        'order',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to filter by page.
     */
    public function scopeForPage($query, $page)
    {
        return $query->where('page', $page)->where('is_active', true)->orderBy('order');
    }

    /**
     * Get sections for a specific page as a keyed collection.
     */
    public static function getPageContent($page)
    {
        return self::forPage($page)->get()->keyBy('section_key');
    }

    /**
     * Get a specific section's content.
     */
    public static function getSectionContent($page, $sectionKey, $default = [])
    {
        $section = self::where('page', $page)
            ->where('section_key', $sectionKey)
            ->where('is_active', true)
            ->first();

        return $section ? $section->content : $default;
    }

    /**
     * Update or create a section.
     */
    public static function setSection($page, $sectionKey, $content, $title = null)
    {
        return self::updateOrCreate(
            ['page' => $page, 'section_key' => $sectionKey],
            [
                'content' => $content,
                'title' => $title,
                'is_active' => true,
            ]
        );
    }

    /**
     * Get default content for a slug.
     */
    public static function getDefaults($slug)
    {
        $defaults = [
            'books' => [
                'title' => 'বই',
                'description' => 'পেপারব্যাক থেকে হার্ডব্যাক - সব ধরনের বই প্রিন্টিং সেবা',
                'headline' => 'পেশাদার বই প্রিন্টিং সেবা',
                'short_description' => 'লেখক থেকে পাঠক - আপনার সৃজনশীলতাকে বাস্তবে রূপান্তরিত করুন। উচ্চ মানের কাগজ, প্রিমিয়াম বাইন্ডিং এবং দ্রুত ডেলিভারি।',
                'grid_title' => 'বইয়ের ধরন নির্বাচন করুন',
                'grid_subtitle' => 'আপনার প্রয়োজন অনুযায়ী সেরা অপশন',
                'hero_slides' => [
                    [
                        'title' => 'বই প্রিন্টিং',
                        'description' => 'আপনার গল্প বলুন আমাদের পেশাদার বই প্রিন্টিং সেবার মাধ্যমে। পেপারব্যাক, হার্ডব্যাক এবং আরও অনেক অপশন।',
                        'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'পেপারব্যাক বই', 'url' => '/books/paperback', 'img' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=300&h=300&fit=crop', 'price' => '৩০০', 'badge' => 'জনপ্রিয়'],
                    ['title' => 'হার্ডব্যাক বই', 'url' => '/books/hardback', 'img' => 'https://images.unsplash.com/photo-1519682577862-22b62b24e491?w=300&h=300&fit=crop', 'price' => '৮০০'],
                    ['title' => 'স্পাইরাল বাইন্ডিং', 'url' => '/books/spiral', 'img' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=300&h=300&fit=crop', 'price' => '১৫০'],
                ],
                'offer' => [
                    'title' => '📚 বই প্রিন্টিং এ মেগা অফার 📚',
                    'text' => '৫০+ বই অর্ডারে পাচ্ছেন ২৫% ছাড়!',
                    'details' => 'বাল্ক অর্ডারে বিশেষ ছাড়। লেখক এবং প্রকাশকদের জন্য বিশেষ প্যাকেজ।',
                    'coupon_code' => 'FIRST25',
                ],
            ],
            'magazines' => [
                'title' => 'ম্যাগাজিন',
                'description' => 'পেশাদার ম্যাগাজিন প্রিন্টিং সেবা',
                'headline' => 'উচ্চ মানের ম্যাগাজিন প্রিন্টিং',
                'short_description' => 'কর্পোরেট প্রকাশনা থেকে লাইফস্টাইল ম্যাগাজিন - সব ধরনের ম্যাগাজিন প্রিন্টিং সেবা পাবেন এক জায়গায়।',
                'grid_title' => 'ম্যাগাজিনের ধরন নির্বাচন করুন',
                'grid_subtitle' => 'আপনার প্রকাশনার জন্য সেরা অপশন',
                'hero_slides' => [
                    [
                        'title' => 'ম্যাগাজিন প্রিন্টিং',
                        'description' => 'আপনার ব্র্যান্ডের গল্প তুলে ধরুন আমাদের প্রিমিয়াম ম্যাগাজিন প্রিন্টিং এর মাধ্যমে।',
                        'image' => 'https://images.unsplash.com/photo-1557821552-17105176677c?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'মাসিক ম্যাগাজিন', 'url' => '/magazines/monthly', 'img' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=300&h=300&fit=crop', 'price' => '১৫০', 'badge' => 'জনপ্রিয়'],
                    ['title' => 'কর্পোরেট ম্যাগাজিন', 'url' => '/magazines/corporate', 'img' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=300&h=300&fit=crop', 'price' => '২০০'],
                    ['title' => 'ক্যাটালগ ম্যাগাজিন', 'url' => '/magazines/catalog', 'img' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=300&h=300&fit=crop', 'price' => '১৮০'],
                ],
                'offer' => [
                    'title' => '🎉 ম্যাগাজিন প্রিন্টিং এ বিশেষ ছাড় 🎉',
                    'text' => '১০০+ কপি অর্ডারে পাচ্ছেন ৩০% ছাড়!',
                    'details' => 'বাল্ক অর্ডারে বিশেষ ছাড়।',
                ],
            ],
            'catalogs' => [
                'title' => 'ক্যাটালগ',
                'description' => 'পণ্য ও সেবা ক্যাটালগ প্রিন্টিং',
                'headline' => 'পেশাদার ক্যাটালগ প্রিন্টিং সেবা',
                'short_description' => 'আপনার ব্যবসার পণ্য তালিকা আকর্ষণীয়ভাবে উপস্থাপন করুন। উচ্চ মানের ক্যাটালগ প্রিন্টিং সেবা।',
                'grid_title' => 'ক্যাটালগের ধরন নির্বাচন করুন',
                'grid_subtitle' => 'আপনার ব্যবসার জন্য উপযুক্ত অপশন',
                'hero_slides' => [
                    [
                        'title' => 'ক্যাটালগ প্রিন্টিং',
                        'description' => 'পণ্যের বিস্তারিত বিবরণ সহ পেশাদার ক্যাটালগ। সব ধরনের ব্যবসার জন্য উপযুক্ত।',
                        'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'স্ট্যান্ডার্ড ক্যাটালগ', 'url' => '/catalogs/standard', 'img' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300&h=300&fit=crop', 'price' => '৩৫০'],
                    ['title' => 'প্রিমিয়াম ক্যাটালগ', 'url' => '/catalogs/premium', 'img' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=300&h=300&fit=crop', 'price' => '৬০০'],
                    ['title' => 'মিনি ক্যাটালগ', 'url' => '/catalogs/mini', 'img' => 'https://images.unsplash.com/photo-1513001900722-370f803f498d?w=300&h=300&fit=crop', 'price' => '২৫০'],
                ],
                'offer' => [
                    'title' => '📖 ক্যাটালগ প্রিন্টিং অফার 📖',
                    'text' => '৫০+ ক্যাটালগে ২০% ছাড়!',
                    'details' => 'বাল্ক অর্ডারে বিশেষ মূল্য ছাড়।',
                ],
            ],
            'brochures' => [
                'title' => 'মার্কেটিং ম্যাটেরিয়াল',
                'description' => 'ব্রোশিওর ও মার্কেটিং উপকরণ প্রিন্টিং',
                'headline' => 'পেশাদার মার্কেটিং ম্যাটেরিয়াল',
                'short_description' => 'আপনার ব্যবসা প্রচারের জন্য আকর্ষণীয় ব্রোশিওর, ফ্লায়ার এবং মার্কেটিং উপকরণ।',
                'grid_title' => 'মার্কেটিং ম্যাটেরিয়াল নির্বাচন করুন',
                'grid_subtitle' => 'আপনার প্রচারণার জন্য সেরা অপশন',
                'hero_slides' => [
                    [
                        'title' => 'মার্কেটিং ম্যাটেরিয়াল',
                        'description' => 'ব্রোশিওর, ফ্লায়ার, লিফলেট - সব ধরনের মার্কেটিং উপকরণ পাবেন এক জায়গায়।',
                        'image' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'ব্রোশিওর', 'url' => '/brochures/standard', 'img' => 'https://images.unsplash.com/photo-1586880244406-556ebe35f282?w=300&h=300&fit=crop', 'price' => '১৫০'],
                    ['title' => 'ফ্লায়ার', 'url' => '/brochures/flyers', 'img' => 'https://images.unsplash.com/photo-1560264280-88b68371db39?w=300&h=300&fit=crop', 'price' => '১০০'],
                    ['title' => 'লিফলেট', 'url' => '/brochures/leaflets', 'img' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=300&h=300&fit=crop', 'price' => '১২০'],
                ],
                'offer' => [
                    'title' => '📢 মার্কেটিং প্রিন্টিং অফার 📢',
                    'text' => '১০০০+ পিস অর্ডারে ২৫% ছাড়!',
                    'details' => 'বাল্ক মার্কেটিং ম্যাটেরিয়ালে বিশেষ ছাড়।',
                ],
            ],
            'business-cards' => [
                'title' => 'বিজনেস কার্ড',
                'description' => 'পেশাদার বিজনেস কার্ড প্রিন্টিং',
                'headline' => 'প্রিমিয়াম বিজনেস কার্ড প্রিন্টিং',
                'short_description' => 'আপনার পেশাদার পরিচয় তুলে ধরুন উচ্চ মানের বিজনেস কার্ডের মাধ্যমে।',
                'grid_title' => 'বিজনেস কার্ড নির্বাচন করুন',
                'grid_subtitle' => 'বিভিন্ন স্টাইল ও ফিনিশ',
                'hero_slides' => [
                    [
                        'title' => 'বিজনেস কার্ড',
                        'description' => 'ক্লাসিক থেকে প্রিমিয়াম - সব ধরনের বিজনেস কার্ড প্রিন্টিং সেবা।',
                        'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'ক্লাসিক কার্ড', 'url' => '/business-cards/classic', 'img' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&h=300&fit=crop', 'price' => '৫০০', 'badge' => 'জনপ্রিয়'],
                    ['title' => 'প্রিমিয়াম কার্ড', 'url' => '/business-cards/premium', 'img' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&h=300&fit=crop', 'price' => '৮০০'],
                    ['title' => 'ম্যাট ফিনিশ', 'url' => '/business-cards/matte', 'img' => 'https://images.unsplash.com/photo-1565372195458-9de0b320ef04?w=300&h=300&fit=crop', 'price' => '৬৫০'],
                    ['title' => 'গ্লসি ফিনিশ', 'url' => '/business-cards/glossy', 'img' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=300&h=300&fit=crop', 'price' => '৬৫০'],
                ],
                'offer' => [
                    'title' => '💼 বিজনেস কার্ড অফার 💼',
                    'text' => '৫০০+ কার্ড অর্ডারে ২০% ছাড়!',
                    'details' => 'প্রথম অর্ডারে অতিরিক্ত ৫% ছাড়।',
                ],
            ],
            'postcards-invitations' => [
                'title' => 'ইনভিটেশন ও স্টেশনারি',
                'description' => 'বিয়ের কার্ড, ইনভিটেশন ও স্টেশনারি প্রিন্টিং',
                'headline' => 'কাস্টম ইনভিটেশন ও স্টেশনারি',
                'short_description' => 'বিশেষ অনুষ্ঠানের জন্য সুন্দর ইনভিটেশন কার্ড এবং দৈনন্দিন ব্যবহারের স্টেশনারি।',
                'grid_title' => 'ইনভিটেশন ও স্টেশনারি নির্বাচন করুন',
                'grid_subtitle' => 'আপনার বিশেষ মুহূর্তের জন্য',
                'hero_slides' => [
                    [
                        'title' => 'ইনভিটেশন ও স্টেশনারি',
                        'description' => 'বিয়ের কার্ড, জন্মদিনের আমন্ত্রণ এবং সব ধরনের স্টেশনারি প্রিন্টিং।',
                        'image' => 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'বিয়ের কার্ড', 'url' => '/postcards-invitations/wedding', 'img' => 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=300&h=300&fit=crop', 'price' => '১০০০'],
                    ['title' => 'পোস্টকার্ড', 'url' => '/postcards-invitations/standard', 'img' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?w=300&h=300&fit=crop', 'price' => '৩০০'],
                    ['title' => 'থ্যাংক ইউ কার্ড', 'url' => '/postcards-invitations/thank-you', 'img' => 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?w=300&h=300&fit=crop', 'price' => '২৫০'],
                ],
                'offer' => [
                    'title' => '💌 ইনভিটেশন অফার 💌',
                    'text' => '১০০+ কার্ডে ১৫% ছাড়!',
                    'details' => 'কাস্টম ডিজাইন সেবা সম্পূর্ণ বিনামূল্যে।',
                ],
            ],
            'banners' => [
                'title' => 'ব্যানার',
                'description' => 'সব ধরনের ব্যানার ও সাইনেজ প্রিন্টিং',
                'headline' => 'উচ্চ মানের ব্যানার প্রিন্টিং',
                'short_description' => 'ইনডোর ও আউটডোর ব্যানার, ফ্লেক্স, ভিনাইল - সব ধরনের ব্যানার প্রিন্টিং সেবা।',
                'grid_title' => 'ব্যানার নির্বাচন করুন',
                'grid_subtitle' => 'আপনার প্রয়োজন অনুযায়ী সাইজ ও উপকরণ',
                'hero_slides' => [
                    [
                        'title' => 'ব্যানার প্রিন্টিং',
                        'description' => 'ইভেন্ট, দোকান, অফিস - যেকোনো জায়গার জন্য উচ্চ মানের ব্যানার।',
                        'image' => 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'ভিনাইল ব্যানার', 'url' => '/banners/vinyl', 'img' => 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?w=300&h=300&fit=crop', 'price' => '১২০'],
                    ['title' => 'ফ্লেক্স ব্যানার', 'url' => '/banners/flex', 'img' => 'https://images.unsplash.com/photo-1551731409-43eb3e517a1a?w=300&h=300&fit=crop', 'price' => '১০০'],
                    ['title' => 'রোল আপ ব্যানার', 'url' => '/banners/roll-up', 'img' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=300&h=300&fit=crop', 'price' => '২৫০০'],
                ],
                'offer' => [
                    'title' => '🎯 ব্যানার প্রিন্টিং অফার 🎯',
                    'text' => 'বড় সাইজ ব্যানারে ১৫% ছাড়!',
                    'details' => 'ফ্রি ডিজাইন সাপোর্ট এবং দ্রুত ডেলিভারি।',
                ],
            ],
            'promotional-items' => [
                'title' => 'প্রমোশনাল আইটেম',
                'description' => 'ব্র্যান্ডেড প্রমোশনাল প্রোডাক্ট প্রিন্টিং',
                'headline' => 'কাস্টম প্রমোশনাল আইটেম',
                'short_description' => 'টি-শার্ট, মগ, পেন, কী-চেইন - সব ধরনের প্রমোশনাল আইটেম কাস্টম প্রিন্টিং।',
                'grid_title' => 'প্রমোশনাল আইটেম নির্বাচন করুন',
                'grid_subtitle' => 'আপনার ব্র্যান্ড প্রচারের জন্য',
                'hero_slides' => [
                    [
                        'title' => 'প্রমোশনাল আইটেম',
                        'description' => 'ব্র্যান্ডেড মার্চেন্ডাইজ এবং প্রমোশনাল পণ্য কাস্টম প্রিন্টিং সেবা।',
                        'image' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=800&h=600&fit=crop',
                    ],
                ],
                'products' => [
                    ['title' => 'কাস্টম টি-শার্ট', 'url' => '/promotional-items/tshirt', 'img' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&h=300&fit=crop', 'price' => '৩৫০'],
                    ['title' => 'প্রিন্টেড মগ', 'url' => '/promotional-items/mug', 'img' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=300&h=300&fit=crop', 'price' => '২৫০'],
                    ['title' => 'কাস্টম পেন', 'url' => '/promotional-items/pen', 'img' => 'https://images.unsplash.com/photo-1586500036706-41963de24d1b?w=300&h=300&fit=crop', 'price' => '৫০'],
                    ['title' => 'কী-চেইন', 'url' => '/promotional-items/keychain', 'img' => 'https://images.unsplash.com/photo-1591561954555-607968c989ab?w=300&h=300&fit=crop', 'price' => '৮০'],
                ],
                'offer' => [
                    'title' => '🎁 প্রমোশনাল আইটেম অফার 🎁',
                    'text' => '১০০+ পিস অর্ডারে ২৫% ছাড়!',
                    'details' => 'ব্র্যান্ডিং ও ডিজাইন সাপোর্ট ফ্রি।',
                ],
            ],
        ];

        return $defaults[$slug] ?? [
            'title' => ucfirst($slug),
            'description' => 'Professional printing services',
            'headline' => 'Professional Printing Services',
            'short_description' => 'High-quality printing at affordable prices.',
            'grid_title' => 'Select a product',
            'grid_subtitle' => 'Choose the best option for your needs',
            'hero_slides' => [],
            'products' => [],
            'offer' => [
                'title' => '🎉 Special Offer 🎉',
                'text' => 'Get 25% off on bulk orders!',
                'details' => 'Limited time offer.',
            ],
        ];
    }
}
