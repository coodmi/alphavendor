<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Trendy clothing, accessories, and footwear for all occasions',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets, smartphones, laptops, and electronic devices',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'description' => 'Furniture, decor, and essentials for your home',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'description' => 'Cosmetics, skincare, wellness, and health products',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment, outdoor gear, and fitness accessories',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, magazines, music, and entertainment',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Toys, games, and activities for kids and adults',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car accessories, parts, and automotive essentials',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Jewelry & Watches',
                'slug' => 'jewelry-watches',
                'description' => 'Elegant jewelry, luxury watches, and accessories',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'description' => 'Everything for your beloved pets',
                'image' => 'categories/placeholder.jpg',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        Category::insert($categories);
        $this->command->info('Categories seeded successfully!');
    }
}
