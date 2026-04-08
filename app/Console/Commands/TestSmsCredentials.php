<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestSmsCredentials extends Command
{
    protected $signature = 'sms:test-credentials {phone?}';
    protected $description = 'Test MiMSMS credentials and send a test SMS';

    public function handle()
    {
        $phone = $this->argument('phone') ?? '+8801234567890';
        
        $this->info('Testing MiMSMS Credentials...');
        $this->line('');
        
        // Check if credentials are set
        $apiKey = env('MIMSMS_APIKEY');
        $username = env('MIMSMS_USERNAME');
        
        if (!$apiKey || !$username) {
            $this->error('❌ MiMSMS credentials not found in .env file');
            $this->line('Please set: MIMSMS_APIKEY, MIMSMS_USERNAME');
            return 1;
        }
        
        if (str_contains($apiKey, 'your_') || str_contains($username, 'your_')) {
            $this->error('❌ MiMSMS credentials appear to be placeholder values');
            $this->line('Please update your .env file with real credentials from MiMSMS dashboard');
            return 1;
        }
        
        $this->info('✅ Credentials found in .env file');
        $this->line('');
        
        // Test API call
        $this->info('Testing API connection...');
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
                'ApiKey' => $apiKey,
                'MobileNumber' => str_replace('+', '', $phone), // Remove + for 880 format
                'SenderName' => env('MIMSMS_SENDER_NAME', 'iSMS'),
                'CampaignName' => '',
                'UserName' => $username,
                'TransactionType' => 'T',
                'MessageId' => '',
                'Message' => 'Test SMS from Laravel OTP System - ' . now()->format('Y-m-d H:i:s'),
                'Telco' => ''
            ]);
            
            $this->line('Status Code: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                $this->line('Response: ' . json_encode($data, JSON_PRETTY_PRINT));
                
                if (isset($data['statusCode']) && $data['statusCode'] == '200') {
                    $this->info('✅ SMS sent successfully!');
                    $this->line('Transaction ID: ' . ($data['trxnId'] ?? 'N/A'));
                    $this->line('Check your phone: ' . $phone);
                } else {
                    $this->error('❌ SMS failed: ' . ($data['responseResult'] ?? 'Unknown error'));
                    $this->line('Status Code: ' . ($data['statusCode'] ?? 'Unknown'));
                }
            } else {
                $this->error('❌ API request failed');
                $this->line('Response: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            return 1;
        }
        
        $this->line('');
        $this->info('Test completed!');
        
        return 0;
    }
}