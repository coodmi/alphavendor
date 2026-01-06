<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Str;

class ExporterBrandSeeder extends Seeder
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

        $brandTemplates = [
            [
                'name' => 'Global Trade Co',
                'description' => 'International trade and export solutions',
                'logo' => 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pacific Exports',
                'description' => 'Premium export goods from Asia-Pacific region',
                'logo' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Continental Goods',
                'description' => 'Quality products for international markets',
                'logo' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Maritime Traders',
                'description' => 'Shipping and maritime trade specialists',
                'logo' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Horizon International',
                'description' => 'Bridging markets across borders',
                'logo' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        // Create brands for each exporter
        $now = now();
        $allBrands = [];

        foreach ($exporters as $index => $exporter) {
            // Each exporter gets 2-3 brands
            $brandsCount = rand(2, 3);

            for ($i = 0; $i < $brandsCount; $i++) {
                $template = $brandTemplates[($index * 2 + $i) % count($brandTemplates)];
                $name = $template['name'] . ' - ' . $exporter->name;

                $allBrands[] = [
                    'vendor_id' => $exporter->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . uniqid(),
                    'description' => $template['description'] . ' by ' . $exporter->name,
                    'logo' => $template['logo'],
                    'is_active' => $template['is_active'],
                    'sort_order' => $template['sort_order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $this->command->info("✓ Created brands for exporter: {$exporter->name}");
        }

        if (!empty($allBrands)) {
            Brand::insert($allBrands);
        }

        $this->command->info('Exporter brands seeded successfully!');
    }
}
