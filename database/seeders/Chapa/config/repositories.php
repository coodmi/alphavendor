<?php

/**
 * Repository Pattern Configuration.
 *
 * This configuration file manages the repository pattern implementation
 * for the application. The repository pattern provides an abstraction
 * layer between the domain/business logic and data access logic.
 *
 * Benefits of using repositories:
 * - Decouples business logic from data access
 * - Makes code more testable (easy to mock repositories)
 * - Centralizes data access logic
 * - Enables caching at the repository level
 * - Allows swapping data sources without changing business logic
 *
 * Usage Examples:
 * ---------------
 *
 * 1. Inject repository in controller:
 *    ```php
 *    public function __construct(
 *        private ProductRepositoryInterface $products
 *    ) {}
 *    ```
 *
 * 2. Use repository methods:
 *    ```php
 *    $product = $this->products->find($id);
 *    $products = $this->products->getByCategory($categoryId);
 *    $popular = $this->products->getPopular(10);
 *    ```
 *
 * 3. Create with repository:
 *    ```php
 *    $product = $this->products->create([
 *        'title' => 'New Product',
 *        'price' => 99.99,
 *    ]);
 *    ```
 *
 * @see App\Repositories\Contracts for interface definitions
 * @see App\Repositories\Eloquent for implementations
 * @see App\Providers\AppServiceProvider for binding registration
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Repository Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the repository pattern
    | implementation. You can customize caching behavior, default settings,
    | and other repository-related options here.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching behavior for repositories. Caching can significantly
    | improve performance for frequently accessed data.
    |
    | Cache keys follow the pattern: {prefix}:{entity}:{identifier}
    | Example: repo:product:123, repo:category:slug:electronics
    |
    */
    'cache' => [
        // Enable or disable repository caching globally
        'enabled' => env('REPOSITORY_CACHE_ENABLED', true),

        // Default cache TTL in seconds (1 hour)
        'ttl' => env('REPOSITORY_CACHE_TTL', 3600),

        // Cache driver to use (null = default driver from config/cache.php)
        'driver' => env('REPOSITORY_CACHE_DRIVER', null),

        // Cache key prefix for all repository cache entries
        'prefix' => env('REPOSITORY_CACHE_PREFIX', 'repo'),

        // Entity-specific TTL overrides (in seconds)
        'ttl_overrides' => [
            'product' => env('REPOSITORY_CACHE_TTL_PRODUCT', 1800),    // 30 minutes
            'category' => env('REPOSITORY_CACHE_TTL_CATEGORY', 7200), // 2 hours
            'order' => env('REPOSITORY_CACHE_TTL_ORDER', 300),        // 5 minutes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Repository Bindings
    |--------------------------------------------------------------------------
    |
    | Define the interface to implementation bindings for repositories.
    | These are automatically registered in the service container by
    | AppServiceProvider.
    |
    | To add a new repository:
    | 1. Create interface in App\Repositories\Contracts
    | 2. Create implementation in App\Repositories\Eloquent
    | 3. Add binding below
    |
    */
    'bindings' => [
        \App\Repositories\Contracts\ProductRepositoryInterface::class => \App\Repositories\Eloquent\EloquentProductRepository::class,
        \App\Repositories\Contracts\CategoryRepositoryInterface::class => \App\Repositories\Eloquent\EloquentCategoryRepository::class,
        \App\Repositories\Contracts\OrderRepositoryInterface::class => \App\Repositories\Eloquent\EloquentOrderRepository::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Settings
    |--------------------------------------------------------------------------
    |
    | Default pagination settings for repository queries.
    |
    */
    'pagination' => [
        // Default items per page
        'per_page' => env('REPOSITORY_PER_PAGE', 15),

        // Maximum items per page (to prevent abuse)
        'max_per_page' => env('REPOSITORY_MAX_PER_PAGE', 100),

        // Page parameter name
        'page_name' => 'page',
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Optimization
    |--------------------------------------------------------------------------
    |
    | Settings for query optimization and eager loading.
    |
    */
    'optimization' => [
        // Enable automatic eager loading of default relations
        'auto_eager_load' => true,

        // Log slow queries (in milliseconds)
        'slow_query_threshold' => env('REPOSITORY_SLOW_QUERY_MS', 100),

        // Enable query logging in development
        'log_queries' => env('REPOSITORY_LOG_QUERIES', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Relations
    |--------------------------------------------------------------------------
    |
    | Define default relations to eager load for each entity type.
    | These are loaded automatically when fetching entities.
    |
    */
    'default_relations' => [
        'product' => ['category'],
        'category' => [],
        'order' => ['items', 'user'],
    ],

];
