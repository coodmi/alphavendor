<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPageContent;

class AboutPageContentSeeder extends Seeder
{
    public function run(): void
    {
        AboutPageContent::create([
            // Hero Section
            'hero_title' => 'About AlphaVendor',
            'hero_subtitle' => 'Your trusted partner in global commerce, connecting businesses worldwide',
            'hero_cta_primary_text' => 'Start Selling',
            'hero_cta_primary_link' => '/register',
            'hero_cta_secondary_text' => 'Learn More',
            'hero_cta_secondary_link' => '/contact',
            
            // Story Section
            'story_title' => 'Our Story',
            'story_paragraph_1' => 'Founded in 2020, AlphaVendor emerged from a simple vision: to create a seamless platform where businesses of all sizes could connect, trade, and grow together. What started as a small marketplace has evolved into a thriving ecosystem serving thousands of vendors and customers worldwide.',
            'story_paragraph_2' => 'We recognized the challenges faced by retailers, wholesalers, and importers in finding reliable partners and quality products. Our platform bridges these gaps, offering a comprehensive solution that combines cutting-edge technology with personalized service.',
            'story_paragraph_3' => 'Today, AlphaVendor stands as a testament to the power of innovation and collaboration. We continue to evolve, adding new features and expanding our reach to serve the global business community better.',
            
            // Mission & Vision
            'mission_title' => 'Our Mission',
            'mission_content' => 'To empower businesses worldwide by providing a trusted, efficient, and innovative marketplace that facilitates seamless trade connections, fosters growth, and creates lasting partnerships across borders.',
            'vision_title' => 'Our Vision',
            'vision_content' => 'To become the world\'s leading multi-vendor marketplace, recognized for excellence in service, innovation in technology, and commitment to helping businesses of all sizes achieve their full potential in the global market.',
            
            // Stats Section
            'stats_vendors' => '1,000+',
            'stats_products' => '50,000+',
            'stats_customers' => '100,000+',
            'stats_countries' => '50+',
            
            // CTA Section
            'cta_title' => 'Ready to Get Started?',
            'cta_subtitle' => 'Join thousands of successful businesses already trading on AlphaVendor',
            'cta_primary_text' => 'Become a Vendor',
            'cta_primary_link' => '/register',
            'cta_secondary_text' => 'Contact Sales',
            'cta_secondary_link' => '/contact',
        ]);
    }
}
