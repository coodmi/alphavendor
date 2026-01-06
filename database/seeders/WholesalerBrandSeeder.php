<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\User;

class WholesalerBrandSeeder extends Seeder
{
    public function run(): void
    {
        $wholesaler = User::where('email', 'wholesaler@vendor.com')->first();

        if (!$wholesaler) {
            $this->command->warn('Wholesaler user not found. Run WholesalerUserSeeder first.');
            return;
        }

        $brands = [
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'BulkTech Pro',
                'slug' => 'bulktech-pro',
                'description' => 'Professional bulk technology solutions for businesses',
                'logo' => 'https://picsum.photos/seed/bulktech/400/400',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'MegaSupply Co',
                'slug' => 'megasupply-co',
                'description' => 'Your trusted partner for wholesale supplies',
                'logo' => 'https://picsum.photos/seed/megasupply/400/400',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'GlobalTrade Brands',
                'slug' => 'globaltrade-brands',
                'description' => 'International wholesale brand distributor',
                'logo' => 'https://picsum.photos/seed/globaltrade/400/400',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'ValueBulk',
                'slug' => 'valuebulk',
                'description' => 'Quality products at wholesale prices',
                'logo' => 'https://picsum.photos/seed/valuebulk/400/400',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'TradeGiant',
                'slug' => 'tradegiant',
                'description' => 'Giant selections for trade professionals',
                'logo' => 'https://picsum.photos/seed/tradegiant/400/400',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('Wholesaler brands seeded successfully!');
    }
}
