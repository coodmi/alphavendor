<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class WholesalerCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Get all wholesaler users
        $wholesalers = User::where('role', 'wholesaler')->where('status', 'active')->get();

        if ($wholesalers->isEmpty()) {
            $this->command->warn('No wholesaler users found. Run WholesalerUserSeeder first.');
            return;
        }

        $categoryTemplates = [
            [
                'name' => 'Bulk Electronics',
                'slug' => 'bulk-electronics',
                'description' => 'Wholesale electronic products and components',
                'image' => 'https://picsum.photos/seed/welectronics/800/600',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Industrial Supplies',
                'slug' => 'industrial-supplies',
                'description' => 'Industrial equipment and supplies in bulk',
                'image' => 'https://picsum.photos/seed/industrial/800/600',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Office Bulk Supplies',
                'slug' => 'office-bulk-supplies',
                'description' => 'Office supplies and equipment wholesale',
                'image' => 'https://picsum.photos/seed/woffice/800/600',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Wholesale Home Goods',
                'slug' => 'wholesale-home-goods',
                'description' => 'Home goods and furnishings in bulk quantities',
                'image' => 'https://picsum.photos/seed/whomegoods/800/600',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Bulk Clothing & Textiles',
                'slug' => 'bulk-clothing-textiles',
                'description' => 'Clothing and textile products wholesale',
                'image' => 'https://picsum.photos/seed/wclothing/800/600',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Food & Beverage Bulk',
                'slug' => 'food-beverage-bulk',
                'description' => 'Food and beverage products in bulk',
                'image' => 'https://picsum.photos/seed/wfood/800/600',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($wholesalers as $wholesaler) {
            foreach ($categoryTemplates as $template) {
                // Make slug and name unique per vendor
                $uniqueSlug = $template['slug'] . '-' . $wholesaler->id;
                $uniqueName = $template['name'] . ' (W' . $wholesaler->id . ')';

                Category::create([
                    'vendor_id' => $wholesaler->id,
                    'name' => $uniqueName,
                    'slug' => $uniqueSlug,
                    'description' => $template['description'],
                    'image' => $template['image'],
                    'is_active' => $template['is_active'],
                    'sort_order' => $template['sort_order'],
                ]);
            }
            $this->command->info("Categories seeded for wholesaler: {$wholesaler->email}");
        }

        $this->command->info('All wholesaler categories seeded successfully!');
    }
}
