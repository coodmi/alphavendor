<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WholesalerUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Global Wholesale Supplies',
            'email' => 'wholesaler@example.com',
            'password' => Hash::make('password'),
            'role' => 'wholesaler',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Bulk Traders International',
            'email' => 'bulktraders@example.com',
            'password' => Hash::make('password'),
            'role' => 'wholesaler',
            'status' => 'active',
        ]);
    }
}
