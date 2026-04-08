<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Category repository interface defining category-specific data access operations.
 *
 * This interface extends the base repository interface and adds
 * category-specific methods including tree/hierarchy operations.
 */
interface CategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a category by its slug.
     *
     * @param  string  $slug  The category slug
     * @param  array<string>  $relations  Relations to eager load
     */
    public function findBySlug(string $slug, array $relations = []): ?Category;

    /**
     * Get active categories.
     *
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Category>
     */
    public function getActive(array $relations = []): Collection;

    /**
     * Get categories with product counts.
     *
     * @param  bool  $activeOnly  Only include active categories
     * @return Collection<int, Category>
     */
    public function getWithProductCounts(bool $activeOnly = true): Collection;

    /**
     * Get categories as a tree structure.
     *
     * Note: This is prepared for future parent-child hierarchy support.
     * Currently returns flat list but structured for tree operations.
     *
     * @param  bool  $activeOnly  Only include active categories
     * @return Collection<int, Category>
     */
    public function getTree(bool $activeOnly = true): Collection;

    /**
     * Get root categories (categories without parent).
     *
     * Note: Prepared for future hierarchy support.
     *
     * @param  bool  $activeOnly  Only include active categories
     * @return Collection<int, Category>
     */
    public function getRoots(bool $activeOnly = true): Collection;

    /**
     * Get categories for dropdown/select options.
     *
     * @param  bool  $activeOnly  Only include active categories
     * @return array<int, string> Array of id => name pairs
     */
    public function getForSelect(bool $activeOnly = true): array;

    /**
     * Check if a category has products.
     *
     * @param  Category  $category  The category to check
     */
    public function hasProducts(Category $category): bool;

    /**
     * Get category with all its products.
     *
     * @param  int  $categoryId  The category ID
     * @param  array<string, mixed>  $productFilters  Filters for products
     */
    public function getWithProducts(int $categoryId, array $productFilters = []): ?Category;

    /**
     * Reorder categories.
     *
     * @param  array<int, int>  $order  Array of category_id => position
     */
    public function reorder(array $order): bool;
}
