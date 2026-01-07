<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommissionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\CommissionSetting::create([
            'type' => 'global',
            'category_id' => null,
            'product_id' => null,
            'commission_rate' => 10.00, // Default 10% commission
            'is_active' => true
        ]);
    }
}
