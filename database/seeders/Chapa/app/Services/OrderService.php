<?php

namespace App\Services;

use App\DTOs\OrderData;
use App\Exceptions\OrderException;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Order Service.
 *
 * Handles all business logic related to orders including creation,
 * status management, and order queries. Uses the repository pattern
 * for data access and implements proper transaction management.
 *
 * Order Status Workflow:
 * - pending: Order created, awaiting processing
 * - processing: Order is being prepared
 * - shipped: Order has been shipped
 * - delivered: Order has been delivered
 * - completed: Order completed successfully
 * - cancelled: Order was cancelled
 * - refunded: Order was refunded
 *
 * @see OrderRepositoryInterface
 * @see OrderException
 */
class OrderService
{
    /**
     * Valid order statuses.
     *
     * @var array<string>
     */
    public const STATUSES = [
        'pending',
        'processing',
        'shipped',
        'delivered',
        'completed',
        'cancelled',
        'refunded',
    ];

    /**
     * Status transitions map.
     * Defines which statuses can transition to which other statuses.
     *
     * @var array<string, array<string>>
     */
    public const STATUS_TRANSITIONS = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => ['completed', 'refunded'],
        'completed' => ['refunded'],
        'cancelled' => [],
        'refunded' => [],
    ];

    /**
     * Create a new OrderService instance.
     *
     * @param  OrderRepositoryInterface  $repository  Order repository
     */
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    /**
     * Create a new order from cart data.
     *
     * @param  array<string, mixed>  $orderData  Customer and shipping data
     * @param  array<int, array<string, mixed>>  $cartItems  Cart items
     * @param  int|null  $userId  Optional user ID
     * @return Order The created order
     *
     * @throws OrderException When order creation fails
     */
    public function createOrder(array $orderData, array $cartItems, ?int $userId = null): Order
    {
        // Validate cart is not empty
        if (empty($cartItems)) {
            throw OrderException::emptyCart();
        }

        return DB::transaction(function () use ($orderData, $cartItems, $userId) {
            try {
                // Calculate totals (no tax)
                $subtotal = $this->calculateSubtotal($cartItems);
                $total = $subtotal; // No tax added

                // Prepare order data
                $orderAttributes = [
                    'user_id' => $userId,
                    'order_number' => $this->generateOrderNumber(),
                    'shipping_name' => $orderData['customer_name'],
                    'shipping_email' => $orderData['customer_email'],
                    'shipping_phone' => $orderData['customer_phone'],
                    'shipping_address' => $orderData['address'],
                    'shipping_city' => $orderData['city'],
                    'shipping_zip' => $orderData['postal_code'],
                    'shipping_country' => $orderData['country'] ?? 'Bangladesh',
                    'payment_method' => $orderData['payment_method'] ?? 'cod',
                    'notes' => $orderData['notes'] ?? null,
                    'subtotal' => $subtotal,
                    'tax' => 0, // No tax
                    'total' => $total,
                    'status' => config('shop.default_order_status', 'pending'),
                ];

                // Prepare items data
                $items = array_map(function ($item) {
                    return [
                        'product_id' => $item['product_id'] ?? null,
                        'product_title' => $item['product_title'],
                        'product_image' => $item['product_image'] ?? null,
                        'format' => $item['format'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ];
                }, $cartItems);

                // Create order with items using repository
                $order = $this->repository->createWithItems($orderAttributes, $items);

                // Dispatch event for order creation
                event('order.created', $order);

                Log::info('Order created successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $total,
                    'user_id' => $userId,
                ]);

                return $order;
            } catch (\Exception $e) {
                Log::error('Order creation failed', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId,
                ]);

                throw OrderException::creationFailed($e->getMessage());
            }
        });
    }

    /**
     * Create an order from a DTO.
     *
     * @param  OrderData  $data  Order data transfer object
     * @param  int|null  $userId  Optional user ID
     * @return Order The created order
     *
     * @throws OrderException When order creation fails
     */
    public function createFromDto(OrderData $data, ?int $userId = null): Order
    {
        $orderData = [
            'customer_name' => $data->customerName,
            'customer_email' => $data->customerEmail,
            'customer_phone' => $data->customerPhone,
            'address' => $data->shippingAddress,
            'city' => '',
            'postal_code' => '',
            'notes' => $data->notes,
        ];

        return $this->createOrder($orderData, $data->items, $userId);
    }

    /**
     * Calculate subtotal from cart items.
     *
     * @param  array<int, array<string, mixed>>  $cartItems  Cart items
     * @return float The subtotal
     */
    public function calculateSubtotal(array $cartItems): float
    {
        return array_reduce($cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);
    }

    /**
     * Generate a unique order number.
     *
     * @return string The generated order number
     */
    public function generateOrderNumber(): string
    {
        $prefix = 'CHP';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Update order status.
     *
     * @param  Order  $order  The order to update
     * @param  string  $status  New status
     * @return Order The updated order
     *
     * @throws OrderException When status is invalid or transition not allowed
     */
    public function updateStatus(Order $order, string $status): Order
    {
        // Validate status
        if (! $this->isValidStatus($status)) {
            throw OrderException::invalidStatus($status);
        }

        // Validate transition
        if (! $this->canTransitionTo($order, $status)) {
            throw OrderException::invalidStatusTransition($order->status, $status);
        }

        $oldStatus = $order->status;

        $updatedOrder = $this->repository->updateStatus($order, $status);

        // Dispatch event for status change
        event('order.status_changed', [
            'order' => $updatedOrder,
            'old_status' => $oldStatus,
            'new_status' => $status,
        ]);

        Log::info('Order status updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $status,
        ]);

        return $updatedOrder;
    }

    /**
     * Cancel an order.
     *
     * @param  Order  $order  The order to cancel
     * @param  string|null  $reason  Cancellation reason
     * @return Order The cancelled order
     *
     * @throws OrderException When order cannot be cancelled
     */
    public function cancel(Order $order, ?string $reason = null): Order
    {
        if (! $this->canTransitionTo($order, 'cancelled')) {
            throw OrderException::cannotCancel($order->order_number, $order->status);
        }

        $cancelledOrder = $this->repository->cancel($order, $reason);

        // Dispatch event for order cancellation
        event('order.cancelled', [
            'order' => $cancelledOrder,
            'reason' => $reason,
        ]);

        Log::info('Order cancelled', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'reason' => $reason,
        ]);

        return $cancelledOrder;
    }

    /**
     * Check if a status is valid.
     *
     * @param  string  $status  The status to check
     */
    public function isValidStatus(string $status): bool
    {
        return in_array($status, self::STATUSES, true);
    }

    /**
     * Check if an order can transition to a given status.
     *
     * @param  Order  $order  The order
     * @param  string  $newStatus  The target status
     */
    public function canTransitionTo(Order $order, string $newStatus): bool
    {
        $currentStatus = $order->status;

        if (! isset(self::STATUS_TRANSITIONS[$currentStatus])) {
            return false;
        }

        return in_array($newStatus, self::STATUS_TRANSITIONS[$currentStatus], true);
    }

    /**
     * Get allowed transitions for an order.
     *
     * @param  Order  $order  The order
     * @return array<string>
     */
    public function getAllowedTransitions(Order $order): array
    {
        return self::STATUS_TRANSITIONS[$order->status] ?? [];
    }

    /**
     * Find an order by ID.
     *
     * @param  int  $id  Order ID
     * @param  array<string>  $relations  Relations to eager load
     */
    public function find(int $id, array $relations = []): ?Order
    {
        /** @var Order|null */
        return $this->repository->find($id, $relations);
    }

    /**
     * Find an order by order number.
     *
     * @param  string  $orderNumber  The order number
     * @param  array<string>  $relations  Relations to eager load
     */
    public function findByOrderNumber(string $orderNumber, array $relations = []): ?Order
    {
        return $this->repository->findByOrderNumber($orderNumber, $relations);
    }

    /**
     * Find an order by order number or throw exception.
     *
     * @param  string  $orderNumber  The order number
     * @param  array<string>  $relations  Relations to eager load
     *
     * @throws OrderException When order is not found
     */
    public function findByOrderNumberOrFail(string $orderNumber, array $relations = []): Order
    {
        $order = $this->findByOrderNumber($orderNumber, $relations);

        if (! $order) {
            throw OrderException::notFound($orderNumber);
        }

        return $order;
    }

    /**
     * Get orders for a user.
     *
     * @param  int  $userId  User ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function getByUser(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getByUser($userId, $filters, $perPage);
    }

    /**
     * Get orders by status.
     *
     * @param  string  $status  Order status
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function getByStatus(string $status, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getByStatus($status, $filters, $perPage);
    }

    /**
     * Get recent orders.
     *
     * @param  int  $limit  Number of orders
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Order>
     */
    public function getRecent(int $limit = 10, array $relations = []): Collection
    {
        return $this->repository->getRecent($limit, $relations);
    }

    /**
     * Get paginated orders with filters.
     *
     * @param  array<string, mixed>  $filters  Filter criteria
     * @param  int  $perPage  Items per page
     * @param  array<string>  $relations  Relations to eager load
     */
    public function paginate(array $filters = [], int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage, $relations);
    }

    /**
     * Search orders.
     *
     * @param  string  $query  Search query
     * @param  array<string, mixed>  $filters  Additional filters
     * @param  int  $perPage  Items per page
     */
    public function search(string $query, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($query, $filters, $perPage);
    }

    /**
     * Get order statistics for dashboard.
     *
     * @param  string|null  $startDate  Optional start date
     * @param  string|null  $endDate  Optional end date
     * @return array<string, mixed>
     */
    public function getStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->repository->getStatistics($startDate, $endDate);
    }

    /**
     * Get total revenue.
     *
     * @param  string|null  $startDate  Optional start date
     * @param  string|null  $endDate  Optional end date
     */
    public function getTotalRevenue(?string $startDate = null, ?string $endDate = null): float
    {
        return $this->repository->getTotalRevenue($startDate, $endDate);
    }

    /**
     * Get order counts by status.
     *
     * @return array<string, int>
     */
    public function getCountByStatus(): array
    {
        return $this->repository->getCountByStatus();
    }

    /**
     * Get pending orders older than specified hours.
     *
     * @param  int  $hoursOld  Hours since creation
     * @return Collection<int, Order>
     */
    public function getPendingOlderThan(int $hoursOld = 24): Collection
    {
        return $this->repository->getPendingOlderThan($hoursOld);
    }

    /**
     * Get orders within a date range.
     *
     * @param  string  $startDate  Start date (Y-m-d format)
     * @param  string  $endDate  End date (Y-m-d format)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Collection<int, Order>
     */
    public function getByDateRange(string $startDate, string $endDate, array $filters = []): Collection
    {
        return $this->repository->getByDateRange($startDate, $endDate, $filters);
    }
}
