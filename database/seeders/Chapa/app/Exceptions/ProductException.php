<?php

namespace App\Exceptions;

/**
 * Product-specific exception class.
 *
 * Provides factory methods for creating product-related exceptions
 * with consistent error messages and HTTP status codes.
 */
class ProductException extends DomainException
{
    /**
     * HTTP status code for the exception.
     */
    protected int $statusCode = 422;

    /**
     * Create exception for product not found.
     *
     * @param  int  $productId  The product ID
     */
    public static function notFound(int $productId): self
    {
        return new self(
            message: "Product not found with ID: {$productId}",
            code: 404,
            statusCode: 404,
            context: ['product_id' => $productId]
        );
    }

    /**
     * Create exception for product not found by slug.
     *
     * @param  string  $slug  The product slug
     */
    public static function notFoundBySlug(string $slug): self
    {
        return new self(
            message: "Product not found with slug: {$slug}",
            code: 404,
            statusCode: 404,
            context: ['slug' => $slug]
        );
    }

    /**
     * Create exception for invalid image.
     *
     * @param  string  $reason  The reason for invalidity
     */
    public static function invalidImage(string $reason): self
    {
        return new self(
            message: "Invalid product image: {$reason}",
            code: 422,
            statusCode: 422,
            context: ['reason' => $reason]
        );
    }

    /**
     * Create exception for duplicate slug.
     *
     * @param  string  $slug  The duplicate slug
     */
    public static function duplicateSlug(string $slug): self
    {
        return new self(
            message: "Product slug already exists: {$slug}",
            code: 409,
            statusCode: 409,
            context: ['slug' => $slug]
        );
    }

    /**
     * Create exception for out of stock.
     *
     * @param  int|null  $productId  Optional product ID
     */
    public static function outOfStock(?int $productId = null): self
    {
        $message = $productId
            ? "Product with ID {$productId} is out of stock."
            : 'Product is out of stock.';

        return new self(
            message: $message,
            code: 422,
            statusCode: 422,
            context: ['product_id' => $productId]
        );
    }

    /**
     * Create exception for product creation failure.
     *
     * @param  string  $reason  The reason for failure
     */
    public static function creationFailed(string $reason): self
    {
        return new self(
            message: "Failed to create product: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['reason' => $reason]
        );
    }

    /**
     * Create exception for product update failure.
     *
     * @param  int  $productId  The product ID
     * @param  string  $reason  The reason for failure
     */
    public static function updateFailed(int $productId, string $reason): self
    {
        return new self(
            message: "Failed to update product {$productId}: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['product_id' => $productId, 'reason' => $reason]
        );
    }

    /**
     * Create exception for product deletion failure.
     *
     * @param  int  $productId  The product ID
     * @param  string  $reason  The reason for failure
     */
    public static function deletionFailed(int $productId, string $reason): self
    {
        return new self(
            message: "Failed to delete product {$productId}: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['product_id' => $productId, 'reason' => $reason]
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
            message: "Invalid product price: {$price}. Price must be greater than zero.",
            code: 422,
            statusCode: 422,
            context: ['price' => $price]
        );
    }

    /**
     * Create exception for invalid category.
     *
     * @param  int  $categoryId  The invalid category ID
     */
    public static function invalidCategory(int $categoryId): self
    {
        return new self(
            message: "Invalid category ID: {$categoryId}",
            code: 422,
            statusCode: 422,
            context: ['category_id' => $categoryId]
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
     * Create exception for unauthorized action.
     *
     * @param  string  $action  The action attempted
     * @param  int|null  $productId  Optional product ID
     */
    public static function unauthorized(string $action, ?int $productId = null): self
    {
        $message = $productId
            ? "Unauthorized to {$action} product {$productId}"
            : "Unauthorized to {$action} product";

        return new self(
            message: $message,
            code: 403,
            statusCode: 403,
            context: ['action' => $action, 'product_id' => $productId]
        );
    }
}
