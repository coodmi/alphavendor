<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPageContent;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = [
            // Hero Section
            [
                'key' => 'hero_title',
                'value' => 'About AlphaVendor',
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Your trusted partner in global commerce, connecting businesses worldwide',
            ],
            [
                'key' => 'hero_cta_primary_text',
                'value' => 'Start Shopping',
            ],
            [
                'key' => 'hero_cta_primary_link',
                'value' => '/shop',
            ],
            [
                'key' => 'hero_cta_secondary_text',
                'value' => 'Contact Us',
            ],
            [
                'key' => 'hero_cta_secondary_link',
                'value' => '#contact',
            ],

            // Our Story Section
            [
                'key' => 'story_title',
                'value' => 'Our Story',
            ],
            [
                'key' => 'story_paragraph_1',
                'value' => 'Founded with a vision to revolutionize B2B commerce, AlphaVendor has grown into a leading multi-vendor marketplace serving thousands of businesses worldwide.',
            ],
            [
                'key' => 'story_paragraph_2',
                'value' => 'We believe in creating seamless connections between retailers, wholesalers, and exporters, making global trade accessible and efficient for businesses of all sizes.',
            ],
            [
                'key' => 'story_paragraph_3',
                'value' => 'Our platform is built on trust, transparency, and innovation, empowering vendors to reach new markets and buyers to discover quality products from verified suppliers.',
            ],

            // Stats Section
            [
                'key' => 'stats_vendors',
                'value' => '10K+',
            ],
            [
                'key' => 'stats_products',
                'value' => '50K+',
            ],
            [
                'key' => 'stats_customers',
                'value' => '100K+',
            ],
            [
                'key' => 'stats_countries',
                'value' => '45+',
            ],

            // Core Values Section
            [
                'key' => 'values_title',
                'value' => 'Our Core Values',
            ],
            [
                'key' => 'values_subtitle',
                'value' => 'The principles that guide everything we do at AlphaVendor',
            ],

            // Mission & Vision Section
            [
                'key' => 'mission_title',
                'value' => 'Our Mission',
            ],
            [
                'key' => 'mission_content',
                'value' => 'To empower businesses of all sizes by providing a transparent, efficient, and innovative marketplace that simplifies global trade and fosters meaningful connections between buyers and sellers worldwide.',
            ],
            [
                'key' => 'vision_title',
                'value' => 'Our Vision',
            ],
            [
                'key' => 'vision_content',
                'value' => 'To become the world\'s most trusted and innovative multi-vendor marketplace, where every business finds opportunities to grow, scale, and succeed in the global market.',
            ],

            // Why Choose Us Section
            [
                'key' => 'why_choose_title',
                'value' => 'Why Choose AlphaVendor?',
            ],
            [
                'key' => 'why_choose_subtitle',
                'value' => 'Discover the advantages that set us apart',
            ],

            // Call to Action Section
            [
                'key' => 'cta_title',
                'value' => 'Ready to Start Your Journey?',
            ],
            [
                'key' => 'cta_subtitle',
                'value' => 'Join thousands of successful vendors and buyers on AlphaVendor today',
            ],
            [
                'key' => 'cta_primary_text',
                'value' => 'Register as Vendor',
            ],
            [
                'key' => 'cta_primary_link',
                'value' => '/register',
            ],
            [
                'key' => 'cta_secondary_text',
                'value' => 'Start Shopping',
            ],
            [
                'key' => 'cta_secondary_link',
                'value' => '/shop',
            ],
        ];

        foreach ($content as $item) {
            AboutPageContent::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value']]
            );
        }
    }
}
