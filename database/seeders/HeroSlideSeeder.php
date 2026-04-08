<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class HeroSlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title' => 'Welcome to AlphaVendor',
                'description' => 'Your trusted multi-vendor marketplace',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&h=600&fit=crop',
                'cta_text' => 'Shop Now',
                'cta_link' => '/shop',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Discover Amazing Deals',
                'description' => 'Connect with trusted vendors worldwide',
                'image' => 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=1600&h=600&fit=crop',
                'cta_text' => 'Explore Now',
                'cta_link' => '/shop',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Retail, Wholesale & Export',
                'description' => 'Everything you need in one place',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1600&h=600&fit=crop',
                'cta_text' => 'Get Started',
                'cta_link' => '/shop',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}
