<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;

class WholesalerProductSeeder extends Seeder
{
    public function run(): void
    {
        $wholesaler = User::where('email', 'wholesaler@vendor.com')->first();

        if (!$wholesaler) {
            $this->command->warn('Wholesaler user not found. Run WholesalerUserSeeder first.');
            return;
        }

        $categories = Category::where('vendor_id', $wholesaler->id)->get();
        $brands = Brand::where('vendor_id', $wholesaler->id)->get();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->warn('Categories or Brands not found. Run WholesalerCategorySeeder and WholesalerBrandSeeder first.');
            return;
        }

        $locations = ['China', 'India', 'Taiwan', 'Vietnam', 'Thailand', 'Malaysia', 'Indonesia', 'South Korea', 'Turkey', 'Mexico'];
        $badges = ['NEW', 'HOT', 'SALE', 'TRENDING', null];
        $statuses = ['active', 'active', 'active', 'inactive'];

        $productTemplates = [
            ['name' => 'Wireless Bluetooth Headphones Bulk Pack', 'price' => 15.99, 'old_price' => 24.99, 'stock' => 5000, 'min_order' => 100],
            ['name' => 'USB-C Charging Cables 50-Pack', 'price' => 89.99, 'old_price' => 149.99, 'stock' => 2000, 'min_order' => 50],
            ['name' => 'LED Desk Lamps Wholesale Lot', 'price' => 12.50, 'old_price' => 19.99, 'stock' => 3000, 'min_order' => 200],
            ['name' => 'Portable Power Banks 10000mAh Bulk', 'price' => 8.99, 'old_price' => 15.99, 'stock' => 10000, 'min_order' => 500],
            ['name' => 'Stainless Steel Water Bottles Wholesale', 'price' => 5.99, 'old_price' => 12.99, 'stock' => 8000, 'min_order' => 300],
            ['name' => 'Eco-Friendly Tote Bags 100-Pack', 'price' => 49.99, 'old_price' => 89.99, 'stock' => 1500, 'min_order' => 100],
            ['name' => 'Wireless Mouse Bulk Order', 'price' => 6.99, 'old_price' => 14.99, 'stock' => 6000, 'min_order' => 250],
            ['name' => 'Laptop Stands Wholesale Pack', 'price' => 11.99, 'old_price' => 24.99, 'stock' => 4000, 'min_order' => 150],
            ['name' => 'Phone Cases Assorted 500-Pack', 'price' => 199.99, 'old_price' => 399.99, 'stock' => 800, 'min_order' => 500],
            ['name' => 'Bluetooth Speakers Bulk Lot', 'price' => 18.99, 'old_price' => 34.99, 'stock' => 3500, 'min_order' => 100],
            ['name' => 'Fitness Resistance Bands Set Wholesale', 'price' => 4.99, 'old_price' => 9.99, 'stock' => 7000, 'min_order' => 400],
            ['name' => 'Yoga Mats Bulk Purchase', 'price' => 9.99, 'old_price' => 19.99, 'stock' => 5000, 'min_order' => 200],
            ['name' => 'Mechanical Keyboards Wholesale', 'price' => 29.99, 'old_price' => 59.99, 'stock' => 2500, 'min_order' => 50],
            ['name' => 'Webcams HD 1080p Bulk Order', 'price' => 22.99, 'old_price' => 44.99, 'stock' => 3000, 'min_order' => 100],
            ['name' => 'Smart Watch Fitness Trackers Wholesale', 'price' => 24.99, 'old_price' => 49.99, 'stock' => 4500, 'min_order' => 150],
            ['name' => 'Desk Organizers Bulk Pack', 'price' => 7.99, 'old_price' => 14.99, 'stock' => 6000, 'min_order' => 300],
            ['name' => 'Notebook Sets Wholesale 200-Pack', 'price' => 149.99, 'old_price' => 299.99, 'stock' => 1000, 'min_order' => 200],
            ['name' => 'Pen Multi-Pack Wholesale 1000pcs', 'price' => 99.99, 'old_price' => 199.99, 'stock' => 5000, 'min_order' => 1000],
            ['name' => 'Backpacks Bulk Order 100-Pack', 'price' => 599.99, 'old_price' => 999.99, 'stock' => 500, 'min_order' => 100],
            ['name' => 'Sunglasses Assorted Styles Wholesale', 'price' => 3.99, 'old_price' => 9.99, 'stock' => 12000, 'min_order' => 500],
        ];

        $skuCounter = 1000;

        foreach ($productTemplates as $template) {
            $category = $categories->random();
            $brand = $brands->random();

            Product::create([
                'vendor_id' => $wholesaler->id,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name' => $template['name'],
                'description' => 'Bulk wholesale product available at competitive prices. Perfect for retailers and businesses looking to stock inventory. High quality guaranteed with fast shipping options.',
                'image' => 'https://picsum.photos/seed/wproduct' . $skuCounter . '/800/800',
                'price' => $template['price'],
                'old_price' => $template['old_price'],
                'stock' => $template['stock'],
                'minimum_order' => $template['min_order'],
                'supplier_location' => $locations[array_rand($locations)],
                'sku' => 'WS-' . str_pad($skuCounter++, 6, '0', STR_PAD_LEFT),
                'status' => $statuses[array_rand($statuses)],
                'rating' => rand(35, 50) / 10,
                'reviews_count' => rand(10, 500),
                'is_featured' => rand(0, 1) ? true : false,
                'badge' => $badges[array_rand($badges)],
            ]);
        }

        $this->command->info('Wholesaler products seeded successfully!');
    }
}
