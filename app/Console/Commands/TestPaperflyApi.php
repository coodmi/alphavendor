<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaperflyService;

class TestPaperflyApi extends Command
{
    protected $signature = 'paperfly:test';
    protected $description = 'Test Paperfly API integration';

    public function handle()
    {
        $this->info('Testing Paperfly API Integration...');
        $this->newLine();

        $paperflyService = new PaperflyService();

        // Check configuration
        $this->info('Configuration Check:');
        $this->line('Base URL: ' . config('services.paperfly.base_url'));
        $this->line('Username: ' . (config('services.paperfly.username') ? '✓ Set' : '✗ Not set'));
        $this->line('Password: ' . (config('services.paperfly.password') ? '✓ Set' : '✗ Not set'));
        $this->line('API Key: ' . (config('services.paperfly.key') ? '✓ Set' : '✗ Not set'));
        $this->newLine();

        // Test order creation (dry run)
        $this->info('Testing Order Creation (Sample Data)...');
        $testOrderData = [
            'order_number' => 'TEST_' . time(),
            'store_name' => 'Test Store',
            'product_brief' => 'Test Product',
            'package_price' => '100',
            'max_weight' => '0.3',
            'customer_name' => 'Test Customer',
            'customer_address' => 'Test Address, Dhaka',
            'customer_phone' => '01234567890',
        ];

        $this->line('Order Data:');
        $this->line(json_encode($testOrderData, JSON_PRETTY_PRINT));
        $this->newLine();

        if ($this->confirm('Do you want to create a real test order?', false)) {
            $result = $paperflyService->createOrder($testOrderData);
            
            if ($result['success']) {
                $this->info('✓ Order created successfully!');
                $this->line('Tracking Number: ' . $result['tracking_number']);
                $this->line('Tracking Barcode: ' . $result['tracking_barcode']);
            } else {
                $this->error('✗ Order creation failed!');
                $this->line('Error: ' . $result['message']);
            }
        } else {
            $this->warn('Skipped real API call.');
        }

        $this->newLine();
        $this->info('Test completed!');
    }
}
