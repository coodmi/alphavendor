<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Eloquent implementation of the Product repository.
 *
 * Provides product-specific data access with caching and query optimization.
 */
class EloquentProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Cache service instance.
     */
    protected CacheService $cacheService;

    /**
     * Create a new product repository instance.
     */
    public function __construct(Product $model, CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cachePrefix = 'products';
        $this->cacheService = $cacheService;
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug(string $slug, array $relations = []): ?Product
    {
        $cacheKey = $this->getCacheKey("slug.{$slug}.".implode('.', $relations));

        return $this->cacheService->remember(
            $cacheKey,
            function () use ($slug, $relations) {
                $query = $this->model->newQuery();

                if (! empty($relations)) {
                    $query->with($relations);
                }

                return $query->where('slug', $slug)->first();
            },
            $this->cacheService->getTtl('medium')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getActive(array $relations = []): Collection
    {
        $cacheKey = $this->getCacheKey('active.'.implode('.', $relations));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery()->active();

        if (! empty($relations)) {
            $query->with($relations);
        }

        $result = $query->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getByCategory(int $categoryId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->where('category_id', $categoryId)
            ->with(['category']);

        $this->applyFilters($query, $filters);

        // Apply default sorting
        if (! isset($filters['sort'])) {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getInStock(array $relations = []): Collection
    {
        $cacheKey = $this->getCacheKey('in_stock.'.implode('.', $relations));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery()->inStock()->active();

        if (! empty($relations)) {
            $query->with($relations);
        }

        $result = $query->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getPopular(int $limit = 10, array $relations = []): Collection
    {
        $cacheKey = $this->getCacheKey("popular.{$limit}.".implode('.', $relations));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery()
            ->active()
            ->orderBy('popularity', 'desc')
            ->limit($limit);

        if (! empty($relations)) {
            $query->with($relations);
        }

        $result = $query->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $searchQuery = $this->model->newQuery()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['category']);

        $this->applyFilters($searchQuery, $filters);

        return $searchQuery->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function updateStock(Product $product, bool $inStock): Product
    {
        $product->stock = $inStock;
        $product->save();

        $this->clearCache();
        $this->clearModelCache($product);
        $this->clearSlugCache($product->slug);

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementPopularity(Product $product, int $amount = 1): Product
    {
        $product->increment('popularity', $amount);

        // Clear popularity-related caches using pattern matching
        $this->cacheService->invalidate($this->cachePrefix.'.popular.*');

        return $product->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function getLowStock(): Collection
    {
        return $this->model->newQuery()
            ->where('stock', false)
            ->orWhere('is_active', false)
            ->with(['category'])
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(array $filters = [], int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Default relations for products
        $defaultRelations = ['category'];
        $relations = array_merge($defaultRelations, $relations);
        $query->with($relations);

        // Handle special filters
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
            unset($filters['is_active']);
        }

        if (isset($filters['stock'])) {
            $query->where('stock', $filters['stock']);
            unset($filters['stock']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
            unset($filters['category_id']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
            unset($filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
            unset($filters['max_price']);
        }

        if (isset($filters['sort'])) {
            $sortField = $filters['sort'];
            $sortDirection = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($sortField, $sortDirection);
            unset($filters['sort'], $filters['sort_direction']);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * Clear slug-specific cache.
     */
    protected function clearSlugCache(string $slug): void
    {
        $this->cacheService->forget($this->getCacheKey("slug.{$slug}"));
        $this->cacheService->forget($this->getCacheKey("slug.{$slug}.category"));
    }

    /**
     * {@inheritdoc}
     */
    protected function clearCache(): void
    {
        parent::clearCache();

        // Use CacheService to invalidate product-related caches with pattern matching
        $this->cacheService->invalidate($this->cachePrefix.'.*');
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id, array $relations = []): ?Model
    {
        // Add default relations for products
        if (empty($relations)) {
            $relations = ['category'];
        }

        return parent::find($id, $relations);
    }
}
