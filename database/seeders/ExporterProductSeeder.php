<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Str;

class ExporterProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all exporters
        $exporters = User::where('role', 'exporter')->get();

        if ($exporters->isEmpty()) {
            $this->command->warn('No exporters found! Please run VendorSeeder first.');
            return;
        }

        $locations = [
            'Shanghai, China',
            'Mumbai, India',
            'Bangkok, Thailand',
            'Ho Chi Minh City, Vietnam',
            'Jakarta, Indonesia',
            'Dhaka, Bangladesh',
            'Manila, Philippines',
            'Karachi, Pakistan',
        ];

        $productTemplates = [
            [
                'name' => 'Organic Rice Export Grade',
                'description' => 'Premium quality organic rice for international markets',
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=800&h=800&fit=crop',
                'price' => 45.00,
                'old_price' => 55.00,
                'stock' => 5000,
                'minimum_order' => 100,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Premium',
            ],
            [
                'name' => 'Cotton Fabric Roll',
                'description' => 'High-quality cotton fabric for garment manufacturing',
                'image' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?w=800&h=800&fit=crop',
                'price' => 120.00,
                'old_price' => null,
                'stock' => 2000,
                'minimum_order' => 50,
                'status' => 'active',
                'is_featured' => true,
                'badge' => null,
            ],
            [
                'name' => 'Industrial CNC Machine',
                'description' => 'Precision CNC machine for manufacturing plants',
                'image' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=800&h=800&fit=crop',
                'price' => 15000.00,
                'old_price' => 18000.00,
                'stock' => 25,
                'minimum_order' => 1,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Best Seller',
            ],
            [
                'name' => 'LED Display Panel',
                'description' => 'Commercial LED display panels for advertising',
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=800&fit=crop',
                'price' => 850.00,
                'old_price' => 1000.00,
                'stock' => 500,
                'minimum_order' => 10,
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'Sale',
            ],
            [
                'name' => 'Handwoven Silk Scarf',
                'description' => 'Traditional handwoven silk scarves with unique patterns',
                'image' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=800&h=800&fit=crop',
                'price' => 35.00,
                'old_price' => 45.00,
                'stock' => 1500,
                'minimum_order' => 200,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Artisan',
            ],
            [
                'name' => 'Industrial Chemicals Pack',
                'description' => 'High-grade industrial chemicals for manufacturing',
                'image' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&h=800&fit=crop',
                'price' => 500.00,
                'old_price' => null,
                'stock' => 300,
                'minimum_order' => 5,
                'status' => 'active',
                'is_featured' => false,
                'badge' => null,
            ],
            [
                'name' => 'Spice Collection Export',
                'description' => 'Premium quality spices from organic farms',
                'image' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=800&h=800&fit=crop',
                'price' => 25.00,
                'old_price' => 30.00,
                'stock' => 8000,
                'minimum_order' => 500,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Organic',
            ],
            [
                'name' => 'Ceramic Tiles Bulk',
                'description' => 'Decorative ceramic tiles for construction projects',
                'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=800&fit=crop',
                'price' => 8.50,
                'old_price' => 12.00,
                'stock' => 50000,
                'minimum_order' => 1000,
                'status' => 'active',
                'is_featured' => false,
                'badge' => 'Bulk Deal',
            ],
            [
                'name' => 'Leather Handbags',
                'description' => 'Genuine leather handbags for retail distribution',
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&h=800&fit=crop',
                'price' => 65.00,
                'old_price' => 85.00,
                'stock' => 3000,
                'minimum_order' => 100,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Popular',
            ],
            [
                'name' => 'Solar Panel Module',
                'description' => 'High-efficiency solar panels for renewable energy',
                'image' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800&h=800&fit=crop',
                'price' => 280.00,
                'old_price' => 320.00,
                'stock' => 1000,
                'minimum_order' => 20,
                'status' => 'active',
                'is_featured' => true,
                'badge' => 'Eco-Friendly',
            ],
        ];

        // Create products for each exporter
        foreach ($exporters as $exporter) {
            // Get exporter's categories and brands
            $categories = Category::where('vendor_id', $exporter->id)->get();
            $brands = Brand::where('vendor_id', $exporter->id)->get();

            if ($categories->isEmpty()) {
                $this->command->warn("No categories found for exporter: {$exporter->name}. Skipping...");
                continue;
            }

            // Each exporter gets 6-10 products
            $productsCount = rand(6, 10);

            for ($i = 0; $i < $productsCount; $i++) {
                $template = $productTemplates[$i % count($productTemplates)];
                $category = $categories->random();
                $brand = $brands->isNotEmpty() ? $brands->random() : null;

                Product::create([
                    'vendor_id' => $exporter->id,
                    'category_id' => $category->id,
                    'brand_id' => $brand ? $brand->id : null,
                    'name' => $template['name'],
                    'slug' => Str::slug($template['name']) . '-' . $exporter->id . '-' . uniqid(),
                    'description' => $template['description'],
                    'image' => $template['image'],
                    'price' => $template['price'],
                    'old_price' => $template['old_price'],
                    'stock' => $template['stock'],
                    'minimum_order' => $template['minimum_order'],
                    'supplier_location' => $locations[array_rand($locations)],
                    'sku' => 'EXP-' . strtoupper(substr($exporter->name, 0, 3)) . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'status' => $template['status'],
                    'is_featured' => $template['is_featured'],
                    'badge' => $template['badge'],
                ]);
            }

            $this->command->info("✓ Created products for exporter: {$exporter->name}");
        }

        $this->command->info('Exporter products seeded successfully!');
    }
}
