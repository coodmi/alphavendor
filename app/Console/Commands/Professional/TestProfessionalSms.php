<?php

namespace App\Console\Commands\Professional;

use Illuminate\Console\Command;
use App\Services\Professional\ProfessionalSmsService;

/**
 * Test Professional SMS Service Command
 * 
 * Command to test the professional SMS service functionality
 * 
 * @package App\Console\Commands\Professional
 * @author Your Name
 * @version 1.0.0
 */
class TestProfessionalSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test-professional 
                            {phone? : Phone number to test with}
                            {--type=notification : Message type}
                            {--otp : Test OTP functionality}
                            {--verify=* : Verify OTP with code}
                            {--stats : Show statistics}
                            {--health : Show health status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the professional SMS service with comprehensive features';

    private ProfessionalSmsService $smsService;

    /**
     * Create a new command instance.
     */
    public function __construct(ProfessionalSmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Professional SMS Service Test Suite');
        $this->info(str_repeat('=', 60));

        // Show health status if requested
        if ($this->option('health')) {
            $this->showHealthStatus();
            return 0;
        }

        // Show statistics if requested
        if ($this->option('stats')) {
            $this->showStatistics();
            return 0;
        }

        $phone = $this->argument('phone') ?? $this->ask('Enter phone number (880xxxxxxxxxx)');
        
        if (!$phone) {
            $this->error('Phone number is required');
            return 1;
        }

        // Validate phone format
        if (!preg_match('/^880[0-9]{10}$/', $phone)) {
            $this->error('Invalid phone number format. Use: 880xxxxxxxxxx');
            return 1;
        }

        // Test OTP functionality
        if ($this->option('otp')) {
            return $this->testOtpFunctionality($phone);
        }

        // Verify OTP if code provided
        if ($this->option('verify')) {
            return $this->verifyOtp($phone, $this->option('verify')[0]);
        }

        // Test regular SMS
        return $this->testRegularSms($phone);
    }

    /**
     * Test regular SMS functionality
     */
    private function testRegularSms(string $phone): int
    {
        $this->info("\n📱 Testing Regular SMS");
        $this->info(str_repeat('-', 40));

        $type = $this->option('type');
        $message = "Professional SMS Test - " . now()->format('Y-m-d H:i:s') . " - Type: {$type}";

        $this->info("Phone: {$phone}");
        $this->info("Type: {$type}");
        $this->info("Message: {$message}");

        $bar = $this->output->createProgressBar(1);
        $bar->start();

        try {
            $response = $this->smsService->send($phone, $message, $type);
            $bar->advance();
            $bar->finish();

            $this->newLine(2);

            if ($response['success']) {
                $this->info('✅ SMS sent successfully!');
                $this->table(['Field', 'Value'], [
                    ['Transaction ID', $response['transaction_id'] ?? 'N/A'],
                    ['SMS Log ID', $response['sms_log_id'] ?? 'N/A'],
                    ['Cost', '$' . number_format($response['cost'] ?? 0, 4)],
                    ['Provider', $response['provider'] ?? 'N/A']
                ]);
                return 0;
            } else {
                $this->error('❌ SMS sending failed!');
                $this->error('Error: ' . $response['error']);
                $this->error('Error Code: ' . ($response['error_code'] ?? 'N/A'));
                return 1;
            }

        } catch (\Exception $e) {
            $bar->finish();
            $this->newLine(2);
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Test OTP functionality
     */
    private function testOtpFunctionality(string $phone): int
    {
        $this->info("\n🔐 Testing OTP Functionality");
        $this->info(str_repeat('-', 40));

        $purpose = $this->choice('Select OTP purpose:', [
            'registration',
            'login', 
            'password_reset',
            'phone_verification',
            'transaction'
        ], 'phone_verification');

        $this->info("Phone: {$phone}");
        $this->info("Purpose: {$purpose}");

        $bar = $this->output->createProgressBar(1);
        $bar->start();

        try {
            $response = $this->smsService->sendOtp($phone, $purpose);
            $bar->advance();
            $bar->finish();

            $this->newLine(2);

            if ($response['success']) {
                $this->info('✅ OTP sent successfully!');
                $this->table(['Field', 'Value'], [
                    ['OTP ID', $response['otp_id']],
                    ['Expires In', $response['expires_in'] . ' seconds'],
                    ['Expires At', $response['expires_at']],
                    ['Transaction ID', $response['transaction_id'] ?? 'N/A']
                ]);

                // Ask if user wants to verify immediately
                if ($this->confirm('Do you want to verify the OTP now?')) {
                    $otpCode = $this->ask('Enter the OTP code you received:');
                    if ($otpCode) {
                        return $this->verifyOtp($phone, $otpCode, $purpose);
                    }
                }

                return 0;
            } else {
                $this->error('❌ OTP sending failed!');
                $this->error('Error: ' . $response['error']);
                $this->error('Error Code: ' . ($response['error_code'] ?? 'N/A'));
                return 1;
            }

        } catch (\Exception $e) {
            $bar->finish();
            $this->newLine(2);
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Verify OTP code
     */
    private function verifyOtp(string $phone, string $otpCode, string $purpose = 'phone_verification'): int
    {
        $this->info("\n🔍 Verifying OTP");
        $this->info(str_repeat('-', 40));

        $this->info("Phone: {$phone}");
        $this->info("OTP Code: {$otpCode}");
        $this->info("Purpose: {$purpose}");

        try {
            $response = $this->smsService->verifyOtp($phone, $otpCode, $purpose);

            if ($response['success']) {
                $this->info('✅ OTP verified successfully!');
                $this->table(['Field', 'Value'], [
                    ['OTP ID', $response['otp_id']],
                    ['Verified At', $response['verified_at']]
                ]);
                return 0;
            } else {
                $this->error('❌ OTP verification failed!');
                $this->error('Message: ' . $response['message']);
                $this->error('Error Code: ' . ($response['error_code'] ?? 'N/A'));
                
                if (isset($response['attempts_remaining'])) {
                    $this->warn('Attempts remaining: ' . $response['attempts_remaining']);
                }
                
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Show service statistics
     */
    private function showStatistics(): void
    {
        $this->info("\n📊 SMS Service Statistics");
        $this->info(str_repeat('-', 40));

        try {
            $stats = $this->smsService->getStatistics();

            $this->table(['Metric', 'Value'], [
                ['Total Sent', number_format($stats['total_sent'])],
                ['Sent Today', number_format($stats['sent_today'])],
                ['Sent This Month', number_format($stats['sent_this_month'])],
                ['Success Rate', $stats['success_rate'] . '%'],
                ['Failed Today', number_format($stats['failed_today'])],
                ['Total Cost', '$' . number_format($stats['total_cost'], 2)],
                ['Cost Today', '$' . number_format($stats['cost_today'], 2)],
            ]);

            $this->info("\n🔐 OTP Statistics:");
            $this->table(['Metric', 'Value'], [
                ['Total OTP Sent', number_format($stats['otp_stats']['total_sent'])],
                ['Total Verified', number_format($stats['otp_stats']['total_verified'])],
                ['Verification Rate', $stats['otp_stats']['verification_rate'] . '%'],
            ]);

            $this->info("\n🏥 Provider Status:");
            $providerHealth = $stats['provider_stats']['health_status'];
            $status = $providerHealth['healthy'] ? '✅ Healthy' : '❌ Unhealthy';
            $this->info("Current Provider: " . $stats['provider_stats']['current_provider']);
            $this->info("Health Status: " . $status);

            $this->info("\nLast Updated: " . $stats['last_updated']);

        } catch (\Exception $e) {
            $this->error('❌ Failed to retrieve statistics: ' . $e->getMessage());
        }
    }

    /**
     * Show health status
     */
    private function showHealthStatus(): void
    {
        $this->info("\n🏥 SMS Service Health Check");
        $this->info(str_repeat('-', 40));

        try {
            $health = $this->smsService->healthCheck();

            $overallStatus = $health['healthy'] ? '✅ Healthy' : '❌ Unhealthy';
            $this->info("Overall Status: {$overallStatus}");
            $this->info("Service Status: " . ucfirst($health['status']));

            $this->info("\n📡 Provider Health:");
            $provider = $health['provider'];
            $providerStatus = $provider['healthy'] ? '✅ Healthy' : '❌ Unhealthy';
            $this->info("Status: {$providerStatus}");
            
            if (isset($provider['response_time'])) {
                $this->info("Response Time: " . round($provider['response_time'] * 1000, 2) . "ms");
            }
            
            if (isset($provider['error'])) {
                $this->error("Error: " . $provider['error']);
            }

            $this->info("\n🗄️ Database Health:");
            $database = $health['database'];
            $dbStatus = $database['healthy'] ? '✅ Healthy' : '❌ Unhealthy';
            $this->info("Status: {$dbStatus}");
            
            if (isset($database['error'])) {
                $this->error("Error: " . $database['error']);
            }

            $this->info("\nChecked At: " . $health['checked_at']);

        } catch (\Exception $e) {
            $this->error('❌ Health check failed: ' . $e->getMessage());
        }
    }
}