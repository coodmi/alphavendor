<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionSetting;
use App\Models\CodCommissionSetting;
use App\Models\Category;

class CommissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create default COD commission setting
        CodCommissionSetting::create([
            'commission_rate' => 2.00, // 2% COD commission
            'is_active' => true
        ]);

        // Get all admin categories
        $categories = Category::whereNull('vendor_id')->get();

        // Set default commission rates for each category
        foreach ($categories as $category) {
            // Retailer commission
            CommissionSetting::create([
                'category_id' => $category->id,
                'seller_type' => 'retailer',
                'commission_rate' => 8.00, // 8% for retailers
                'is_active' => true
            ]);

            // Wholesaler commission
            CommissionSetting::create([
                'category_id' => $category->id,
                'seller_type' => 'wholesaler',
                'commission_rate' => 5.00, // 5% for wholesalers
                'is_active' => true
            ]);

            // Importer commission
            CommissionSetting::create([
                'category_id' => $category->id,
                'seller_type' => 'importer',
                'commission_rate' => 6.00, // 6% for importers
                'is_active' => true
            ]);
        }
    }
}
