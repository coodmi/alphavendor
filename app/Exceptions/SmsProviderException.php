<?php

namespace App\Exceptions;

use Exception;

/**
 * SMS Provider Exception
 * 
 * Custom exception for SMS provider errors
 * 
 * @package App\Exceptions
 * @author Your Name
 * @version 1.0.0
 */
class SmsProviderException extends Exception
{
    private ?array $context;

    /**
     * Constructor
     *
     * @param string $message Exception message
     * @param int $code Exception code
     * @param Exception|null $previous Previous exception
     * @param array|null $context Additional context data
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        ?array $context = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get exception context
     *
     * @return array|null Context data
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * Set exception context
     *
     * @param array $context Context data
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Convert exception to array
     *
     * @return array Exception data
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'context' => $this->context,
            'trace' => $this->getTraceAsString()
        ];
    }
}