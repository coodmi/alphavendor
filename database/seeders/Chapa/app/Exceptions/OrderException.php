<?php

namespace App\Exceptions;

/**
 * Order-specific exception class.
 *
 * Provides factory methods for creating order-related exceptions
 * with consistent error messages and HTTP status codes.
 */
class OrderException extends DomainException
{
    /**
     * HTTP status code for the exception.
     */
    protected int $statusCode = 422;

    /**
     * Create exception for order not found.
     *
     * @param  string|int  $identifier  Order ID or order number
     */
    public static function notFound(string|int $identifier): self
    {
        return new self(
            message: "Order not found: {$identifier}",
            code: 404,
            statusCode: 404,
            context: ['identifier' => $identifier]
        );
    }

    /**
     * Create exception for invalid status.
     *
     * @param  string  $status  The invalid status
     */
    public static function invalidStatus(string $status): self
    {
        return new self(
            message: "Invalid order status: {$status}",
            code: 422,
            statusCode: 422,
            context: ['status' => $status]
        );
    }

    /**
     * Create exception for invalid status transition.
     *
     * @param  string  $currentStatus  Current status
     * @param  string  $targetStatus  Target status
     */
    public static function invalidStatusTransition(string $currentStatus, string $targetStatus): self
    {
        return new self(
            message: "Cannot transition order from '{$currentStatus}' to '{$targetStatus}'",
            code: 422,
            statusCode: 422,
            context: [
                'current_status' => $currentStatus,
                'target_status' => $targetStatus,
            ]
        );
    }

    /**
     * Create exception for empty cart.
     */
    public static function emptyCart(): self
    {
        return new self(
            message: 'Cannot create order from empty cart.',
            code: 422,
            statusCode: 422
        );
    }

    /**
     * Create exception for insufficient stock.
     *
     * @param  string  $product  Product name or ID
     * @param  int|null  $requested  Requested quantity
     * @param  int|null  $available  Available quantity
     */
    public static function insufficientStock(
        string $product,
        ?int $requested = null,
        ?int $available = null
    ): self {
        $message = "Insufficient stock for product: {$product}";

        if ($requested !== null && $available !== null) {
            $message .= ". Requested: {$requested}, Available: {$available}";
        }

        return new self(
            message: $message,
            code: 422,
            statusCode: 422,
            context: [
                'product' => $product,
                'requested' => $requested,
                'available' => $available,
            ]
        );
    }

    /**
     * Create exception for order creation failure.
     *
     * @param  string  $reason  The reason for failure
     */
    public static function creationFailed(string $reason): self
    {
        return new self(
            message: "Order creation failed: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['reason' => $reason]
        );
    }

    /**
     * Create exception for processing failure.
     *
     * @param  string  $reason  The reason for failure
     */
    public static function processingFailed(string $reason): self
    {
        return new self(
            message: "Order processing failed: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['reason' => $reason]
        );
    }

    /**
     * Create exception for order update failure.
     *
     * @param  string|int  $identifier  Order ID or order number
     * @param  string  $reason  The reason for failure
     */
    public static function updateFailed(string|int $identifier, string $reason): self
    {
        return new self(
            message: "Failed to update order {$identifier}: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['identifier' => $identifier, 'reason' => $reason]
        );
    }

    /**
     * Create exception when order cannot be cancelled.
     *
     * @param  string  $orderNumber  Order number
     * @param  string  $currentStatus  Current status
     */
    public static function cannotCancel(string $orderNumber, string $currentStatus): self
    {
        return new self(
            message: "Order {$orderNumber} cannot be cancelled. Current status: {$currentStatus}",
            code: 422,
            statusCode: 422,
            context: [
                'order_number' => $orderNumber,
                'current_status' => $currentStatus,
            ]
        );
    }

    /**
     * Create exception when order cannot be refunded.
     *
     * @param  string  $orderNumber  Order number
     * @param  string  $reason  The reason
     */
    public static function cannotRefund(string $orderNumber, string $reason): self
    {
        return new self(
            message: "Order {$orderNumber} cannot be refunded: {$reason}",
            code: 422,
            statusCode: 422,
            context: [
                'order_number' => $orderNumber,
                'reason' => $reason,
            ]
        );
    }

    /**
     * Create exception for payment failure.
     *
     * @param  string  $orderNumber  Order number
     * @param  string  $reason  The reason for failure
     */
    public static function paymentFailed(string $orderNumber, string $reason): self
    {
        return new self(
            message: "Payment failed for order {$orderNumber}: {$reason}",
            code: 402,
            statusCode: 402,
            context: [
                'order_number' => $orderNumber,
                'reason' => $reason,
            ]
        );
    }

    /**
     * Create exception for unauthorized action.
     *
     * @param  string  $action  The action attempted
     * @param  string|null  $orderNumber  Optional order number
     */
    public static function unauthorized(string $action, ?string $orderNumber = null): self
    {
        $message = $orderNumber
            ? "Unauthorized to {$action} order {$orderNumber}"
            : "Unauthorized to {$action} order";

        return new self(
            message: $message,
            code: 403,
            statusCode: 403,
            context: ['action' => $action, 'order_number' => $orderNumber]
        );
    }

    /**
     * Create exception for validation failure.
     *
     * @param  array<string, array<string>>  $errors  Validation errors
     */
    public static function validationFailed(array $errors): self
    {
        return new self(
            message: 'Order validation failed',
            code: 422,
            statusCode: 422,
            context: ['errors' => $errors]
        );
    }

    /**
     * Create exception for shipping failure.
     *
     * @param  string  $orderNumber  Order number
     * @param  string  $reason  The reason for failure
     */
    public static function shippingFailed(string $orderNumber, string $reason): self
    {
        return new self(
            message: "Shipping failed for order {$orderNumber}: {$reason}",
            code: 500,
            statusCode: 500,
            context: [
                'order_number' => $orderNumber,
                'reason' => $reason,
            ]
        );
    }
}
