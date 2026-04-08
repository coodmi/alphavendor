<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * No SQL Injection Rule.
 *
 * Validates that input doesn't contain common SQL injection patterns.
 */
class NoSqlInjection implements ValidationRule
{
    /**
     * SQL injection patterns to check for.
     *
     * @var array<string>
     */
    protected array $patterns = [
        '/(\bUNION\b.*\bSELECT\b)/i',
        '/(\bSELECT\b.*\bFROM\b)/i',
        '/(\bINSERT\b.*\bINTO\b)/i',
        '/(\bUPDATE\b.*\bSET\b)/i',
        '/(\bDELETE\b.*\bFROM\b)/i',
        '/(\bDROP\b.*\bTABLE\b)/i',
        '/(\bCREATE\b.*\bTABLE\b)/i',
        '/(\bALTER\b.*\bTABLE\b)/i',
        '/(\bEXEC\b|\bEXECUTE\b)/i',
        '/(--|\#|\/\*|\*\/)/i',
        '/(\bOR\b.*=.*)/i',
        '/(\bAND\b.*=.*)/i',
        '/(\'|\")(\s)*(OR|AND)(\s)*(\d+)(\s)*=(\s)*(\d+)/i',
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
                $fail('The :attribute contains invalid characters or patterns.');

                return;
            }
        }
    }
}
