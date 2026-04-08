<?php

namespace App\Exceptions;

/**
 * Category-specific exception class.
 *
 * Provides factory methods for creating category-related exceptions
 * with consistent error messages and HTTP status codes.
 */
class CategoryException extends DomainException
{
    /**
     * HTTP status code for the exception.
     */
    protected int $statusCode = 422;

    /**
     * Create exception for category not found.
     *
     * @param  string  $slug  The category slug
     */
    public static function notFound(string $slug): self
    {
        return new self(
            message: "Category not found: {$slug}",
            code: 404,
            statusCode: 404,
            context: ['slug' => $slug]
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
            message: "Category slug already exists: {$slug}",
            code: 409,
            statusCode: 409,
            context: ['slug' => $slug]
        );
    }

    /**
     * Create exception for category with products.
     *
     * @param  string  $category  The category name or slug
     */
    public static function hasProducts(string $category): self
    {
        return new self(
            message: "Cannot delete category with products: {$category}",
            code: 422,
            statusCode: 422,
            context: ['category' => $category]
        );
    }

    /**
     * Create exception for invalid parent category.
     *
     * @param  int  $parentId  The invalid parent category ID
     */
    public static function invalidParent(int $parentId): self
    {
        return new self(
            message: "Invalid parent category ID: {$parentId}",
            code: 422,
            statusCode: 422,
            context: ['parent_id' => $parentId]
        );
    }

    /**
     * Create exception for circular reference.
     *
     * @param  int  $categoryId  The category ID
     * @param  int  $parentId  The parent category ID
     */
    public static function circularReference(int $categoryId, int $parentId): self
    {
        return new self(
            message: "Circular reference detected: category {$categoryId} cannot be its own ancestor",
            code: 422,
            statusCode: 422,
            context: ['category_id' => $categoryId, 'parent_id' => $parentId]
        );
    }

    /**
     * Create exception for category creation failure.
     *
     * @param  string  $reason  The reason for failure
     */
    public static function creationFailed(string $reason): self
    {
        return new self(
            message: "Failed to create category: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['reason' => $reason]
        );
    }

    /**
     * Create exception for category update failure.
     *
     * @param  int  $categoryId  The category ID
     * @param  string  $reason  The reason for failure
     */
    public static function updateFailed(int $categoryId, string $reason): self
    {
        return new self(
            message: "Failed to update category {$categoryId}: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['category_id' => $categoryId, 'reason' => $reason]
        );
    }

    /**
     * Create exception for category deletion failure.
     *
     * @param  int  $categoryId  The category ID
     * @param  string  $reason  The reason for failure
     */
    public static function deletionFailed(int $categoryId, string $reason): self
    {
        return new self(
            message: "Failed to delete category {$categoryId}: {$reason}",
            code: 500,
            statusCode: 500,
            context: ['category_id' => $categoryId, 'reason' => $reason]
        );
    }
}
