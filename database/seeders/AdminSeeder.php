<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@alphavendor.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create sample users
        User::create([
            'name' => 'John Retailer',
            'email' => 'retailer@example.com',
            'password' => Hash::make('password'),
            'role' => 'retailer',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Jane Wholesaler',
            'email' => 'wholesaler@example.com',
            'password' => Hash::make('password'),
            'role' => 'wholesaler',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Mike Exporter',
            'email' => 'exporter@example.com',
            'password' => Hash::make('password'),
            'role' => 'exporter',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
        ]);
    }
}
