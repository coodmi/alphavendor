<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sanitize Input Middleware.
 *
 * Sanitizes user input to prevent XSS attacks and other security issues.
 */
class SanitizeInput
{
    /**
     * Fields that should not be sanitized.
     *
     * @var array<string>
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (! in_array($key, $this->except) && is_string($value)) {
                $value = $this->sanitize($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }

    /**
     * Sanitize a string value.
     */
    protected function sanitize(string $value): string
    {
        // Remove null bytes
        $value = str_replace(chr(0), '', $value);

        // Trim whitespace
        $value = trim($value);

        // Strip tags (except allowed ones)
        $value = strip_tags($value, '<p><br><strong><em><ul><ol><li><a>');

        // Convert special characters to HTML entities
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

        return $value;
    }
}
