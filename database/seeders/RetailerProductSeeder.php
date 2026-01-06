<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class RetailerProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a retailer vendor
        $retailer = User::where('role', 'retailer')->first();

        if (!$retailer) {
            $this->command->info('No retailer found. Please run VendorSeeder first.');
            return;
        }

        // Get categories and brands
        $categories = Category::all();
        $brands = Brand::all();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->info('No categories or brands found. Please run CategorySeeder and BrandSeeder first.');
            return;
        }

        $products = [
            ['name' => 'Retail Premium Leather Wallet', 'price' => 49.99, 'old_price' => 79.99, 'rating' => 4.5, 'badge' => 'Hot Deal'],
            ['name' => 'Retail Wireless Bluetooth Headphones', 'price' => 89.99, 'old_price' => 129.99, 'rating' => 4.8, 'badge' => 'Bestseller'],
            ['name' => 'Retail Smart Watch Pro', 'price' => 199.99, 'old_price' => 299.99, 'rating' => 4.6, 'badge' => 'New'],
            ['name' => 'Retail Portable Power Bank 20000mAh', 'price' => 34.99, 'old_price' => 49.99, 'rating' => 4.4, 'badge' => null],
            ['name' => 'Retail USB-C Fast Charging Cable', 'price' => 12.99, 'old_price' => 19.99, 'rating' => 4.7, 'badge' => null],
            ['name' => 'Retail Phone Case Premium Quality', 'price' => 24.99, 'old_price' => 39.99, 'rating' => 4.3, 'badge' => null],
            ['name' => 'Retail Laptop Backpack Water Resistant', 'price' => 59.99, 'old_price' => 89.99, 'rating' => 4.5, 'badge' => 'Hot Deal'],
            ['name' => 'Retail Gaming Mouse RGB', 'price' => 44.99, 'old_price' => 69.99, 'rating' => 4.6, 'badge' => null],
            ['name' => 'Retail Mechanical Keyboard', 'price' => 79.99, 'old_price' => 119.99, 'rating' => 4.7, 'badge' => 'Bestseller'],
            ['name' => 'Retail Wireless Charging Pad', 'price' => 29.99, 'old_price' => 44.99, 'rating' => 4.4, 'badge' => null],
            ['name' => 'Retail Screen Protector Tempered Glass', 'price' => 14.99, 'old_price' => 24.99, 'rating' => 4.5, 'badge' => null],
            ['name' => 'Retail Bluetooth Speaker Portable', 'price' => 54.99, 'old_price' => 79.99, 'rating' => 4.6, 'badge' => 'Hot Deal'],
        ];

        foreach ($products as $productData) {
            Product::create([
                'vendor_id' => $retailer->id,
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']) . '-' . uniqid(),
                'description' => 'High quality ' . strtolower($productData['name']) . ' for everyday use.',
                'price' => $productData['price'],
                'old_price' => $productData['old_price'],
                'stock' => rand(10, 100),
                'sku' => 'RET-' . strtoupper(substr(md5($productData['name']), 0, 8)),
                'status' => 'active',
                'is_featured' => rand(0, 1),
                'rating' => $productData['rating'],
                'reviews_count' => rand(10, 150),
                'badge' => $productData['badge'],
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop',
            ]);
        }

        $this->command->info('Retailer products seeded successfully!');
    }
}
