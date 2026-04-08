<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class UpdateOtpExpiry extends Command
{
    protected $signature = 'otp:set-expiry {minutes : OTP expiry time in minutes}';
    protected $description = 'Update OTP expiry time';

    public function handle()
    {
        $minutes = $this->argument('minutes');
        
        if (!is_numeric($minutes) || $minutes < 1) {
            $this->error('❌ Please provide a valid number of minutes (minimum 1)');
            return 1;
        }
        
        if ($minutes > 60) {
            $this->warn('⚠️  Setting OTP expiry to more than 60 minutes is not recommended for security');
            if (!$this->confirm('Do you want to continue?')) {
                return 0;
            }
        }
        
        // Update database setting
        Setting::setValue('OTP_EXPIRY', $minutes);
        
        // Update .env file
        $this->updateEnvFile('OTP_EXPIRY_MINUTES', $minutes);
        
        // Clear config cache
        $this->call('config:clear');
        
        $this->info("✅ OTP expiry time updated to {$minutes} minutes");
        $this->line("📱 New OTPs will expire after {$minutes} minutes");
        
        return 0;
    }
    
    private function updateEnvFile($key, $value)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";
        
        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n{$replacement}";
        }
        
        file_put_contents($envFile, $envContent);
    }
}