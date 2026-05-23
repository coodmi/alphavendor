<?php

namespace App\Services;

use App\Models\Setting;

class OtpMessageBuilder
{
    public const DEFAULT_TEMPLATE = <<<'TEXT'
Welcome to AR Market BD ! Your verification code : {otp}
Valid for 15 minutes.
For security, do not share this code.
TEXT;

    public static function getTemplate(): string
    {
        $fromDb = Setting::getValue('OTP_TEMPLATE');
        if (is_string($fromDb) && trim($fromDb) !== '') {
            return $fromDb;
        }

        $fromEnv = env('OTP_SMS_TEMPLATE');
        if (is_string($fromEnv) && trim($fromEnv) !== '') {
            return $fromEnv;
        }

        return self::DEFAULT_TEMPLATE;
    }

    public static function expiryMinutes(): int
    {
        $fromDb = Setting::getValue('OTP_EXPIRY');
        if ($fromDb !== null && $fromDb !== '') {
            return max(1, (int) $fromDb);
        }

        return max(1, (int) env('OTP_EXPIRY_MINUTES', 15));
    }

    public static function build(string $otp, ?int $expiryMinutes = null): string
    {
        $expiry = $expiryMinutes ?? self::expiryMinutes();

        return str_replace(
            ['{otp}', '{{otp}}', '{expiry}', '{{expiry}}'],
            [$otp, $otp, (string) $expiry, (string) $expiry],
            self::getTemplate()
        );
    }

    public static function persistTemplate(string $template, ?int $expiryMinutes = null): void
    {
        Setting::setValue('OTP_TEMPLATE', $template);

        if ($expiryMinutes !== null) {
            Setting::setValue('OTP_EXPIRY', $expiryMinutes);
        }
    }
}
