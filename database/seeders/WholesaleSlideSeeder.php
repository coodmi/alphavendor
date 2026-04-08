<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WholesaleSlide;

class WholesaleSlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title' => 'Wholesale Marketplace',
                'description' => 'Connect with verified suppliers and manufacturers. Buy in bulk with competitive wholesale prices for your business.',
                'image' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?w=1600&h=600&fit=crop',
                'cta_text' => 'Start Ordering',
                'cta_link' => '/shop',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'B2B Solutions for Your Business',
                'description' => 'Access 50K+ bulk products from 2,500+ verified manufacturers worldwide',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600&h=600&fit=crop',
                'cta_text' => 'Register as Buyer',
                'cta_link' => '/register',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Competitive Wholesale Prices',
                'description' => 'Get the best deals on bulk orders with 15K+ trusted business partners',
                'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=1600&h=600&fit=crop',
                'cta_text' => 'Browse Products',
                'cta_link' => '/shop',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            WholesaleSlide::create($slide);
        }
    }
}
