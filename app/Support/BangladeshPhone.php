<?php

namespace App\Support;

class BangladeshPhone
{
    /**
     * Store/compare as +8801XXXXXXXXX
     */
    public static function normalize(string $number): string
    {
        $number = trim($number);
        $digits = preg_replace('/[^0-9]/', '', $number);

        if (strlen($digits) === 13 && str_starts_with($digits, '880')) {
            return '+'.$digits;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return '+880'.substr($digits, 1);
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '1')) {
            return '+880'.$digits;
        }

        if (str_starts_with($number, '+880')) {
            return $number;
        }

        return '+880'.ltrim($digits, '0');
    }

    /**
     * MiMSMS API expects 8801XXXXXXXXX (digits only, no +).
     */
    public static function forMimSms(string $number): string
    {
        $digits = preg_replace('/[^0-9]/', '', self::normalize($number));

        if (str_starts_with($digits, '880')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '880'.substr($digits, 1);
        }

        return '880'.$digits;
    }
}
