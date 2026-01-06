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
        $hashedPassword = Hash::make('password');
        $now = now();
        
        User::insert([
            [
                'name' => 'Fashion Empire Store',
                'email' => 'retailer@vendor.com',
                'password' => $hashedPassword,
                'role' => 'retailer',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tech Electronics Hub',
                'email' => 'wholesaler@vendor.com',
                'password' => $hashedPassword,
                'role' => 'wholesaler',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Global Exports Inc',
                'email' => 'exporter@vendor.com',
                'password' => $hashedPassword,
                'role' => 'exporter',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Beauty Paradise',
                'email' => 'retailer2@vendor.com',
                'password' => $hashedPassword,
                'role' => 'retailer',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sports World',
                'email' => 'wholesaler2@vendor.com',
                'password' => $hashedPassword,
                'role' => 'wholesaler',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->command->info('Vendors seeded successfully!');
    }
}
