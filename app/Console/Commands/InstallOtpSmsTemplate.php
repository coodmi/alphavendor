<?php

namespace App\Console\Commands;

use App\Services\OtpMessageBuilder;
use Illuminate\Console\Command;

class InstallOtpSmsTemplate extends Command
{
    protected $signature = 'otp:install-template';

    protected $description = 'Install AR Market BD OTP SMS template in database (fixes legacy "Your OTP is:" text)';

    public function handle(): int
    {
        OtpMessageBuilder::persistTemplate(
            OtpMessageBuilder::DEFAULT_TEMPLATE,
            OtpMessageBuilder::expiryMinutes()
        );

        $this->info('OTP template updated.');
        $this->line(OtpMessageBuilder::build('123456'));

        return self::SUCCESS;
    }
}
