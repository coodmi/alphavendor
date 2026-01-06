<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromoBanner;

class PromoBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promoBanners = [
            [
                'title' => 'Mega Sale Event',
                'subtitle' => 'Limited Time Offer',
                'description' => 'Get up to 50% off on thousands of products. Shop now and save big on electronics, fashion, home goods and more!',
                'button_text' => 'Shop Now',
                'button_link' => '/shop',
                'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&h=600&fit=crop',
                'background_color' => '#FFA500',
                'text_color' => '#FFFFFF',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'Fresh & Trending',
                'description' => 'Discover the latest products from top brands. Be the first to get your hands on the newest collection.',
                'button_text' => 'Explore Collection',
                'button_link' => '/shop?collection=new',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&h=600&fit=crop',
                'background_color' => '#F59E0B',
                'text_color' => '#FFFFFF',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Free Shipping',
                'subtitle' => 'On Orders Over $100',
                'description' => 'Enjoy free shipping on all orders above $100. Fast delivery guaranteed to your doorstep.',
                'button_text' => 'Learn More',
                'button_link' => '/shipping',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&h=600&fit=crop',
                'background_color' => '#EAB308',
                'text_color' => '#1F2937',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($promoBanners as $banner) {
            PromoBanner::create($banner);
        }
    }
}
