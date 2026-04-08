<?php

namespace App\Exceptions;

/**
 * Cart-specific exception class.
 *
 * Provides factory methods for creating cart-related exceptions
 * with consistent error messages and HTTP status codes.
 */
class CartException extends DomainException
{
    /**
     * HTTP status code for the exception.
     */
    protected int $statusCode = 422;

    /**
     * Create exception for item not found in cart.
     *
     * @param  string  $productKey  The product key
     */
    public static function itemNotFound(string $productKey): self
    {
        return new self(
            message: "Item not found in cart: {$productKey}",
            code: 404,
            statusCode: 404,
            context: ['product_key' => $productKey]
        );
    }

    /**
     * Create exception for invalid quantity.
     *
     * @param  int  $quantity  The invalid quantity
     */
    public static function invalidQuantity(int $quantity): self
    {
        return new self(
            message: "Invalid quantity: {$quantity}. Quantity must be greater than 0.",
            code: 422,
            statusCode: 422,
            context: ['quantity' => $quantity]
        );
    }

    /**
     * Create exception for invalid price.
     *
     * @param  float  $price  The invalid price
     */
    public static function invalidPrice(float $price): self
    {
        return new self(
            message: "Invalid price: {$price}. Price must be greater than 0.",
            code: 422,
            statusCode: 422,
            context: ['price' => $price]
        );
    }

    /**
     * Create exception for missing product title.
     */
    public static function missingProductTitle(): self
    {
        return new self(
            message: 'Product title is required.',
            code: 422,
            statusCode: 422
        );
    }

    /**
     * Create exception for product not available.
     *
     * @param  int  $productId  The product ID
     */
    public static function productNotAvailable(int $productId): self
    {
        return new self(
            message: "Product is not available: {$productId}",
            code: 422,
            statusCode: 422,
            context: ['product_id' => $productId]
        );
    }

    /**
     * Create exception for product out of stock.
     *
     * @param  int  $productId  The product ID
     * @param  string|null  $productTitle  Optional product title
     */
    public static function outOfStock(int $productId, ?string $productTitle = null): self
    {
        $message = $productTitle
            ? "Product '{$productTitle}' is out of stock."
            : "Product {$productId} is out of stock.";

        return new self(
            message: $message,
            code: 422,
            statusCode: 422,
            context: ['product_id' => $productId, 'product_title' => $productTitle]
        );
    }

    /**
     * Create exception for insufficient stock.
     *
     * @param  int  $productId  The product ID
     * @param  int  $requested  Requested quantity
     * @param  int  $available  Available quantity
     */
    public static function insufficientStock(int $productId, int $requested, int $available): self
    {
        return new self(
            message: "Insufficient stock for product {$productId}. Requested: {$requested}, Available: {$available}",
            code: 422,
            statusCode: 422,
            context: [
                'product_id' => $productId,
                'requested' => $requested,
                'available' => $available,
            ]
        );
    }

    /**
     * Create exception for empty cart.
     */
    public static function emptyCart(): self
    {
        return new self(
            message: 'Cart is empty.',
            code: 422,
            statusCode: 422
        );
    }

    /**
     * Create exception for cart validation failure.
     *
     * @param  array<string, array<string, mixed>>  $issues  Validation issues
     */
    public static function validationFailed(array $issues): self
    {
        return new self(
            message: 'Cart validation failed. Some items may have changed or become unavailable.',
            code: 422,
            statusCode: 422,
            context: ['issues' => $issues]
        );
    }

    /**
     * Create exception for cart operation failure.
     *
     * @param  string  $operation  The operation that failed
     * @param  string  $reason  The reason for failure
     */
    public static function operationFailed(string $operation, string $reason): self
    {
        return new self(
            message: "Cart operation '{$operation}' failed: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['operation' => $operation, 'reason' => $reason]
        );
    }

    /**
     * Create exception for maximum quantity exceeded.
     *
     * @param  int  $productId  The product ID
     * @param  int  $maxQuantity  Maximum allowed quantity
     */
    public static function maxQuantityExceeded(int $productId, int $maxQuantity): self
    {
        return new self(
            message: "Maximum quantity ({$maxQuantity}) exceeded for product {$productId}.",
            code: 422,
            statusCode: 422,
            context: ['product_id' => $productId, 'max_quantity' => $maxQuantity]
        );
    }

    /**
     * Create exception for cart merge failure.
     *
     * @param  string  $reason  The reason for failure
     */
    public static function mergeFailed(string $reason): self
    {
        return new self(
            message: "Failed to merge carts: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['reason' => $reason]
        );
    }
}
