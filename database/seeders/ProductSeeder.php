<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])->get();

        if ($categories->isEmpty()) {
            $this->command->error('Please run CategorySeeder first!');
            return;
        }

        if ($vendors->isEmpty()) {
            $this->command->error('No vendors found! Please create users with vendor roles first.');
            return;
        }

        $productsData = [
            // Fashion Products
            [
                'category_name' => 'Fashion',
                'name' => 'Premium Leather Jacket',
                'description' => 'High-quality genuine leather jacket with modern design',
                'price' => 199.99,
                'old_price' => 249.99,
                'stock' => 45,
                'sku' => 'FASH-LJ-001',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'New',
            ],
            [
                'category_name' => 'Fashion',
                'name' => 'Designer Sunglasses',
                'description' => 'Stylish UV protection sunglasses',
                'price' => 89.99,
                'old_price' => 129.99,
                'stock' => 120,
                'sku' => 'FASH-SG-002',
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'Sale',
            ],
            [
                'category_name' => 'Fashion',
                'name' => 'Casual Sneakers',
                'description' => 'Comfortable everyday sneakers',
                'price' => 79.99,
                'old_price' => null,
                'stock' => 200,
                'sku' => 'FASH-SN-003',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],

            // Electronics Products
            [
                'category_name' => 'Electronics',
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium noise-cancelling wireless headphones',
                'price' => 149.99,
                'old_price' => 199.99,
                'stock' => 85,
                'sku' => 'ELEC-HP-001',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Hot',
            ],
            [
                'category_name' => 'Electronics',
                'name' => 'Smart Watch Pro',
                'description' => 'Advanced fitness tracking smartwatch',
                'price' => 299.99,
                'old_price' => 349.99,
                'stock' => 60,
                'sku' => 'ELEC-SW-002',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'New',
            ],
            [
                'category_name' => 'Electronics',
                'name' => '4K Action Camera',
                'description' => 'Waterproof action camera with 4K recording',
                'price' => 249.99,
                'old_price' => null,
                'stock' => 40,
                'sku' => 'ELEC-CAM-003',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],

            // Home & Living Products
            [
                'category_name' => 'Home & Living',
                'name' => 'Modern Table Lamp',
                'description' => 'Elegant LED table lamp with adjustable brightness',
                'price' => 59.99,
                'old_price' => 79.99,
                'stock' => 150,
                'sku' => 'HOME-LAMP-001',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'category_name' => 'Home & Living',
                'name' => 'Decorative Wall Art',
                'description' => 'Contemporary canvas wall art set',
                'price' => 129.99,
                'old_price' => 159.99,
                'stock' => 75,
                'sku' => 'HOME-ART-002',
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'Sale',
            ],
            [
                'category_name' => 'Home & Living',
                'name' => 'Cozy Throw Blanket',
                'description' => 'Soft and warm fleece throw blanket',
                'price' => 39.99,
                'old_price' => null,
                'stock' => 200,
                'sku' => 'HOME-BLK-003',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],

            // Beauty & Health Products
            [
                'category_name' => 'Beauty & Health',
                'name' => 'Luxury Skincare Set',
                'description' => 'Complete skincare routine with natural ingredients',
                'price' => 89.99,
                'old_price' => 119.99,
                'stock' => 95,
                'sku' => 'BEAUTY-SKN-001',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Hot',
            ],
            [
                'category_name' => 'Beauty & Health',
                'name' => 'Professional Hair Dryer',
                'description' => 'Ionic hair dryer with multiple heat settings',
                'price' => 79.99,
                'old_price' => null,
                'stock' => 110,
                'sku' => 'BEAUTY-HD-002',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],

            // Sports & Outdoors Products
            [
                'category_name' => 'Sports & Outdoors',
                'name' => 'Yoga Mat Premium',
                'description' => 'Non-slip eco-friendly yoga mat',
                'price' => 49.99,
                'old_price' => 69.99,
                'stock' => 180,
                'sku' => 'SPORT-YM-001',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'category_name' => 'Sports & Outdoors',
                'name' => 'Camping Tent 4-Person',
                'description' => 'Waterproof camping tent for family adventures',
                'price' => 189.99,
                'old_price' => 239.99,
                'stock' => 35,
                'sku' => 'SPORT-TNT-002',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'New',
            ],

            // Books & Media Products
            [
                'category_name' => 'Books & Media',
                'name' => 'Bestseller Novel Collection',
                'description' => 'Set of 5 award-winning novels',
                'price' => 49.99,
                'old_price' => null,
                'stock' => 120,
                'sku' => 'BOOK-NOV-001',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'category_name' => 'Books & Media',
                'name' => 'Vinyl Record Player',
                'description' => 'Vintage-style turntable with Bluetooth',
                'price' => 159.99,
                'old_price' => 199.99,
                'stock' => 45,
                'sku' => 'BOOK-VRP-002',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Hot',
            ],

            // Toys & Games Products
            [
                'category_name' => 'Toys & Games',
                'name' => 'Building Blocks Set',
                'description' => '500-piece creative building blocks',
                'price' => 39.99,
                'old_price' => null,
                'stock' => 250,
                'sku' => 'TOY-BB-001',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'category_name' => 'Toys & Games',
                'name' => 'Board Game Collection',
                'description' => 'Classic family board games bundle',
                'price' => 59.99,
                'old_price' => 79.99,
                'stock' => 90,
                'sku' => 'TOY-BG-002',
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'Sale',
            ],

            // Automotive Products
            [
                'category_name' => 'Automotive',
                'name' => 'Car Dash Camera HD',
                'description' => 'Full HD dashboard camera with night vision',
                'price' => 119.99,
                'old_price' => 149.99,
                'stock' => 70,
                'sku' => 'AUTO-DC-001',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'category_name' => 'Automotive',
                'name' => 'Car Phone Mount',
                'description' => 'Universal magnetic car phone holder',
                'price' => 24.99,
                'old_price' => null,
                'stock' => 300,
                'sku' => 'AUTO-PM-002',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],

            // Jewelry & Watches Products
            [
                'category_name' => 'Jewelry & Watches',
                'name' => 'Silver Pendant Necklace',
                'description' => 'Elegant sterling silver pendant with chain',
                'price' => 79.99,
                'old_price' => 99.99,
                'stock' => 55,
                'sku' => 'JEWL-NKL-001',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'New',
            ],
            [
                'category_name' => 'Jewelry & Watches',
                'name' => 'Luxury Wrist Watch',
                'description' => 'Premium automatic mechanical watch',
                'price' => 399.99,
                'old_price' => 499.99,
                'stock' => 25,
                'sku' => 'JEWL-WCH-002',
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Hot',
            ],

            // Pet Supplies Products
            [
                'category_name' => 'Pet Supplies',
                'name' => 'Pet Bed Deluxe',
                'description' => 'Comfortable orthopedic pet bed',
                'price' => 69.99,
                'old_price' => null,
                'stock' => 80,
                'sku' => 'PET-BED-001',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'category_name' => 'Pet Supplies',
                'name' => 'Interactive Pet Toy Set',
                'description' => 'Engaging toys to keep pets entertained',
                'price' => 29.99,
                'old_price' => 39.99,
                'stock' => 150,
                'sku' => 'PET-TOY-002',
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
        ];

        $products = [];
        foreach ($productsData as $productData) {
            $categoryName = $productData['category_name'];
            unset($productData['category_name']);

            $category = $categories->firstWhere('name', $categoryName);
            if (!$category) {
                continue;
            }

            $vendor = $vendors->random();

            $productData['category_id'] = $category->id;
            $productData['vendor_id'] = $vendor->id;
            $productData['slug'] = Str::slug($productData['name']) . '-' . uniqid();
            $productData['rating'] = rand(35, 50) / 10;
            $productData['reviews_count'] = rand(10, 500);
            $productData['image'] = 'products/placeholder.jpg';

            $products[] = $productData;
        }

        Product::insert($products);
        $this->command->info('Products seeded successfully!');
    }
}
