<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Order repository interface defining order-specific data access operations.
 *
 * This interface extends the base repository interface and adds
 * order-specific methods including complex queries and transaction handling.
 */
interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Find an order by its order number.
     *
     * @param  string  $orderNumber  The order number
     * @param  array<string>  $relations  Relations to eager load
     */
    public function findByOrderNumber(string $orderNumber, array $relations = []): ?Order;

    /**
     * Get orders for a specific user.
     *
     * @param  User|int  $user  The user or user ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function getByUser(User|int $user, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get orders by status.
     *
     * @param  string  $status  The order status
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function getByStatus(string $status, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get recent orders.
     *
     * @param  int  $limit  Number of orders to return
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Order>
     */
    public function getRecent(int $limit = 10, array $relations = []): Collection;

    /**
     * Create an order with items in a transaction.
     *
     * @param  array<string, mixed>  $orderData  Order attributes
     * @param  array<int, array<string, mixed>>  $items  Order items data
     *
     * @throws \Exception When transaction fails
     */
    public function createWithItems(array $orderData, array $items): Order;

    /**
     * Update order status.
     *
     * @param  Order  $order  The order to update
     * @param  string  $status  New status
     */
    public function updateStatus(Order $order, string $status): Order;

    /**
     * Get orders within a date range.
     *
     * @param  string  $startDate  Start date (Y-m-d format)
     * @param  string  $endDate  End date (Y-m-d format)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Collection<int, Order>
     */
    public function getByDateRange(string $startDate, string $endDate, array $filters = []): Collection;

    /**
     * Get order statistics.
     *
     * @param  string|null  $startDate  Optional start date
     * @param  string|null  $endDate  Optional end date
     * @return array<string, mixed> Statistics array
     */
    public function getStatistics(?string $startDate = null, ?string $endDate = null): array;

    /**
     * Get total revenue.
     *
     * @param  string|null  $startDate  Optional start date
     * @param  string|null  $endDate  Optional end date
     */
    public function getTotalRevenue(?string $startDate = null, ?string $endDate = null): float;

    /**
     * Get orders count by status.
     *
     * @return array<string, int> Array of status => count
     */
    public function getCountByStatus(): array;

    /**
     * Cancel an order.
     *
     * @param  Order  $order  The order to cancel
     * @param  string|null  $reason  Cancellation reason
     */
    public function cancel(Order $order, ?string $reason = null): Order;

    /**
     * Get pending orders that need attention.
     *
     * @param  int  $hoursOld  Hours since creation
     * @return Collection<int, Order>
     */
    public function getPendingOlderThan(int $hoursOld = 24): Collection;

    /**
     * Search orders by various criteria.
     *
     * @param  string  $query  Search query (order number, customer name, email)
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
