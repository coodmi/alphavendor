<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class ExporterCategorySeeder extends Seeder
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

        $categoryTemplates = [
            [
                'name' => 'Agricultural Products',
                'description' => 'Fresh produce and agricultural goods for export',
                'image' => 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Textiles & Garments',
                'description' => 'Quality textiles and ready-made garments',
                'image' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Machinery & Equipment',
                'description' => 'Industrial machinery and heavy equipment',
                'image' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Electronics & Technology',
                'description' => 'Consumer electronics and tech products',
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Handicrafts & Artisan',
                'description' => 'Traditional handicrafts and artisan products',
                'image' => 'https://images.unsplash.com/photo-1528396518501-b53b655eb9b3?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Chemicals & Pharmaceuticals',
                'description' => 'Industrial chemicals and pharmaceutical products',
                'image' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&h=600&fit=crop',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        // Create categories for each exporter
        $now = now();
        $allCategories = [];

        foreach ($exporters as $index => $exporter) {
            // Each exporter gets 3-4 categories
            $categoriesCount = rand(3, 4);

            for ($i = 0; $i < $categoriesCount; $i++) {
                $template = $categoryTemplates[($index * 2 + $i) % count($categoryTemplates)];

                // Create unique slug by appending exporter ID
                $uniqueName = $template['name'] . ' ' . $exporter->id;

                $allCategories[] = [
                    'vendor_id' => $exporter->id,
                    'name' => $uniqueName,
                    'slug' => Str::slug($uniqueName) . '-' . uniqid(),
                    'description' => $template['description'] . ' curated by ' . $exporter->name,
                    'image' => $template['image'],
                    'is_active' => $template['is_active'],
                    'sort_order' => $template['sort_order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $this->command->info("✓ Created categories for exporter: {$exporter->name}");
        }

        if (!empty($allCategories)) {
            Category::insert($allCategories);
        }

        $this->command->info('Exporter categories seeded successfully!');
    }
}
