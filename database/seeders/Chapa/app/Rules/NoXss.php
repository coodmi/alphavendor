<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * No XSS Rule.
 *
 * Validates that input doesn't contain XSS attack patterns.
 */
class NoXss implements ValidationRule
{
    /**
     * XSS patterns to check for.
     *
     * @var array<string>
     */
    protected array $patterns = [
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
        '/<object\b[^>]*>(.*?)<\/object>/is',
        '/<embed\b[^>]*>/is',
        '/<applet\b[^>]*>(.*?)<\/applet>/is',
        '/on\w+\s*=\s*["\']?[^"\']*["\']?/is', // Event handlers like onclick, onload
        '/javascript:/is',
        '/vbscript:/is',
        '/data:text\/html/is',
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail('The :attribute contains potentially dangerous content.');

                return;
            }
        }
    }
}
