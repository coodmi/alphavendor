<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
                'description' => 'Trendy clothing, accessories, and footwear for all occasions',
                'unsplash_query' => 'fashion-clothing',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Electronics',
                'description' => 'Latest gadgets, smartphones, laptops, and electronic devices',
                'unsplash_query' => 'electronics-gadgets',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Furniture, decor, and essentials for your home',
                'unsplash_query' => 'home-interior',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Beauty & Health',
                'description' => 'Cosmetics, skincare, wellness, and health products',
                'unsplash_query' => 'beauty-cosmetics',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment, outdoor gear, and fitness accessories',
                'unsplash_query' => 'sports-equipment',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Books & Media',
                'description' => 'Books, magazines, music, and entertainment',
                'unsplash_query' => 'books-reading',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Toys, games, and activities for kids and adults',
                'unsplash_query' => 'toys-games',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Automotive',
                'description' => 'Car accessories, parts, and automotive essentials',
                'unsplash_query' => 'car-automotive',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Jewelry & Watches',
                'description' => 'Elegant jewelry, luxury watches, and accessories',
                'unsplash_query' => 'jewelry-luxury',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Pet Supplies',
                'description' => 'Everything for your beloved pets',
                'unsplash_query' => 'pet-supplies',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $unsplashQuery = $categoryData['unsplash_query'];
            unset($categoryData['unsplash_query']);

            // Download image from Unsplash
            $imageUrl = "https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&h=600&fit=crop&q=80";
            
            // Alternative: Try picsum.photos as backup
            $backupUrl = "https://picsum.photos/800/600";
            
            try {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 15,
                        'follow_location' => 1,
                        'max_redirects' => 5
                    ]
                ]);
                
                $imageContent = @file_get_contents($imageUrl, false, $context);
                
                // Try backup if first fails
                if ($imageContent === false) {
                    sleep(1);
                    $imageContent = @file_get_contents($backupUrl, false, $context);
                }
                
                if ($imageContent !== false && strlen($imageContent) > 1000) {
                    $filename = 'categories/' . time() . '_' . uniqid() . '.jpg';
                    Storage::disk('public')->put($filename, $imageContent);
                    $categoryData['image'] = $filename;
                    $this->command->info("✓ Downloaded image for: {$categoryData['name']}");
                } else {
                    $categoryData['image'] = null;
                    $this->command->warn("✗ No image for: {$categoryData['name']}");
                }
            } catch (\Exception $e) {
                $categoryData['image'] = null;
                $this->command->warn("✗ Failed: {$categoryData['name']} - {$e->getMessage()}");
            }

            Category::create($categoryData);

            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 seconds
        }

        $this->command->info('Categories seeded successfully with Unsplash images!');
    }
}
