<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class RetailerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all retailers
        $retailers = User::where('role', 'retailer')->get();

        if ($retailers->isEmpty()) {
            $this->command->warn('No retailers found! Please run VendorSeeder first.');
            return;
        }

        $categoryTemplates = [
            [
                'name' => 'Clothing',
                'description' => 'Fashion apparel and clothing items',
                'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gadgets',
                'description' => 'Latest electronic gadgets and accessories',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Furniture',
                'description' => 'Home and office furniture',
                'image' => 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Fitness',
                'description' => 'Exercise and fitness equipment',
                'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Cosmetics',
                'description' => 'Beauty and makeup products',
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Accessories',
                'description' => 'Fashion accessories and jewelry',
                'image' => 'https://images.unsplash.com/photo-1492707892479-7bc8d5a4ee93?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        // Create categories for each retailer
        foreach ($retailers as $index => $retailer) {
            // Each retailer gets 3-4 categories
            $categoriesCount = rand(3, 4);
            
            for ($i = 0; $i < $categoriesCount; $i++) {
                $template = $categoryTemplates[($index * 2 + $i) % count($categoryTemplates)];
                
                // Create unique slug by appending retailer ID
                $uniqueName = $template['name'] . ' ' . $retailer->id;
                
                Category::create([
                    'vendor_id' => $retailer->id,
                    'name' => $uniqueName,
                    'description' => $template['description'] . ' curated by ' . $retailer->name,
                    'image' => $template['image'],
                    'is_active' => $template['is_active'],
                    'sort_order' => $template['sort_order'],
                ]);
            }

            $this->command->info("✓ Created categories for retailer: {$retailer->name}");
        }

        $this->command->info('Retailer categories seeded successfully!');
    }
}
