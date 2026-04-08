<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Eloquent implementation of the Category repository.
 *
 * Provides category-specific data access with hierarchy support and caching.
 */
class EloquentCategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * Cache service instance.
     */
    protected CacheService $cacheService;

    /**
     * Create a new category repository instance.
     */
    public function __construct(Category $model, CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cachePrefix = 'categories';
        $this->cacheService = $cacheService;
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug(string $slug, array $relations = []): ?Category
    {
        $cacheKey = $this->getCacheKey("slug.{$slug}.".implode('.', $relations));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery();

        if (! empty($relations)) {
            $query->with($relations);
        }

        /** @var Category|null $result */
        $result = $query->where('slug', $slug)->first();

        if ($this->cacheEnabled && $result) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
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

        $query = $this->model->newQuery()->where('is_active', true);

        if (! empty($relations)) {
            $query->with($relations);
        }

        $result = $query->orderBy('name')->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getWithProductCounts(bool $activeOnly = true): Collection
    {
        $cacheKey = $this->getCacheKey("with_counts.{$activeOnly}");

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery()
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }]);

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        $result = $query->orderBy('name')->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getTree(bool $activeOnly = true): Collection
    {
        $cacheKey = $this->getCacheKey("tree.{$activeOnly}");

        return $this->cacheService->rememberWithTags(
            ['categories', 'category_tree'],
            $cacheKey,
            function () use ($activeOnly) {
                $query = $this->model->newQuery();

                if ($activeOnly) {
                    $query->where('is_active', true);
                }

                // Currently returns flat list - prepared for future hierarchy support
                // When parent_id is added, this will build a proper tree structure
                return $query->orderBy('name')->get();
            },
            $this->cacheService->getTtl('long')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getRoots(bool $activeOnly = true): Collection
    {
        $cacheKey = $this->getCacheKey("roots.{$activeOnly}");

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery();

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        // Currently returns all categories - prepared for future hierarchy support
        // When parent_id is added: $query->whereNull('parent_id')
        $result = $query->orderBy('name')->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getForSelect(bool $activeOnly = true): array
    {
        $cacheKey = $this->getCacheKey("select.{$activeOnly}");

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery();

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        $result = $query->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProducts(Category $category): bool
    {
        return $category->products()->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function getWithProducts(int $categoryId, array $productFilters = []): ?Category
    {
        $query = $this->model->newQuery()
            ->with(['products' => function ($q) use ($productFilters) {
                if (isset($productFilters['is_active'])) {
                    $q->where('is_active', $productFilters['is_active']);
                }
                if (isset($productFilters['stock'])) {
                    $q->where('stock', $productFilters['stock']);
                }
                if (isset($productFilters['sort'])) {
                    $sortDirection = $productFilters['sort_direction'] ?? 'asc';
                    $q->orderBy($productFilters['sort'], $sortDirection);
                } else {
                    $q->orderBy('created_at', 'desc');
                }
            }]);

        return $query->find($categoryId);
    }

    /**
     * {@inheritdoc}
     */
    public function reorder(array $order): bool
    {
        try {
            DB::beginTransaction();

            foreach ($order as $categoryId => $position) {
                $this->model->newQuery()
                    ->where('id', $categoryId)
                    ->update(['sort_order' => $position]);
            }

            DB::commit();
            $this->clearCache();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function clearCache(): void
    {
        parent::clearCache();

        // Use CacheService to invalidate all category-related caches
        $this->cacheService->invalidate($this->cachePrefix.'.*');

        // Also flush category tags
        $this->cacheService->flushTags(['categories', 'category_tree']);
    }

    /**
     * Clear slug-specific cache.
     */
    protected function clearSlugCache(string $slug): void
    {
        $this->cacheService->forget($this->getCacheKey("slug.{$slug}"));
        $this->cacheService->forget($this->getCacheKey("slug.{$slug}.products"));
    }

    /**
     * {@inheritdoc}
     */
    public function update(Model $model, array $data): Model
    {
        /** @var Category $model */
        $oldSlug = $model->slug;

        $result = parent::update($model, $data);

        // Clear old slug cache if slug changed
        if ($oldSlug !== $model->slug) {
            $this->clearSlugCache($oldSlug);
        }
        $this->clearSlugCache($model->slug);

        return $result;
    }
}
