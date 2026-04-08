<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Logging service for consistent, structured logging across the application.
 *
 * Provides methods for logging with automatic context enrichment including
 * user information, request details, trace IDs, and performance metrics.
 */
class LoggingService
{
    /**
     * Current trace ID for request correlation.
     */
    protected ?string $traceId = null;

    /**
     * Create a new LoggingService instance.
     */
    public function __construct()
    {
        $this->traceId = $this->generateTraceId();
    }

    /**
     * Generate a unique trace ID for request correlation.
     */
    protected function generateTraceId(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Get the current trace ID.
     */
    public function getTraceId(): string
    {
        return $this->traceId ?? $this->generateTraceId();
    }

    /**
     * Set a custom trace ID.
     */
    public function setTraceId(string $traceId): self
    {
        $this->traceId = $traceId;

        return $this;
    }

    /**
     * Build context array with standard fields.
     *
     * @param  array<string, mixed>  $additionalContext
     * @return array<string, mixed>
     */
    protected function buildContext(array $additionalContext = []): array
    {
        $context = [
            'trace_id' => $this->getTraceId(),
            'timestamp' => now()->toIso8601String(),
            'environment' => config('app.env'),
        ];

        // Add request context if available
        if (app()->bound('request')) {
            $request = request();
            $context['request'] = [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Add route information if available
            if ($request->route()) {
                $context['request']['route'] = $request->route()->getName() ?? $request->route()->uri();
            }
        }

        // Add authenticated user info if available
        if (auth()->check()) {
            $context['user'] = [
                'id' => auth()->id(),
                'email' => auth()->user()->email ?? null,
            ];
        }

        // Merge with additional context
        return array_merge($context, $additionalContext);
    }

    /**
     * Log an emergency message.
     *
     * @param  array<string, mixed>  $context
     */
    public function emergency(string $message, array $context = []): void
    {
        Log::emergency($message, $this->buildContext($context));
    }

    /**
     * Log an alert message.
     *
     * @param  array<string, mixed>  $context
     */
    public function alert(string $message, array $context = []): void
    {
        Log::alert($message, $this->buildContext($context));
    }

    /**
     * Log a critical message.
     *
     * @param  array<string, mixed>  $context
     */
    public function critical(string $message, array $context = []): void
    {
        Log::critical($message, $this->buildContext($context));
    }

    /**
     * Log an error message.
     *
     * @param  array<string, mixed>  $context
     */
    public function error(string $message, array $context = []): void
    {
        Log::error($message, $this->buildContext($context));
    }

    /**
     * Log a warning message.
     *
     * @param  array<string, mixed>  $context
     */
    public function warning(string $message, array $context = []): void
    {
        Log::warning($message, $this->buildContext($context));
    }

    /**
     * Log a notice message.
     *
     * @param  array<string, mixed>  $context
     */
    public function notice(string $message, array $context = []): void
    {
        Log::notice($message, $this->buildContext($context));
    }

    /**
     * Log an info message.
     *
     * @param  array<string, mixed>  $context
     */
    public function info(string $message, array $context = []): void
    {
        Log::info($message, $this->buildContext($context));
    }

    /**
     * Log a debug message.
     *
     * @param  array<string, mixed>  $context
     */
    public function debug(string $message, array $context = []): void
    {
        Log::debug($message, $this->buildContext($context));
    }

    /**
     * Log a performance metric.
     *
     * @param  float  $duration  Duration in milliseconds
     * @param  array<string, mixed>  $context
     */
    public function performance(string $operation, float $duration, array $context = []): void
    {
        $context['operation'] = $operation;
        $context['duration_ms'] = round($duration, 2);
        $context['is_slow'] = $duration > 1000; // Flag operations over 1 second

        Log::channel('performance')->info("Performance: {$operation}", $this->buildContext($context));

        // Also log to main channel if slow
        if ($duration > 1000) {
            $this->warning("Slow operation detected: {$operation} took {$duration}ms", $context);
        }
    }

    /**
     * Log an audit event.
     *
     * @param  array<string, mixed>  $context
     */
    public function audit(string $action, string $resource, array $context = []): void
    {
        $context['action'] = $action;
        $context['resource'] = $resource;

        Log::channel('audit')->info("Audit: {$action} on {$resource}", $this->buildContext($context));
    }

    /**
     * Log a security event.
     *
     * @param  string  $severity  'low', 'medium', 'high', 'critical'
     * @param  array<string, mixed>  $context
     */
    public function security(string $event, string $severity = 'medium', array $context = []): void
    {
        $context['event'] = $event;
        $context['severity'] = $severity;

        $logLevel = match ($severity) {
            'critical' => 'critical',
            'high' => 'error',
            'medium' => 'warning',
            default => 'info',
        };

        Log::channel('security')->{$logLevel}("Security: {$event}", $this->buildContext($context));
    }

    /**
     * Log a database query for debugging.
     *
     * @param  array<mixed>  $bindings
     * @param  float  $time  Time in milliseconds
     */
    public function query(string $query, array $bindings, float $time): void
    {
        if ($time > 100) { // Only log slow queries
            $this->warning('Slow query detected', [
                'query' => $query,
                'bindings' => $bindings,
                'time_ms' => round($time, 2),
            ]);
        }
    }

    /**
     * Log an exception with full context.
     *
     * @param  array<string, mixed>  $context
     */
    public function exception(\Throwable $exception, array $context = []): void
    {
        $context['exception'] = [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];

        $this->error($exception->getMessage(), $context);
    }

    /**
     * Start a performance timer.
     *
     * @return float Start time in milliseconds
     */
    public function startTimer(string $operation): float
    {
        return microtime(true) * 1000;
    }

    /**
     * End a performance timer and log the result.
     *
     * @param  float  $startTime  Start time from startTimer()
     * @param  array<string, mixed>  $context
     * @return float Duration in milliseconds
     */
    public function endTimer(string $operation, float $startTime, array $context = []): float
    {
        $duration = (microtime(true) * 1000) - $startTime;
        $this->performance($operation, $duration, $context);

        return $duration;
    }

    /**
     * Log a business event.
     *
     * @param  array<string, mixed>  $context
     */
    public function business(string $event, array $context = []): void
    {
        $context['event_type'] = 'business';
        $this->info("Business Event: {$event}", $context);
    }

    /**
     * Log user activity.
     *
     * @param  array<string, mixed>  $context
     */
    public function activity(string $activity, array $context = []): void
    {
        $context['activity_type'] = 'user_activity';
        $this->info("User Activity: {$activity}", $context);
    }
}
