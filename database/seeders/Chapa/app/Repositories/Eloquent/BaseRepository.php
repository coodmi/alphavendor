<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Base Eloquent repository implementation.
 *
 * Provides common CRUD operations with optional caching support.
 * All concrete repositories should extend this class.
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * The model instance.
     */
    protected Model $model;

    /**
     * Cache TTL in seconds (default: 1 hour).
     */
    protected int $cacheTtl = 3600;

    /**
     * Whether caching is enabled for this repository.
     */
    protected bool $cacheEnabled = true;

    /**
     * Cache key prefix for this repository.
     */
    protected string $cachePrefix = '';

    /**
     * Create a new repository instance.
     *
     * @param  Model  $model  The Eloquent model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->cachePrefix = strtolower(class_basename($model));
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id, array $relations = []): ?Model
    {
        $cacheKey = $this->getCacheKey("find.{$id}.".implode('.', $relations));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery();

        if (! empty($relations)) {
            $query->with($relations);
        }

        $result = $query->find($id);

        if ($this->cacheEnabled && $result) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail(int $id, array $relations = []): Model
    {
        $query = $this->model->newQuery();

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all(array $relations = []): Collection
    {
        $cacheKey = $this->getCacheKey('all.'.implode('.', $relations));

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->model->newQuery();

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
    public function paginate(array $filters = [], int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (! empty($relations)) {
            $query->with($relations);
        }

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Model
    {
        $model = $this->model->newInstance($data);
        $model->save();

        $this->clearCache();

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function update(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();

        $this->clearCache();
        $this->clearModelCache($model);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Model $model): bool
    {
        $result = $model->delete();

        $this->clearCache();
        $this->clearModelCache($model);

        return $result;
    }

    /**
     * Apply filters to a query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array<string, mixed>  $filters
     */
    protected function applyFilters($query, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }
    }

    /**
     * Get a cache key with the repository prefix.
     *
     * @param  string  $key  The cache key suffix
     */
    protected function getCacheKey(string $key): string
    {
        return "{$this->cachePrefix}.{$key}";
    }

    /**
     * Clear all cache for this repository.
     */
    protected function clearCache(): void
    {
        if (! $this->cacheEnabled) {
            return;
        }

        // Clear common cache keys
        Cache::forget($this->getCacheKey('all'));
        Cache::forget($this->getCacheKey('all.category'));
    }

    /**
     * Clear cache for a specific model.
     */
    protected function clearModelCache(Model $model): void
    {
        if (! $this->cacheEnabled) {
            return;
        }

        Cache::forget($this->getCacheKey("find.{$model->getKey()}"));
    }

    /**
     * Disable caching for this repository instance.
     */
    public function withoutCache(): static
    {
        $this->cacheEnabled = false;

        return $this;
    }

    /**
     * Enable caching for this repository instance.
     */
    public function withCache(): static
    {
        $this->cacheEnabled = true;

        return $this;
    }

    /**
     * Set the cache TTL.
     */
    public function setCacheTtl(int $seconds): static
    {
        $this->cacheTtl = $seconds;

        return $this;
    }
}
