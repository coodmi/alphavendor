<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class WholesalerCategorySeeder extends Seeder
{
    public function run(): void
    {
        $wholesaler = User::where('email', 'wholesaler@vendor.com')->first();
        
        if (!$wholesaler) {
            $this->command->warn('Wholesaler user not found. Run WholesalerUserSeeder first.');
            return;
        }

        $categories = [
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'Bulk Electronics',
                'slug' => 'bulk-electronics',
                'description' => 'Wholesale electronic products and components',
                'image' => 'https://picsum.photos/seed/welectronics/800/600',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'Industrial Supplies',
                'slug' => 'industrial-supplies',
                'description' => 'Industrial equipment and supplies in bulk',
                'image' => 'https://picsum.photos/seed/industrial/800/600',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'Office Bulk Supplies',
                'slug' => 'office-bulk-supplies',
                'description' => 'Office supplies and equipment wholesale',
                'image' => 'https://picsum.photos/seed/woffice/800/600',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'Wholesale Home Goods',
                'slug' => 'wholesale-home-goods',
                'description' => 'Home goods and furnishings in bulk quantities',
                'image' => 'https://picsum.photos/seed/whomegoods/800/600',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'Bulk Clothing & Textiles',
                'slug' => 'bulk-clothing-textiles',
                'description' => 'Clothing and textile products wholesale',
                'image' => 'https://picsum.photos/seed/wclothing/800/600',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'vendor_id' => $wholesaler->id,
                'name' => 'Food & Beverage Bulk',
                'slug' => 'food-beverage-bulk',
                'description' => 'Food and beverage products in bulk',
                'image' => 'https://picsum.photos/seed/wfood/800/600',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Wholesaler categories seeded successfully!');
    }
}
