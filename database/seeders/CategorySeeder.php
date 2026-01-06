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
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=200&fit=crop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets, smartphones, laptops, and electronic devices',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=200&h=200&fit=crop',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'description' => 'Furniture, decor, and essentials for your home',
                'image' => 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=200&h=200&fit=crop',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'description' => 'Cosmetics, skincare, wellness, and health products',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=200&h=200&fit=crop',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment, outdoor gear, and fitness accessories',
                'image' => 'https://images.unsplash.com/photo-1461896836934- voices-c6a51d6e?w=200&h=200&fit=crop',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, magazines, music, and entertainment',
                'image' => 'https://images.unsplash.com/photo-1524578271613-d550eacf6090?w=200&h=200&fit=crop',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Toys, games, and activities for kids and adults',
                'image' => 'https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=200&h=200&fit=crop',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car accessories, parts, and automotive essentials',
                'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=200&h=200&fit=crop',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Jewelry & Watches',
                'slug' => 'jewelry-watches',
                'description' => 'Elegant jewelry, luxury watches, and accessories',
                'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=200&h=200&fit=crop',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'description' => 'Everything for your beloved pets',
                'image' => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=200&h=200&fit=crop',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
        $this->command->info('Categories seeded successfully!');
    }
}
