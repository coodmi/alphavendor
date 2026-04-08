<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

/**
 * Rate Limit Service Provider.
 *
 * Configures rate limiting for API and authentication routes.
 */
class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // API rate limiting - 60 requests per minute per user/IP
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            )->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please try again later.',
                ], 429);
            });
        });

        // Authentication rate limiting - 5 attempts per minute per IP
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many login attempts. Please try again later.',
                ], 429);
            });
        });

        // Strict API rate limiting for sensitive operations - 10 per minute
        RateLimiter::for('api-strict', function (Request $request) {
            return Limit::perMinute(10)->by(
                $request->user()?->id ?: $request->ip()
            )->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded for this operation.',
                ], 429);
            });
        });

        // Guest rate limiting - 30 requests per minute per IP
        RateLimiter::for('guest', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please try again later.',
                ], 429);
            });
        });

        // Search rate limiting - 20 requests per minute
        RateLimiter::for('search', function (Request $request) {
            return Limit::perMinute(20)->by(
                $request->user()?->id ?: $request->ip()
            )->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many search requests. Please try again later.',
                ], 429);
            });
        });
    }
}
