<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;

class DemoProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Get vendors
        $retailer = User::where('email', 'retailer@example.com')->first();
        $wholesaler = User::where('email', 'wholesaler@example.com')->first();
        $importer = User::where('email', 'importer@example.com')->first();

        if (!$retailer || !$wholesaler || !$importer) {
            $this->command->error('Vendors not found! Please run AdminSeeder first.');
            return;
        }

        // Get or create categories
        $electronics = Category::firstOrCreate(['name' => 'Electronics', 'slug' => 'electronics']);
        $fashion = Category::firstOrCreate(['name' => 'Fashion', 'slug' => 'fashion']);
        $home = Category::firstOrCreate(['name' => 'Home & Living', 'slug' => 'home-living']);
        $beauty = Category::firstOrCreate(['name' => 'Beauty & Health', 'slug' => 'beauty-health']);
        $sports = Category::firstOrCreate(['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors']);

        // Get or create brands
        $samsung = Brand::firstOrCreate(['name' => 'Samsung', 'slug' => 'samsung']);
        $apple = Brand::firstOrCreate(['name' => 'Apple', 'slug' => 'apple']);
        $nike = Brand::firstOrCreate(['name' => 'Nike', 'slug' => 'nike']);
        $adidas = Brand::firstOrCreate(['name' => 'Adidas', 'slug' => 'adidas']);
        $generic = Brand::firstOrCreate(['name' => 'Generic', 'slug' => 'generic']);

        // Retailer Products (5)
        $retailerProducts = [
            [
                'name' => 'Samsung Galaxy A54 5G Smartphone',
                'slug' => 'samsung-galaxy-a54-5g',
                'description' => 'Latest Samsung Galaxy A54 with 5G connectivity, 128GB storage, 8GB RAM, and stunning AMOLED display. Perfect for everyday use with excellent camera quality.',
                'price' => 42999.00,
                'old_price' => 49999.00,
                'stock' => 25,
                'category_id' => $electronics->id,
                'brand_id' => $samsung->id,
                'vendor_id' => $retailer->id,
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'HOT',
            ],
            [
                'name' => 'Nike Air Max 270 Running Shoes',
                'slug' => 'nike-air-max-270-running-shoes',
                'description' => 'Comfortable Nike Air Max 270 running shoes with excellent cushioning and breathable mesh upper. Available in multiple colors and sizes.',
                'price' => 8999.00,
                'old_price' => 12999.00,
                'stock' => 50,
                'category_id' => $sports->id,
                'brand_id' => $nike->id,
                'vendor_id' => $retailer->id,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'SALE',
            ],
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'Premium wireless Bluetooth headphones with active noise cancellation, 30-hour battery life, and superior sound quality. Perfect for music lovers.',
                'price' => 3499.00,
                'old_price' => 4999.00,
                'stock' => 40,
                'category_id' => $electronics->id,
                'brand_id' => $generic->id,
                'vendor_id' => $retailer->id,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Cotton Casual T-Shirt Pack (3 Pieces)',
                'slug' => 'cotton-casual-tshirt-pack',
                'description' => 'Premium quality 100% cotton casual t-shirts. Pack of 3 in assorted colors. Comfortable, breathable, and perfect for daily wear.',
                'price' => 1299.00,
                'old_price' => 1899.00,
                'stock' => 100,
                'category_id' => $fashion->id,
                'brand_id' => $generic->id,
                'vendor_id' => $retailer->id,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'LED Desk Lamp with USB Charging',
                'slug' => 'led-desk-lamp-usb-charging',
                'description' => 'Modern LED desk lamp with adjustable brightness, USB charging port, and flexible arm. Energy-efficient and eye-friendly lighting for study or work.',
                'price' => 1899.00,
                'old_price' => 2499.00,
                'stock' => 35,
                'category_id' => $home->id,
                'brand_id' => $generic->id,
                'vendor_id' => $retailer->id,
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'NEW',
            ],
        ];

        // Wholesaler Products (5)
        $wholesalerProducts = [
            [
                'name' => 'Bulk Cotton T-Shirts (50 Pieces)',
                'slug' => 'bulk-cotton-tshirts-50pcs',
                'description' => 'Wholesale pack of 50 premium cotton t-shirts. Mixed sizes and colors. Perfect for retailers or bulk buyers. High-quality fabric at wholesale price.',
                'price' => 15000.00,
                'old_price' => 20000.00,
                'stock' => 500,
                'category_id' => $fashion->id,
                'brand_id' => $generic->id,
                'vendor_id' => $wholesaler->id,
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'WHOLESALE',
            ],
            [
                'name' => 'Smartphone Accessories Bundle (100 Units)',
                'slug' => 'smartphone-accessories-bundle-100',
                'description' => 'Wholesale bundle of 100 smartphone accessories including cases, screen protectors, chargers, and cables. Mixed items for various phone models.',
                'price' => 25000.00,
                'old_price' => 35000.00,
                'stock' => 1000,
                'category_id' => $electronics->id,
                'brand_id' => $generic->id,
                'vendor_id' => $wholesaler->id,
                'image' => 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'BULK',
            ],
            [
                'name' => 'LED Bulbs Wholesale Pack (200 Pieces)',
                'slug' => 'led-bulbs-wholesale-200pcs',
                'description' => 'Energy-efficient LED bulbs wholesale pack. 200 pieces of 9W LED bulbs. Long-lasting, eco-friendly, and cost-effective lighting solution.',
                'price' => 18000.00,
                'old_price' => 24000.00,
                'stock' => 2000,
                'category_id' => $home->id,
                'brand_id' => $generic->id,
                'vendor_id' => $wholesaler->id,
                'image' => 'https://images.unsplash.com/photo-1524484485831-a92ffc0de03f?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Sports Water Bottles Bulk (100 Units)',
                'slug' => 'sports-water-bottles-bulk-100',
                'description' => 'BPA-free sports water bottles in bulk. 100 units per pack. Various colors available. Perfect for gyms, sports clubs, or retail.',
                'price' => 8000.00,
                'old_price' => 12000.00,
                'stock' => 800,
                'category_id' => $sports->id,
                'brand_id' => $generic->id,
                'vendor_id' => $wholesaler->id,
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Face Masks Wholesale (500 Pieces)',
                'slug' => 'face-masks-wholesale-500pcs',
                'description' => '3-ply disposable face masks. Wholesale pack of 500 pieces. High-quality, breathable, and comfortable. Ideal for bulk buyers and retailers.',
                'price' => 5000.00,
                'old_price' => 7500.00,
                'stock' => 5000,
                'category_id' => $beauty->id,
                'brand_id' => $generic->id,
                'vendor_id' => $wholesaler->id,
                'image' => 'https://images.unsplash.com/photo-1584483766114-2cea6facdf57?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
        ];

        // Importer Products (5)
        $importerProducts = [
            [
                'name' => 'Apple iPhone 15 Pro Max (Imported)',
                'slug' => 'apple-iphone-15-pro-max-imported',
                'description' => 'Latest Apple iPhone 15 Pro Max imported from USA. 256GB storage, A17 Pro chip, titanium design, and advanced camera system. Original with international warranty.',
                'price' => 149999.00,
                'old_price' => 169999.00,
                'stock' => 15,
                'category_id' => $electronics->id,
                'brand_id' => $apple->id,
                'vendor_id' => $importer->id,
                'image' => 'https://images.unsplash.com/photo-1592286927505-2fd0c4a0f7e1?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'IMPORTED',
            ],
            [
                'name' => 'Adidas Ultraboost 23 (Original Import)',
                'slug' => 'adidas-ultraboost-23-original',
                'description' => 'Original Adidas Ultraboost 23 running shoes imported from Germany. Premium quality with Boost technology for maximum comfort and performance.',
                'price' => 18999.00,
                'old_price' => 24999.00,
                'stock' => 30,
                'category_id' => $sports->id,
                'brand_id' => $adidas->id,
                'vendor_id' => $importer->id,
                'image' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'ORIGINAL',
            ],
            [
                'name' => 'Sony WH-1000XM5 Noise Cancelling Headphones',
                'slug' => 'sony-wh1000xm5-headphones',
                'description' => 'Premium Sony WH-1000XM5 wireless headphones with industry-leading noise cancellation. Imported from Japan with full warranty and original accessories.',
                'price' => 32999.00,
                'old_price' => 39999.00,
                'stock' => 20,
                'category_id' => $electronics->id,
                'brand_id' => $generic->id,
                'vendor_id' => $importer->id,
                'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Swiss Beauty Cosmetics Set (Imported)',
                'slug' => 'swiss-beauty-cosmetics-set',
                'description' => 'Premium Swiss beauty cosmetics set imported from Switzerland. Includes foundation, lipstick, eyeshadow palette, and brushes. Dermatologically tested.',
                'price' => 8999.00,
                'old_price' => 12999.00,
                'stock' => 40,
                'category_id' => $beauty->id,
                'brand_id' => $generic->id,
                'vendor_id' => $importer->id,
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'Italian Leather Handbag (Genuine Import)',
                'slug' => 'italian-leather-handbag',
                'description' => 'Genuine Italian leather handbag imported from Italy. Handcrafted with premium quality leather. Elegant design perfect for formal and casual occasions.',
                'price' => 15999.00,
                'old_price' => 21999.00,
                'stock' => 25,
                'category_id' => $fashion->id,
                'brand_id' => $generic->id,
                'vendor_id' => $importer->id,
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500&h=500&fit=crop',
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'PREMIUM',
            ],
        ];

        // Insert Retailer Products
        $this->command->info('Creating Retailer Products...');
        foreach ($retailerProducts as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
        $this->command->info('✓ Created 5 Retailer products');

        // Insert Wholesaler Products
        $this->command->info('Creating Wholesaler Products...');
        foreach ($wholesalerProducts as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
        $this->command->info('✓ Created 5 Wholesaler products');

        // Insert Importer Products
        $this->command->info('Creating Importer Products...');
        foreach ($importerProducts as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
        $this->command->info('✓ Created 5 Importer products');

        $this->command->info('');
        $this->command->info('✅ Successfully created 15 demo products!');
        $this->command->info('   - 5 Retailer products');
        $this->command->info('   - 5 Wholesaler products');
        $this->command->info('   - 5 Importer products');
    }
}
