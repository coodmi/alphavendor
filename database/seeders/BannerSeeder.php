<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Summer Sale - Up to 50% Off',
                'image' => 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?w=1200&h=400&fit=crop',
                'link' => '/shop?sale=summer',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'New Arrivals Collection',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&h=400&fit=crop',
                'link' => '/shop?collection=new',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Free Shipping on Orders Over $100',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200&h=400&fit=crop',
                'link' => '/shop',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Electronics Festival',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1200&h=400&fit=crop',
                'link' => '/shop?category=electronics',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
