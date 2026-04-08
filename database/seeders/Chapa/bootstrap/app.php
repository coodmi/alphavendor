<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            SecurityHeaders::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle DomainException with proper responses
        $exceptions->renderable(function (\App\Exceptions\DomainException $e, $request) {
            // Log the exception with structured context
            if ($e->shouldReport()) {
                \Illuminate\Support\Facades\Log::error($e->getMessage(), [
                    'exception' => get_class($e),
                    'status_code' => $e->getStatusCode(),
                    'context' => $e->getContext(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            // Return JSON response for API requests
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json($e->toArray(), $e->getStatusCode());
            }

            // Return web response with flash message
            return back()
                ->withInput()
                ->with('error', $e->getUserMessage());
        });

        // Handle validation exceptions
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'ValidationException',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Handle model not found exceptions
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'ModelNotFoundException',
                    'message' => 'Resource not found.',
                ], 404);
            }

            return back()->with('error', 'The requested resource was not found.');
        });

        // Handle authentication exceptions
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'AuthenticationException',
                    'message' => 'Unauthenticated.',
                ], 401);
            }
        });

        // Handle authorization exceptions
        $exceptions->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'AuthorizationException',
                    'message' => 'This action is unauthorized.',
                ], 403);
            }

            return back()->with('error', 'You are not authorized to perform this action.');
        });

        // Handle throttle exceptions (rate limiting)
        $exceptions->renderable(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'ThrottleRequestsException',
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
                ], 429);
            }

            return back()->with('error', 'Too many requests. Please try again later.');
        });
    })->create();
