<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip CSP in development to allow Vite dev server (IPv6 addresses not supported in CSP)
        if (! app()->environment('local', 'development')) {
            // Production: Strict CSP
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; ".
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; ".
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net; ".
                "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:; ".
                "img-src 'self' data: https:; ".
                "connect-src 'self';"
            );
        }

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=()'
        );

        // HSTS (HTTPS Strict Transport Security) - only in production
        if (app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
