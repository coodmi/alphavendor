<?php

namespace App\Support;

class SeoKeywords
{
    public static function count(?string $keywords): int
    {
        if ($keywords === null || trim($keywords) === '') {
            return 0;
        }

        return count(array_filter(array_map('trim', explode(',', $keywords))));
    }

    public static function validate(?string $keywords, int $minimum = 5, bool $required = false): ?string
    {
        if ($keywords === null || trim($keywords) === '') {
            return $required ? "At least {$minimum} keywords are required (comma separated)." : null;
        }

        if (self::count($keywords) < $minimum) {
            return "Please provide at least {$minimum} keywords separated by commas.";
        }

        return null;
    }
}
