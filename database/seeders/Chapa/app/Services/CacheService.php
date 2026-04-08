<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * Cache service for multi-layer caching with invalidation strategies.
 *
 * Provides methods for caching with automatic invalidation, cache tagging,
 * cache warming, and monitoring capabilities.
 */
class CacheService
{
    /**
     * In-memory cache for the current request.
     *
     * @var array<string, mixed>
     */
    protected array $memoryCache = [];

    /**
     * Remember a value in cache with multi-layer support.
     *
     * @param  int|null  $ttl  Time to live in seconds
     */
    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        // Check memory cache first
        if (array_key_exists($key, $this->memoryCache)) {
            return $this->memoryCache[$key];
        }

        // Check Redis cache
        $value = Cache::remember($key, $ttl ?? $this->getDefaultTtl(), $callback);

        // Store in memory cache for this request
        $this->memoryCache[$key] = $value;

        return $value;
    }

    /**
     * Remember a value forever (until manually invalidated).
     */
    public function rememberForever(string $key, callable $callback): mixed
    {
        if (array_key_exists($key, $this->memoryCache)) {
            return $this->memoryCache[$key];
        }

        $value = Cache::rememberForever($key, $callback);
        $this->memoryCache[$key] = $value;

        return $value;
    }

    /**
     * Store a value in cache.
     *
     * @param  int|null  $ttl  Time to live in seconds
     */
    public function put(string $key, mixed $value, ?int $ttl = null): bool
    {
        $this->memoryCache[$key] = $value;

        return Cache::put($key, $value, $ttl ?? $this->getDefaultTtl());
    }

    /**
     * Store a value in cache forever.
     */
    public function forever(string $key, mixed $value): bool
    {
        $this->memoryCache[$key] = $value;

        return Cache::forever($key, $value);
    }

    /**
     * Get a value from cache.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->memoryCache)) {
            return $this->memoryCache[$key];
        }

        $value = Cache::get($key, $default);

        if ($value !== null) {
            $this->memoryCache[$key] = $value;
        }

        return $value;
    }

    /**
     * Check if a key exists in cache.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->memoryCache) || Cache::has($key);
    }

    /**
     * Invalidate a single cache key.
     */
    public function forget(string $key): bool
    {
        unset($this->memoryCache[$key]);

        return Cache::forget($key);
    }

    /**
     * Invalidate cache keys matching a pattern.
     *
     * @param  string  $pattern  Pattern with wildcards (e.g., "products.*")
     * @return int Number of keys deleted
     */
    public function invalidate(string $pattern): int
    {
        // Clear matching keys from memory cache
        foreach (array_keys($this->memoryCache) as $key) {
            if ($this->matchesPattern($key, $pattern)) {
                unset($this->memoryCache[$key]);
            }
        }

        // Only use Redis pattern matching if Redis is the cache driver
        $cacheDriver = config('cache.default');
        if ($cacheDriver !== 'redis') {
            // For non-Redis drivers, we can't efficiently pattern match
            // Return 0 as we've already cleared memory cache
            return 0;
        }

        try {
            // Get cache prefix
            $prefix = config('cache.prefix');
            $fullPattern = $prefix.$pattern;

            // Find and delete matching keys in Redis
            $keys = Redis::keys($fullPattern);

            if (empty($keys)) {
                return 0;
            }

            // Remove prefix from keys before deleting
            $keysToDelete = array_map(function ($key) use ($prefix) {
                return str_replace($prefix, '', $key);
            }, $keys);

            foreach ($keysToDelete as $key) {
                Cache::forget($key);
            }

            return count($keysToDelete);
        } catch (\Exception $e) {
            // Log error but don't fail - cache invalidation is not critical
            \Illuminate\Support\Facades\Log::warning('Cache invalidation failed', [
                'pattern' => $pattern,
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    /**
     * Invalidate all cache.
     */
    public function flush(): bool
    {
        $this->memoryCache = [];

        return Cache::flush();
    }

    /**
     * Cache with tags for grouped invalidation.
     *
     * @param  array<string>  $tags
     */
    public function tags(array $tags): \Illuminate\Cache\TaggedCache
    {
        return Cache::tags($tags);
    }

    /**
     * Remember a value with tags.
     *
     * @param  array<string>  $tags
     */
    public function rememberWithTags(array $tags, string $key, callable $callback, ?int $ttl = null): mixed
    {
        return $this->tags($tags)->remember($key, $ttl ?? $this->getDefaultTtl(), $callback);
    }

    /**
     * Invalidate all cache entries with specific tags.
     *
     * @param  array<string>  $tags
     */
    public function flushTags(array $tags): bool
    {
        return Cache::tags($tags)->flush();
    }

    /**
     * Warm cache with critical data.
     *
     * @param  array<string, callable>  $warmers  Array of key => callback pairs
     * @return int Number of items warmed
     */
    public function warm(array $warmers, ?int $ttl = null): int
    {
        $count = 0;

        foreach ($warmers as $key => $callback) {
            try {
                $this->remember($key, $callback, $ttl);
                $count++;
            } catch (\Exception $e) {
                // Log error but continue warming other keys
                \Illuminate\Support\Facades\Log::error("Cache warming failed for key: {$key}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    /**
     * Get cache statistics.
     *
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        $cacheDriver = config('cache.default');

        if ($cacheDriver !== 'redis') {
            return [
                'driver' => $cacheDriver,
                'message' => 'Statistics only available for Redis cache driver',
            ];
        }

        try {
            $info = Redis::info();

            return [
                'driver' => 'redis',
                'memory_used' => $info['used_memory_human'] ?? 'N/A',
                'memory_peak' => $info['used_memory_peak_human'] ?? 'N/A',
                'total_keys' => $info['db0'] ?? 'N/A',
                'hits' => $info['keyspace_hits'] ?? 0,
                'misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => $this->calculateHitRate($info),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Unable to retrieve cache statistics',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate cache hit rate.
     *
     * @param  array<string, mixed>  $info
     */
    protected function calculateHitRate(array $info): string
    {
        $hits = (int) ($info['keyspace_hits'] ?? 0);
        $misses = (int) ($info['keyspace_misses'] ?? 0);
        $total = $hits + $misses;

        if ($total === 0) {
            return '0%';
        }

        $rate = ($hits / $total) * 100;

        return number_format($rate, 2).'%';
    }

    /**
     * Get default TTL from config.
     */
    protected function getDefaultTtl(): int
    {
        return config('cache.ttl.default', 3600);
    }

    /**
     * Get TTL by type.
     *
     * @param  string  $type  'short', 'medium', 'long', 'very_long'
     */
    public function getTtl(string $type = 'default'): int
    {
        return config("cache.ttl.{$type}", $this->getDefaultTtl());
    }

    /**
     * Check if a key matches a pattern.
     */
    protected function matchesPattern(string $key, string $pattern): bool
    {
        $pattern = str_replace('*', '.*', preg_quote($pattern, '/'));

        return (bool) preg_match("/^{$pattern}$/", $key);
    }

    /**
     * Increment a cached value.
     */
    public function increment(string $key, int $value = 1): int|bool
    {
        unset($this->memoryCache[$key]);

        return Cache::increment($key, $value);
    }

    /**
     * Decrement a cached value.
     */
    public function decrement(string $key, int $value = 1): int|bool
    {
        unset($this->memoryCache[$key]);

        return Cache::decrement($key, $value);
    }

    /**
     * Add a value to cache only if it doesn't exist.
     */
    public function add(string $key, mixed $value, ?int $ttl = null): bool
    {
        $result = Cache::add($key, $value, $ttl ?? $this->getDefaultTtl());

        if ($result) {
            $this->memoryCache[$key] = $value;
        }

        return $result;
    }

    /**
     * Get multiple cache values at once.
     *
     * @param  array<string>  $keys
     * @return array<string, mixed>
     */
    public function many(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }

    /**
     * Set multiple cache values at once.
     *
     * @param  array<string, mixed>  $values
     */
    public function putMany(array $values, ?int $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->memoryCache[$key] = $value;
        }

        return Cache::putMany($values, $ttl ?? $this->getDefaultTtl());
    }
}
