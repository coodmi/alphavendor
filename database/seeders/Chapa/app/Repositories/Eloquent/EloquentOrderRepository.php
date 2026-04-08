<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Eloquent implementation of the Order repository.
 *
 * Provides order-specific data access with transaction handling and complex queries.
 */
class EloquentOrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * Order status constants.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    /**
     * Create a new order repository instance.
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
        $this->cachePrefix = 'orders';
        // Orders are less cacheable due to frequent status changes
        $this->cacheTtl = 300; // 5 minutes
    }

    /**
     * {@inheritdoc}
     */
    public function findByOrderNumber(string $orderNumber, array $relations = []): ?Order
    {
        $query = $this->model->newQuery();

        // Default relations for orders
        $defaultRelations = ['items', 'user'];
        $relations = array_merge($defaultRelations, $relations);
        $query->with($relations);

        /** @var Order|null $result */
        return $query->where('order_number', $orderNumber)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getByUser(User|int $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $userId = $user instanceof User ? $user->id : $user;

        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->with(['items'])
            ->orderBy('created_at', 'desc');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getByStatus(string $status, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->where('status', $status)
            ->with(['items', 'user'])
            ->orderBy('created_at', 'desc');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function getRecent(int $limit = 10, array $relations = []): Collection
    {
        $cacheKey = $this->getCacheKey("recent.{$limit}");

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $defaultRelations = ['items', 'user'];
        $relations = array_merge($defaultRelations, $relations);

        $result = $this->model->newQuery()
            ->with($relations)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function createWithItems(array $orderData, array $items): Order
    {
        DB::beginTransaction();

        try {
            // Generate order number if not provided
            if (! isset($orderData['order_number'])) {
                $orderData['order_number'] = $this->generateOrderNumber();
            }

            // Set default status if not provided
            if (! isset($orderData['status'])) {
                $orderData['status'] = self::STATUS_PENDING;
            }

            /** @var Order $order */
            $order = $this->model->newInstance($orderData);
            $order->save();

            // Create order items
            foreach ($items as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            $this->clearCache();

            // Return order with items loaded
            return $order->load(['items', 'user']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = $status;
        $order->save();

        $this->clearCache();

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function getByDateRange(string $startDate, string $endDate, array $filters = []): Collection
    {
        $query = $this->model->newQuery()
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->with(['items', 'user'])
            ->orderBy('created_at', 'desc');

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        $query = $this->model->newQuery();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        }

        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->where('status', '!=', self::STATUS_CANCELLED)->sum('total');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $completedOrders = (clone $query)->where('status', self::STATUS_COMPLETED)->count();
        $cancelledOrders = (clone $query)->where('status', self::STATUS_CANCELLED)->count();

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => (float) $totalRevenue,
            'average_order_value' => round($averageOrderValue, 2),
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'completion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalRevenue(?string $startDate = null, ?string $endDate = null): float
    {
        $query = $this->model->newQuery()
            ->where('status', '!=', self::STATUS_CANCELLED);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        }

        return (float) $query->sum('total');
    }

    /**
     * {@inheritdoc}
     */
    public function getCountByStatus(): array
    {
        $cacheKey = $this->getCacheKey('count_by_status');

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $result = $this->model->newQuery()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $result, $this->cacheTtl);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(Order $order, ?string $reason = null): Order
    {
        DB::beginTransaction();

        try {
            $order->status = self::STATUS_CANCELLED;

            if ($reason) {
                $notes = $order->notes ?? '';
                $order->notes = trim($notes."\nCancellation reason: ".$reason);
            }

            $order->save();

            DB::commit();

            $this->clearCache();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPendingOlderThan(int $hoursOld = 24): Collection
    {
        $cutoffTime = now()->subHours($hoursOld);

        return $this->model->newQuery()
            ->where('status', self::STATUS_PENDING)
            ->where('created_at', '<', $cutoffTime)
            ->with(['items', 'user'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $searchQuery = $this->model->newQuery()
            ->where(function ($q) use ($query) {
                $q->where('order_number', 'like', "%{$query}%")
                    ->orWhere('shipping_name', 'like', "%{$query}%")
                    ->orWhere('shipping_email', 'like', "%{$query}%")
                    ->orWhere('shipping_phone', 'like', "%{$query}%");
            })
            ->with(['items', 'user'])
            ->orderBy('created_at', 'desc');

        $this->applyFilters($searchQuery, $filters);

        return $searchQuery->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(array $filters = [], int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Default relations for orders
        $defaultRelations = ['items', 'user'];
        $relations = array_merge($defaultRelations, $relations);
        $query->with($relations);

        // Handle special filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
            unset($filters['status']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
            unset($filters['user_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from'].' 00:00:00');
            unset($filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to'].' 23:59:59');
            unset($filters['date_to']);
        }

        if (isset($filters['min_total'])) {
            $query->where('total', '>=', $filters['min_total']);
            unset($filters['min_total']);
        }

        if (isset($filters['max_total'])) {
            $query->where('total', '<=', $filters['max_total']);
            unset($filters['max_total']);
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id, array $relations = []): ?Model
    {
        // Add default relations for orders
        if (empty($relations)) {
            $relations = ['items', 'user'];
        }

        return parent::find($id, $relations);
    }

    /**
     * Generate a unique order number.
     */
    protected function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.strtoupper(Str::random(8));
        } while ($this->model->newQuery()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * {@inheritdoc}
     */
    protected function clearCache(): void
    {
        parent::clearCache();

        // Clear order-specific caches
        Cache::forget($this->getCacheKey('recent.10'));
        Cache::forget($this->getCacheKey('count_by_status'));
    }
}
