<?php

namespace App\Exceptions;

/**
 * Payment-specific exception class.
 *
 * Provides factory methods for creating payment-related exceptions
 * with consistent error messages and HTTP status codes.
 */
class PaymentException extends DomainException
{
    /**
     * HTTP status code for the exception.
     */
    protected int $statusCode = 402;

    /**
     * Create exception for payment processing failure.
     *
     * @param  string  $reason  The reason for failure
     * @param  string|null  $transactionId  Optional transaction ID
     */
    public static function processingFailed(string $reason, ?string $transactionId = null): self
    {
        $context = ['reason' => $reason];

        if ($transactionId !== null) {
            $context['transaction_id'] = $transactionId;
        }

        return new self(
            message: "Payment processing failed: {$reason}",
            code: 402,
            statusCode: 402,
            context: $context
        );
    }

    /**
     * Create exception for declined payment.
     *
     * @param  string  $reason  The reason for decline
     */
    public static function declined(string $reason): self
    {
        return new self(
            message: "Payment declined: {$reason}",
            code: 402,
            statusCode: 402,
            context: ['reason' => $reason]
        );
    }

    /**
     * Create exception for insufficient funds.
     */
    public static function insufficientFunds(): self
    {
        return new self(
            message: 'Payment declined: Insufficient funds',
            code: 402,
            statusCode: 402,
            context: ['reason' => 'insufficient_funds']
        );
    }

    /**
     * Create exception for invalid payment method.
     *
     * @param  string  $method  The invalid payment method
     */
    public static function invalidMethod(string $method): self
    {
        return new self(
            message: "Invalid payment method: {$method}",
            code: 422,
            statusCode: 422,
            context: ['method' => $method]
        );
    }

    /**
     * Create exception for expired payment method.
     *
     * @param  string  $method  The payment method type
     */
    public static function expiredMethod(string $method): self
    {
        return new self(
            message: "Payment method expired: {$method}",
            code: 422,
            statusCode: 422,
            context: ['method' => $method, 'reason' => 'expired']
        );
    }

    /**
     * Create exception for payment gateway timeout.
     *
     * @param  string  $gateway  The payment gateway name
     */
    public static function gatewayTimeout(string $gateway): self
    {
        return new self(
            message: "Payment gateway timeout: {$gateway}",
            code: 504,
            statusCode: 504,
            context: ['gateway' => $gateway]
        );
    }

    /**
     * Create exception for payment gateway error.
     *
     * @param  string  $gateway  The payment gateway name
     * @param  string  $error  The error message from gateway
     */
    public static function gatewayError(string $gateway, string $error): self
    {
        return new self(
            message: "Payment gateway error from {$gateway}: {$error}",
            code: 502,
            statusCode: 502,
            context: ['gateway' => $gateway, 'error' => $error]
        );
    }

    /**
     * Create exception for refund failure.
     *
     * @param  string  $transactionId  The transaction ID
     * @param  string  $reason  The reason for failure
     */
    public static function refundFailed(string $transactionId, string $reason): self
    {
        return new self(
            message: "Refund failed for transaction {$transactionId}: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['transaction_id' => $transactionId, 'reason' => $reason]
        );
    }

    /**
     * Create exception for invalid amount.
     *
     * @param  float  $amount  The invalid amount
     */
    public static function invalidAmount(float $amount): self
    {
        return new self(
            message: "Invalid payment amount: {$amount}",
            code: 422,
            statusCode: 422,
            context: ['amount' => $amount]
        );
    }

    /**
     * Create exception for amount mismatch.
     *
     * @param  float  $expected  Expected amount
     * @param  float  $actual  Actual amount
     */
    public static function amountMismatch(float $expected, float $actual): self
    {
        return new self(
            message: "Payment amount mismatch. Expected: {$expected}, Actual: {$actual}",
            code: 422,
            statusCode: 422,
            context: ['expected' => $expected, 'actual' => $actual]
        );
    }

    /**
     * Create exception for duplicate transaction.
     *
     * @param  string  $transactionId  The duplicate transaction ID
     */
    public static function duplicateTransaction(string $transactionId): self
    {
        return new self(
            message: "Duplicate transaction detected: {$transactionId}",
            code: 409,
            statusCode: 409,
            context: ['transaction_id' => $transactionId]
        );
    }

    /**
     * Create exception for payment not found.
     *
     * @param  string  $transactionId  The transaction ID
     */
    public static function notFound(string $transactionId): self
    {
        return new self(
            message: "Payment not found: {$transactionId}",
            code: 404,
            statusCode: 404,
            context: ['transaction_id' => $transactionId]
        );
    }

    /**
     * Create exception for payment already processed.
     *
     * @param  string  $transactionId  The transaction ID
     */
    public static function alreadyProcessed(string $transactionId): self
    {
        return new self(
            message: "Payment already processed: {$transactionId}",
            code: 409,
            statusCode: 409,
            context: ['transaction_id' => $transactionId]
        );
    }
}
