<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample employee users (moderators)
        User::create([
            'name' => 'Jane Employee',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Mike Moderator',
            'email' => 'mike.moderator@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sarah Staff',
            'email' => 'sarah.staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}