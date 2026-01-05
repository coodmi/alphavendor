<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'Fashion Empire Store',
                'email' => 'retailer@vendor.com',
                'password' => Hash::make('password'),
                'role' => 'retailer',
            ],
            [
                'name' => 'Tech Electronics Hub',
                'email' => 'wholesaler@vendor.com',
                'password' => Hash::make('password'),
                'role' => 'wholesaler',
            ],
            [
                'name' => 'Global Exports Inc',
                'email' => 'exporter@vendor.com',
                'password' => Hash::make('password'),
                'role' => 'exporter',
            ],
            [
                'name' => 'Beauty Paradise',
                'email' => 'retailer2@vendor.com',
                'password' => Hash::make('password'),
                'role' => 'retailer',
            ],
            [
                'name' => 'Sports World',
                'email' => 'wholesaler2@vendor.com',
                'password' => Hash::make('password'),
                'role' => 'wholesaler',
            ],
        ];

        foreach ($vendors as $vendorData) {
            User::firstOrCreate(
                ['email' => $vendorData['email']],
                $vendorData
            );
        }

        $this->command->info('Vendors seeded successfully!');
    }
}
