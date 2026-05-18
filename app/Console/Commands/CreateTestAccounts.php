<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test accounts for easy testing (password: password123)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test accounts...');
        
        $hashedPassword = Hash::make('password123');
        $now = now();

        $testAccounts = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => $hashedPassword,
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => $now,
            ],
            [
                'name' => 'Test Retailer',
                'email' => 'retailer@test.com',
                'password' => $hashedPassword,
                'role' => 'retailer',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Test Wholesaler',
                'email' => 'wholesaler@test.com',
                'password' => $hashedPassword,
                'role' => 'wholesaler',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Test Exporter',
                'email' => 'exporter@test.com',
                'password' => $hashedPassword,
                'role' => 'exporter',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Test Importer',
                'email' => 'importer@test.com',
                'password' => $hashedPassword,
                'role' => 'importer',
                'status' => 'active',
                'email_verified_at' => $now,
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Test Customer',
                'email' => 'user@test.com',
                'password' => $hashedPassword,
                'role' => 'customer',
                'status' => 'active',
                'email_verified_at' => $now,
            ],
        ];

        foreach ($testAccounts as $account) {
            $existingUser = User::where('email', $account['email'])->first();
            
            if (!$existingUser) {
                User::create($account);
                $this->info("✓ Created: {$account['email']} ({$account['role']})");
            } else {
                $existingUser->update([
                    'password' => $hashedPassword,
                    'status' => 'active',
                    'email_verified_at' => $now,
                ]);
                $this->info("✓ Updated: {$account['email']} ({$account['role']})");
            }
        }

        $this->newLine();
        $this->info('✅ Test accounts created/updated successfully!');
        $this->newLine();
        $this->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@test.com', 'password123', 'Admin'],
                ['retailer@test.com', 'password123', 'Retailer'],
                ['wholesaler@test.com', 'password123', 'Wholesaler'],
                ['exporter@test.com', 'password123', 'Exporter'],
                ['importer@test.com', 'password123', 'Importer'],
                ['user@test.com', 'password123', 'Customer'],
            ]
        );
        
        return Command::SUCCESS;
    }
}
