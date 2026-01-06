<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Str;

class RetailerProductsSeeder extends Seeder
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

        $productTemplates = [
            [
                'name' => 'Premium Cotton T-Shirt',
                'description' => 'Comfortable 100% cotton t-shirt',
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=800&fit=crop',
                'price' => 29.99,
                'old_price' => 39.99,
                'stock' => 50,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'New',
            ],
            [
                'name' => 'Wireless Earbuds Pro',
                'description' => 'High-quality wireless earbuds with noise cancellation',
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&h=800&fit=crop',
                'price' => 89.99,
                'old_price' => 129.99,
                'stock' => 30,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Sale',
            ],
            [
                'name' => 'Modern Desk Chair',
                'description' => 'Ergonomic office chair with lumbar support',
                'image' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267?w=800&h=800&fit=crop',
                'price' => 199.99,
                'old_price' => null,
                'stock' => 15,
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'name' => 'Yoga Exercise Mat',
                'description' => 'Non-slip yoga mat with carrying strap',
                'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&h=800&fit=crop',
                'price' => 34.99,
                'old_price' => 44.99,
                'stock' => 40,
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'name' => 'Luxury Face Cream',
                'description' => 'Anti-aging face cream with natural ingredients',
                'image' => 'https://images.unsplash.com/photo-1570194065650-d99fb4bedf0a?w=800&h=800&fit=crop',
                'price' => 59.99,
                'old_price' => 79.99,
                'stock' => 25,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Hot',
            ],
            [
                'name' => 'Designer Backpack',
                'description' => 'Stylish backpack with laptop compartment',
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&h=800&fit=crop',
                'price' => 79.99,
                'old_price' => null,
                'stock' => 35,
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'name' => 'Smart LED Bulb',
                'description' => 'WiFi-enabled color-changing LED bulb',
                'image' => 'https://images.unsplash.com/photo-1550985616-10810253b84d?w=800&h=800&fit=crop',
                'price' => 24.99,
                'old_price' => 34.99,
                'stock' => 60,
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight running shoes with cushioning',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=800&fit=crop',
                'price' => 119.99,
                'old_price' => 149.99,
                'stock' => 45,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Sale',
            ],
        ];

        // Create products for each retailer
        foreach ($retailers as $retailer) {
            // Get retailer's categories and brands
            $categories = Category::where('vendor_id', $retailer->id)->get();
            $brands = Brand::where('vendor_id', $retailer->id)->get();

            if ($categories->isEmpty()) {
                $this->command->warn("No categories found for retailer: {$retailer->name}. Skipping...");
                continue;
            }

            // Each retailer gets 5-8 products
            $productsCount = rand(5, 8);

            for ($i = 0; $i < $productsCount; $i++) {
                $template = $productTemplates[$i % count($productTemplates)];
                $category = $categories->random();
                $brand = $brands->isNotEmpty() ? $brands->random() : null;

                Product::create([
                    'vendor_id' => $retailer->id,
                    'category_id' => $category->id,
                    'brand_id' => $brand ? $brand->id : null,
                    'name' => $template['name'],
                    'slug' => Str::slug($template['name']) . '-' . $retailer->id . '-' . uniqid(),
                    'description' => $template['description'],
                    'image' => $template['image'],
                    'price' => $template['price'],
                    'old_price' => $template['old_price'],
                    'stock' => $template['stock'],
                    'sku' => 'RET-' . strtoupper(substr($retailer->name, 0, 3)) . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'status' => $template['status'],
                    'rating' => rand(35, 50) / 10,
                    'reviews_count' => rand(5, 100),
                    'is_featured' => $template['is_featured'],
                    'badge' => $template['badge'],
                ]);
            }

            $this->command->info("✓ Created {$productsCount} products for retailer: {$retailer->name}");
        }

        $this->command->info('Retailer products seeded successfully!');
    }
}
