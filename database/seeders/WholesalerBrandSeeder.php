<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\User;

class WholesalerBrandSeeder extends Seeder
{
    public function run(): void
    {
        // Get all wholesaler users
        $wholesalers = User::where('role', 'wholesaler')->where('status', 'active')->get();

        if ($wholesalers->isEmpty()) {
            $this->command->warn('No wholesaler users found. Run WholesalerUserSeeder first.');
            return;
        }

        $brandTemplates = [
            [
                'name' => 'BulkTech Pro',
                'slug' => 'bulktech-pro',
                'description' => 'Professional bulk technology solutions for businesses',
                'logo' => 'https://picsum.photos/seed/bulktech/400/400',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'MegaSupply Co',
                'slug' => 'megasupply-co',
                'description' => 'Your trusted partner for wholesale supplies',
                'logo' => 'https://picsum.photos/seed/megasupply/400/400',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'GlobalTrade Brands',
                'slug' => 'globaltrade-brands',
                'description' => 'International wholesale brand distributor',
                'logo' => 'https://picsum.photos/seed/globaltrade/400/400',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'ValueBulk',
                'slug' => 'valuebulk',
                'description' => 'Quality products at wholesale prices',
                'logo' => 'https://picsum.photos/seed/valuebulk/400/400',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'TradeGiant',
                'slug' => 'tradegiant',
                'description' => 'Giant selections for trade professionals',
                'logo' => 'https://picsum.photos/seed/tradegiant/400/400',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($wholesalers as $index => $wholesaler) {
            foreach ($brandTemplates as $template) {
                // Make slug unique per vendor
                $uniqueSlug = $template['slug'] . '-' . $wholesaler->id;
                $uniqueName = $template['name'] . ' (W' . $wholesaler->id . ')';

                Brand::create([
                    'vendor_id' => $wholesaler->id,
                    'name' => $uniqueName,
                    'slug' => $uniqueSlug,
                    'description' => $template['description'],
                    'logo' => $template['logo'],
                    'is_active' => $template['is_active'],
                    'sort_order' => $template['sort_order'],
                ]);
            }
            $this->command->info("Brands seeded for wholesaler: {$wholesaler->email}");
        }

        $this->command->info('All wholesaler brands seeded successfully!');
    }
}
