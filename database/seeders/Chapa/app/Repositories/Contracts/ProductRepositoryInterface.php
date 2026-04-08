<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Product repository interface defining product-specific data access operations.
 *
 * This interface extends the base repository interface and adds
 * product-specific methods for querying and managing products.
 */
interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a product by its slug.
     *
     * @param  string  $slug  The product slug
     * @param  array<string>  $relations  Relations to eager load
     */
    public function findBySlug(string $slug, array $relations = []): ?Product;

    /**
     * Get active products.
     *
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Product>
     */
    public function getActive(array $relations = []): Collection;

    /**
     * Get products by category.
     *
     * @param  int  $categoryId  The category ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function getByCategory(int $categoryId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get products that are in stock.
     *
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Product>
     */
    public function getInStock(array $relations = []): Collection;

    /**
     * Get popular products.
     *
     * @param  int  $limit  Number of products to return
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Product>
     */
    public function getPopular(int $limit = 10, array $relations = []): Collection;

    /**
     * Search products by title or description.
     *
     * @param  string  $query  Search query
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Update product stock status.
     *
     * @param  Product  $product  The product to update
     * @param  bool  $inStock  Stock status
     */
    public function updateStock(Product $product, bool $inStock): Product;

    /**
     * Increment product popularity.
     *
     * @param  Product  $product  The product
     * @param  int  $amount  Amount to increment
     */
    public function incrementPopularity(Product $product, int $amount = 1): Product;

    /**
     * Get products with low stock or out of stock.
     *
     * @return Collection<int, Product>
     */
    public function getLowStock(): Collection;
}
