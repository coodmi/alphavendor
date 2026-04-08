<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class ExportProductsSeeder extends Seeder
{
    public function run()
    {
        // Create an exporter user if doesn't exist
        $exporter = User::firstOrCreate(
            ['email' => 'exporter@example.com'],
            [
                'name' => 'Global Exporter',
                'password' => Hash::make('password'),
                'role' => 'exporter',
                'email_verified_at' => now(),
            ]
        );

        // Get or create categories
        $categories = [
            'Electronics' => Category::firstOrCreate(['name' => 'Electronics', 'slug' => 'electronics'], ['is_active' => true]),
            'Textiles' => Category::firstOrCreate(['name' => 'Textiles', 'slug' => 'textiles'], ['is_active' => true]),
            'Machinery' => Category::firstOrCreate(['name' => 'Machinery', 'slug' => 'machinery'], ['is_active' => true]),
            'Food Products' => Category::firstOrCreate(['name' => 'Food Products', 'slug' => 'food-products'], ['is_active' => true]),
            'Chemicals' => Category::firstOrCreate(['name' => 'Chemicals', 'slug' => 'chemicals'], ['is_active' => true]),
        ];

        // Demo export products
        $products = [
            [
                'name' => 'Industrial LED Lighting System',
                'description' => 'High-efficiency LED lighting system for industrial applications. Energy-saving, long-lasting, and eco-friendly.',
                'price' => 45.99,
                'old_price' => 65.99,
                'category' => 'Electronics',
                'image' => 'https://images.unsplash.com/photo-1524484485831-a92ffc0de03f?w=500&h=500&fit=crop',
                'minimum_order' => 500,
                'supplier_location' => 'China',
                'rating' => 4.7,
                'reviews_count' => 156,
                'badge' => 'SALE',
            ],
            [
                'name' => 'Organic Cotton Fabric Rolls',
                'description' => 'Premium organic cotton fabric, GOTS certified. Perfect for garment manufacturing.',
                'price' => 12.50,
                'old_price' => 18.00,
                'category' => 'Textiles',
                'image' => 'https://images.unsplash.com/photo-1558769132-cb1aea3c8565?w=500&h=500&fit=crop',
                'minimum_order' => 1000,
                'supplier_location' => 'India',
                'rating' => 4.8,
                'reviews_count' => 203,
                'badge' => 'HOT',
            ],
            [
                'name' => 'CNC Milling Machine',
                'description' => 'High-precision CNC milling machine for metal and plastic processing. Industrial grade.',
                'price' => 8500.00,
                'old_price' => 12000.00,
                'category' => 'Machinery',
                'image' => 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=500&h=500&fit=crop',
                'minimum_order' => 1,
                'supplier_location' => 'Germany',
                'rating' => 4.9,
                'reviews_count' => 87,
                'badge' => 'NEW',
            ],
            [
                'name' => 'Premium Green Tea Extract',
                'description' => 'High-quality green tea extract powder. FDA approved, suitable for supplements and beverages.',
                'price' => 25.00,
                'old_price' => 35.00,
                'category' => 'Food Products',
                'image' => 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=500&h=500&fit=crop',
                'minimum_order' => 100,
                'supplier_location' => 'Japan',
                'rating' => 4.6,
                'reviews_count' => 142,
            ],
            [
                'name' => 'Wireless Bluetooth Earbuds Bulk',
                'description' => 'High-quality wireless earbuds with noise cancellation. Perfect for retail distribution.',
                'price' => 8.99,
                'old_price' => 15.99,
                'category' => 'Electronics',
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&h=500&fit=crop',
                'minimum_order' => 500,
                'supplier_location' => 'China',
                'rating' => 4.5,
                'reviews_count' => 312,
                'badge' => 'SALE',
            ],
            [
                'name' => 'Denim Jeans Wholesale',
                'description' => 'Premium quality denim jeans in various sizes. Durable and fashionable.',
                'price' => 15.99,
                'old_price' => 24.99,
                'category' => 'Textiles',
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop',
                'minimum_order' => 200,
                'supplier_location' => 'Bangladesh',
                'rating' => 4.4,
                'reviews_count' => 178,
            ],
            [
                'name' => 'Industrial Air Compressor',
                'description' => 'Heavy-duty air compressor for industrial use. High efficiency and reliability.',
                'price' => 1250.00,
                'old_price' => 1800.00,
                'category' => 'Machinery',
                'image' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=500&h=500&fit=crop',
                'minimum_order' => 5,
                'supplier_location' => 'USA',
                'rating' => 4.7,
                'reviews_count' => 94,
            ],
            [
                'name' => 'Organic Coconut Oil',
                'description' => 'Virgin coconut oil, cold-pressed and organic certified. Food grade quality.',
                'price' => 18.50,
                'old_price' => 28.00,
                'category' => 'Food Products',
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&h=500&fit=crop',
                'minimum_order' => 100,
                'supplier_location' => 'Philippines',
                'rating' => 4.8,
                'reviews_count' => 267,
                'badge' => 'HOT',
            ],
            [
                'name' => 'Smartphone LCD Screens Bulk',
                'description' => 'High-quality replacement LCD screens for various smartphone models.',
                'price' => 22.99,
                'old_price' => 35.99,
                'category' => 'Electronics',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop',
                'minimum_order' => 100,
                'supplier_location' => 'South Korea',
                'rating' => 4.6,
                'reviews_count' => 189,
            ],
            [
                'name' => 'Cotton T-Shirts Wholesale',
                'description' => '100% cotton t-shirts in various colors and sizes. Perfect for printing.',
                'price' => 3.99,
                'old_price' => 6.99,
                'category' => 'Textiles',
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop',
                'minimum_order' => 500,
                'supplier_location' => 'Vietnam',
                'rating' => 4.5,
                'reviews_count' => 421,
                'badge' => 'SALE',
            ],
            [
                'name' => 'Hydraulic Press Machine',
                'description' => 'Industrial hydraulic press for metal forming and stamping operations.',
                'price' => 5500.00,
                'old_price' => 7500.00,
                'category' => 'Machinery',
                'image' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=500&h=500&fit=crop',
                'minimum_order' => 1,
                'supplier_location' => 'Italy',
                'rating' => 4.8,
                'reviews_count' => 76,
            ],
            [
                'name' => 'Instant Coffee Powder',
                'description' => 'Premium instant coffee powder. Rich flavor and aroma. Food grade certified.',
                'price' => 12.00,
                'old_price' => 18.00,
                'category' => 'Food Products',
                'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&h=500&fit=crop',
                'minimum_order' => 200,
                'supplier_location' => 'Brazil',
                'rating' => 4.7,
                'reviews_count' => 234,
            ],
            [
                'name' => 'Solar Panel Modules',
                'description' => 'High-efficiency solar panels for residential and commercial use.',
                'price' => 185.00,
                'old_price' => 250.00,
                'category' => 'Electronics',
                'image' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=500&h=500&fit=crop',
                'minimum_order' => 50,
                'supplier_location' => 'China',
                'rating' => 4.9,
                'reviews_count' => 145,
                'badge' => 'NEW',
            ],
            [
                'name' => 'Silk Fabric Premium Quality',
                'description' => 'Pure silk fabric for luxury garments. Smooth texture and vibrant colors.',
                'price' => 45.00,
                'old_price' => 65.00,
                'category' => 'Textiles',
                'image' => 'https://images.unsplash.com/photo-1596783074918-c84cb06531ca?w=500&h=500&fit=crop',
                'minimum_order' => 100,
                'supplier_location' => 'China',
                'rating' => 4.8,
                'reviews_count' => 167,
            ],
            [
                'name' => 'Laser Cutting Machine',
                'description' => 'Precision laser cutting machine for metal, wood, and acrylic materials.',
                'price' => 12500.00,
                'old_price' => 18000.00,
                'category' => 'Machinery',
                'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=500&h=500&fit=crop',
                'minimum_order' => 1,
                'supplier_location' => 'Germany',
                'rating' => 4.9,
                'reviews_count' => 92,
                'badge' => 'HOT',
            ],
            [
                'name' => 'Organic Honey Bulk',
                'description' => 'Pure organic honey from natural sources. No additives or preservatives.',
                'price' => 8.50,
                'old_price' => 12.00,
                'category' => 'Food Products',
                'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784acc?w=500&h=500&fit=crop',
                'minimum_order' => 100,
                'supplier_location' => 'New Zealand',
                'rating' => 4.9,
                'reviews_count' => 298,
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories[$productData['category']];
            
            Product::create([
                'vendor_id' => $exporter->id,
                'category_id' => $category->id,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'old_price' => $productData['old_price'] ?? null,
                'image' => $productData['image'],
                'minimum_order' => $productData['minimum_order'],
                'supplier_location' => $productData['supplier_location'],
                'rating' => $productData['rating'],
                'reviews_count' => $productData['reviews_count'],
                'badge' => $productData['badge'] ?? null,
                'status' => 'active',
                'stock' => 9999,
                'sku' => 'EXP-' . strtoupper(substr(md5($productData['name']), 0, 8)),
            ]);
        }

        $this->command->info('Export products seeded successfully!');
    }
}
