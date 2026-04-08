<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Metrics Service.
 *
 * Tracks and retrieves business metrics and performance data.
 */
class MetricsService
{
    /**
     * Cache TTL for metrics (in seconds).
     */
    private const METRICS_TTL = 300; // 5 minutes

    /**
     * Record a product view.
     */
    public function recordProductView(Product $product): void
    {
        // Increment view count in cache
        $key = "metrics:product_views:{$product->id}";
        Cache::increment($key);

        // Add to popular products sorted set (using timestamp as score)
        $popularKey = 'metrics:popular_products';
        $currentScore = Cache::get("{$popularKey}:{$product->id}", 0);
        Cache::put("{$popularKey}:{$product->id}", $currentScore + 1, now()->addDays(30));

        // Update product popularity in database (async via job would be better)
        $product->increment('popularity');
    }

    /**
     * Get product view count.
     */
    public function getProductViews(Product $product): int
    {
        $key = "metrics:product_views:{$product->id}";

        return (int) Cache::get($key, 0);
    }

    /**
     * Get popular products.
     */
    public function getPopularProducts(int $limit = 10): Collection
    {
        return Cache::remember(
            'metrics:popular_products_list',
            self::METRICS_TTL,
            function () use ($limit) {
                return Product::with('category')
                    ->active()
                    ->orderBy('popularity', 'desc')
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Record an order metric.
     */
    public function recordOrder(Order $order): void
    {
        $date = now()->format('Y-m-d');

        // Increment daily order count
        Cache::increment("metrics:orders:count:{$date}");

        // Add to daily revenue
        $revenueKey = "metrics:orders:revenue:{$date}";
        $currentRevenue = (float) Cache::get($revenueKey, 0);
        Cache::put($revenueKey, $currentRevenue + $order->total, now()->addDays(30));

        // Track order by status
        Cache::increment("metrics:orders:status:{$order->status}:{$date}");
    }

    /**
     * Get daily order count.
     */
    public function getDailyOrderCount(?string $date = null): int
    {
        $date = $date ?? now()->format('Y-m-d');

        return (int) Cache::get("metrics:orders:count:{$date}", 0);
    }

    /**
     * Get daily revenue.
     */
    public function getDailyRevenue(?string $date = null): float
    {
        $date = $date ?? now()->format('Y-m-d');

        return (float) Cache::get("metrics:orders:revenue:{$date}", 0);
    }

    /**
     * Get order metrics by status.
     */
    public function getOrdersByStatus(string $status, ?string $date = null): int
    {
        $date = $date ?? now()->format('Y-m-d');

        return (int) Cache::get("metrics:orders:status:{$status}:{$date}", 0);
    }

    /**
     * Get performance timing for an operation.
     *
     * @return mixed
     */
    public function timeOperation(string $operation, callable $callback)
    {
        $startTime = microtime(true);

        try {
            $result = $callback();

            $executionTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds

            // Store timing metric
            $this->recordTiming($operation, $executionTime);

            return $result;
        } catch (\Exception $e) {
            $executionTime = (microtime(true) - $startTime) * 1000;
            $this->recordTiming($operation, $executionTime, true);

            throw $e;
        }
    }

    /**
     * Record timing metric.
     */
    protected function recordTiming(string $operation, float $time, bool $failed = false): void
    {
        $key = "metrics:timing:{$operation}";

        // Store last 100 timings
        $timings = Cache::get($key, []);
        $timings[] = [
            'time' => $time,
            'failed' => $failed,
            'timestamp' => now()->timestamp,
        ];

        // Keep only last 100 entries
        if (count($timings) > 100) {
            $timings = array_slice($timings, -100);
        }

        Cache::put($key, $timings, now()->addHours(24));
    }

    /**
     * Get average timing for an operation.
     */
    public function getAverageTiming(string $operation): ?float
    {
        $key = "metrics:timing:{$operation}";
        $timings = Cache::get($key, []);

        if (empty($timings)) {
            return null;
        }

        $times = array_column($timings, 'time');

        return array_sum($times) / count($times);
    }

    /**
     * Get dashboard metrics summary.
     */
    public function getDashboardMetrics(): array
    {
        return Cache::remember(
            'metrics:dashboard',
            self::METRICS_TTL,
            function () {
                $today = now()->format('Y-m-d');
                $yesterday = now()->subDay()->format('Y-m-d');

                return [
                    'orders' => [
                        'today' => $this->getDailyOrderCount($today),
                        'yesterday' => $this->getDailyOrderCount($yesterday),
                        'pending' => Order::where('status', 'pending')->count(),
                        'processing' => Order::where('status', 'processing')->count(),
                    ],
                    'revenue' => [
                        'today' => $this->getDailyRevenue($today),
                        'yesterday' => $this->getDailyRevenue($yesterday),
                        'month' => Order::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('total'),
                    ],
                    'products' => [
                        'total' => Product::count(),
                        'active' => Product::where('is_active', true)->count(),
                        'out_of_stock' => Product::where('stock', false)->count(),
                    ],
                    'popular_products' => $this->getPopularProducts(5),
                ];
            }
        );
    }

    /**
     * Clear all metrics cache.
     */
    public function clearMetrics(): void
    {
        // This would be better implemented with cache tags
        // For now, we'll just clear the dashboard cache
        Cache::forget('metrics:dashboard');
        Cache::forget('metrics:popular_products_list');
    }

    /**
     * Get slow query metrics from database.
     *
     * @param  int  $threshold  Threshold in milliseconds
     */
    public function getSlowQueries(int $threshold = 100): Collection
    {
        // This would typically query Telescope's entries table
        // For now, return empty collection
        return collect([]);
    }

    /**
     * Get error rate metrics.
     */
    public function getErrorRate(?string $date = null): array
    {
        $date = $date ?? now()->format('Y-m-d');

        return [
            'total_requests' => (int) Cache::get("metrics:requests:{$date}", 0),
            'total_errors' => (int) Cache::get("metrics:errors:{$date}", 0),
            'error_rate' => $this->calculateErrorRate($date),
        ];
    }

    /**
     * Calculate error rate.
     */
    protected function calculateErrorRate(string $date): float
    {
        $totalRequests = (int) Cache::get("metrics:requests:{$date}", 0);
        $totalErrors = (int) Cache::get("metrics:errors:{$date}", 0);

        if ($totalRequests === 0) {
            return 0.0;
        }

        return ($totalErrors / $totalRequests) * 100;
    }

    /**
     * Record a request.
     */
    public function recordRequest(): void
    {
        $date = now()->format('Y-m-d');
        Cache::increment("metrics:requests:{$date}");
    }

    /**
     * Record an error.
     */
    public function recordError(): void
    {
        $date = now()->format('Y-m-d');
        Cache::increment("metrics:errors:{$date}");
    }
}
