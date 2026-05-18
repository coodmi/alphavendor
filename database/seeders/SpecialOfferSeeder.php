<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpecialOffer;

class SpecialOfferSeeder extends Seeder
{
    public function run()
    {
        $offers = [
            [
                'name' => 'Summer Sale - Up to 50% Off',
                'slug' => 'summer-sale-up-to-50-off',
                'description' => 'Get amazing discounts up to 50% off on selected items this summer season',
                'badge_text' => '50% OFF',
                'badge_color' => '#DC2626',
                'image' => 'https://images.unsplash.com/photo-1607083206968-13611e3d76db?w=800&h=400&fit=crop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'New Arrivals Collection',
                'slug' => 'new-arrivals-collection',
                'description' => 'Check out our latest collection of trending products and fashion items',
                'badge_text' => 'NEW',
                'badge_color' => '#7C3AED',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&h=400&fit=crop',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Free Shipping on Orders Over $100',
                'slug' => 'free-shipping-on-orders-over-100',
                'description' => 'Enjoy free shipping on all orders above $100. Shop now and save on delivery',
                'badge_text' => 'FREE SHIP',
                'badge_color' => '#059669',
                'image' => 'https://images.unsplash.com/photo-1558769132-cb1aea1f1d58?w=800&h=400&fit=crop',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Electronics Festival',
                'slug' => 'electronics-festival',
                'description' => 'Massive discounts on electronics, gadgets, and tech accessories',
                'badge_text' => 'TECH SALE',
                'badge_color' => '#2563EB',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=400&fit=crop',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        SpecialOffer::updateOrCreate(
            ['slug' => 'demo-offer'],
            [
                'name' => 'Demo Offer — 20% Off',
                'description' => 'Demo special offer for testing product assignment.',
                'badge_text' => '20% OFF',
                'badge_color' => '#4F46E5',
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        foreach ($offers as $offer) {
            SpecialOffer::updateOrCreate(['slug' => $offer['slug']], $offer);
        }
    }
}
