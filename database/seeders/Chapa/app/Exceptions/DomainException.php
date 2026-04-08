<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Base domain exception class.
 *
 * Provides common functionality for all domain-specific exceptions
 * including HTTP status codes, context data, JSON serialization,
 * and automatic logging capabilities.
 */
abstract class DomainException extends Exception
{
    /**
     * HTTP status code for the exception.
     */
    protected int $statusCode = 500;

    /**
     * Additional context data.
     *
     * @var array<string, mixed>
     */
    protected array $context = [];

    /**
     * Whether to automatically log this exception.
     */
    protected bool $shouldLog = true;

    /**
     * Log level for this exception.
     */
    protected string $logLevel = 'error';

    /**
     * Create a new DomainException instance.
     *
     * @param  string  $message  Error message
     * @param  int  $code  Error code
     * @param  Throwable|null  $previous  Previous exception
     * @param  int|null  $statusCode  HTTP status code (overrides default)
     * @param  array<string, mixed>  $context  Additional context
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        ?int $statusCode = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);

        if ($statusCode !== null) {
            $this->statusCode = $statusCode;
        }

        $this->context = $context;

        // Automatically log the exception if configured
        if ($this->shouldLog) {
            $this->logException();
        }
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get additional context data.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set additional context data.
     *
     * @param  array<string, mixed>  $context
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Add a single context item.
     */
    public function addContext(string $key, mixed $value): self
    {
        $this->context[$key] = $value;

        return $this;
    }

    /**
     * Convert exception to array for JSON responses.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'error' => class_basename($this),
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];

        // Include context if not empty
        if (! empty($this->context)) {
            $data['context'] = $this->context;
        }

        // Include trace in non-production environments
        if (config('app.debug')) {
            $data['trace'] = $this->getTrace();
        }

        return $data;
    }

    /**
     * Log the exception with context.
     */
    protected function logException(): void
    {
        $logContext = array_merge($this->context, [
            'exception' => get_class($this),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'status_code' => $this->statusCode,
        ]);

        // Add request context if available
        if (app()->bound('request')) {
            $request = request();
            $logContext['request'] = [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Add authenticated user info if available
            if (auth()->check()) {
                $logContext['user_id'] = auth()->id();
            }
        }

        Log::log($this->logLevel, $this->getMessage(), $logContext);
    }

    /**
     * Set whether this exception should be logged.
     */
    public function setShouldLog(bool $shouldLog): self
    {
        $this->shouldLog = $shouldLog;

        return $this;
    }

    /**
     * Set the log level for this exception.
     */
    public function setLogLevel(string $level): self
    {
        $this->logLevel = $level;

        return $this;
    }

    /**
     * Get a user-friendly error message.
     * Can be overridden by child classes for custom user messages.
     */
    public function getUserMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * Determine if this exception should be reported.
     */
    public function shouldReport(): bool
    {
        return $this->shouldLog;
    }
}
