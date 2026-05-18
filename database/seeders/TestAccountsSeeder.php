<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates test accounts with @test.com domain for easy testing
     */
    public function run(): void
    {
        $hashedPassword = Hash::make('password123');
        $now = now();

        // Check if accounts already exist, if not create them
        $testAccounts = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => $hashedPassword,
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Test Retailer',
                'email' => 'retailer@test.com',
                'password' => $hashedPassword,
                'role' => 'retailer',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Test Wholesaler',
                'email' => 'wholesaler@test.com',
                'password' => $hashedPassword,
                'role' => 'wholesaler',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Test Exporter',
                'email' => 'exporter@test.com',
                'password' => $hashedPassword,
                'role' => 'exporter',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Test Importer',
                'email' => 'importer@test.com',
                'password' => $hashedPassword,
                'role' => 'importer',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Test Customer',
                'email' => 'user@test.com',
                'password' => $hashedPassword,
                'role' => 'customer',
                'status' => 'active',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($testAccounts as $account) {
            // Check if user already exists
            $existingUser = User::where('email', $account['email'])->first();
            
            if (!$existingUser) {
                User::create($account);
                $this->command->info("Created test account: {$account['email']}");
            } else {
                // Update password if user exists
                $existingUser->update([
                    'password' => $hashedPassword,
                    'status' => 'active',
                ]);
                $this->command->info("Updated test account: {$account['email']}");
            }
        }

        $this->command->info('Test accounts seeded/updated successfully!');
        $this->command->info('All test accounts use password: password123');
    }
}
