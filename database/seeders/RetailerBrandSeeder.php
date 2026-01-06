<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Str;

class RetailerBrandSeeder extends Seeder
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

        $brandTemplates = [
            [
                'name' => 'Urban Style',
                'description' => 'Modern fashion and lifestyle brand',
                'logo' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'TechVision',
                'description' => 'Innovative technology solutions',
                'logo' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'HomeComfort',
                'description' => 'Quality home and living essentials',
                'logo' => 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'FitLife',
                'description' => 'Sports and fitness equipment',
                'logo' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'GlowBeauty',
                'description' => 'Premium beauty and skincare products',
                'logo' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        // Create brands for each retailer
        $now = now();
        $allBrands = [];
        
        foreach ($retailers as $index => $retailer) {
            // Each retailer gets 2-3 brands
            $brandsCount = rand(2, 3);

            for ($i = 0; $i < $brandsCount; $i++) {
                $template = $brandTemplates[($index * 2 + $i) % count($brandTemplates)];
                $name = $template['name'] . ' - ' . $retailer->name;
                
                $allBrands[] = [
                    'vendor_id' => $retailer->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . uniqid(),
                    'description' => $template['description'] . ' by ' . $retailer->name,
                    'logo' => $template['logo'],
                    'is_active' => $template['is_active'],
                    'sort_order' => $template['sort_order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $this->command->info("✓ Created brands for retailer: {$retailer->name}");
        }
        
        if (!empty($allBrands)) {
            Brand::insert($allBrands);
        }

        $this->command->info('Retailer brands seeded successfully!');
    }
}
