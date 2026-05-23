<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class OtpMessageBuilder
{
    public const DEFAULT_TEMPLATE = <<<'TEXT'
Welcome to AR Market BD ! Your verification code : {otp}
Valid for 15 minutes.
For security, do not share this code.
TEXT;

    public static function getTemplate(): string
    {
        foreach (self::templateCandidates() as $template) {
            if ($template !== null && trim($template) !== '' && ! self::isLegacyTemplate($template)) {
                return $template;
            }
        }

        return self::DEFAULT_TEMPLATE;
    }

    public static function expiryMinutes(): int
    {
        $fromDb = Setting::getValue('OTP_EXPIRY');
        if ($fromDb !== null && $fromDb !== '') {
            return max(1, (int) $fromDb);
        }

        return max(1, (int) config('sms.otp_expiry_minutes', 15));
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
        Cache::forget('setting_OTP_TEMPLATE');

        if ($expiryMinutes !== null) {
            Setting::setValue('OTP_EXPIRY', (string) $expiryMinutes);
            Cache::forget('setting_OTP_EXPIRY');
        }
    }

    /**
     * Force AR Market BD template in DB (run on deploy / admin save).
     */
    public static function ensureArMarketTemplateInstalled(): void
    {
        $current = Setting::getValue('OTP_TEMPLATE');
        if ($current === null || $current === '' || self::isLegacyTemplate($current)) {
            self::persistTemplate(self::DEFAULT_TEMPLATE, self::expiryMinutes());
        }
    }

    public static function isLegacyTemplate(string $template): bool
    {
        $normalized = strtolower(trim($template));

        if (str_contains($normalized, 'welcome to ar market bd')) {
            return false;
        }

        return (bool) preg_match(
            '/your\s+otp(\s+code)?\s+is\s*:/i',
            $template
        );
    }

    /**
     * @return array<int, string|null>
     */
    private static function templateCandidates(): array
    {
        return [
            Setting::getValue('OTP_TEMPLATE'),
            config('sms.otp_template'),
            env('OTP_SMS_TEMPLATE'),
        ];
    }
}
