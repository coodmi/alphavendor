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
        $hashedPassword = Hash::make('password');
        
        User::insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@alphavendor.com',
                'password' => $hashedPassword,
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Retailer',
                'email' => 'retailer@example.com',
                'password' => $hashedPassword,
                'role' => 'retailer',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Wholesaler',
                'email' => 'wholesaler@example.com',
                'password' => $hashedPassword,
                'role' => 'wholesaler',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mike Exporter',
                'email' => 'exporter@example.com',
                'password' => $hashedPassword,
                'role' => 'exporter',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => $hashedPassword,
                'role' => 'user',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
