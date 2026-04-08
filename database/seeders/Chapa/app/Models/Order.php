<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Order Model.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $order_number
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $shipping_name
 * @property string|null $shipping_email
 * @property string|null $shipping_phone
 * @property string|null $shipping_country
 * @property string|null $shipping_address
 * @property string|null $shipping_city
 * @property string|null $shipping_state
 * @property string|null $shipping_zip
 * @property string|null $payment_method
 * @property string|null $notes
 * @property float $subtotal
 * @property float $tax
 * @property float $total
 * @property float|null $total_amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_country',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'payment_method',
        'notes',
        'subtotal',
        'tax',
        'total',
        'status',
        'payment_phone',
        'transaction_id',
        'payment_screenshot_path',
        'is_payment_verified',
    ];

    /**
     * Eager load relationships by default to prevent N+1 queries.
     *
     * @var array
     */
    protected $with = ['items'];

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'is_payment_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notes()
    {
        return $this->hasMany(OrderNote::class);
    }

    /**
     * Scope for service orders (orders containing service products).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeServiceOrders($query)
    {
        return $query->whereHas('items', function ($q) {
            $q->where('product_type', 'service');
        });
    }

    /**
     * Scope for shop orders (orders containing products from shop page).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShopOrders($query)
    {
        return $query->whereHas('items', function ($q) {
            $q->where('product_type', 'product');
        });
    }

    /**
     * Scope for orders by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for orders by date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            return $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            return $query->where('created_at', '<=', $endDate);
        }
        
        return $query;
    }

    /**
     * Scope for searching orders by ID or customer name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('id', $term)
              ->orWhere('order_number', 'LIKE', "%{$term}%")
              ->orWhere('shipping_name', 'LIKE', "%{$term}%")
              ->orWhereHas('user', function ($userQuery) use ($term) {
                  $userQuery->where('name', 'LIKE', "%{$term}%");
              });
        });
    }

    /**
     * Scope for orders by user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for recent orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for orders within date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for pending orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the customer name (alias for shipping_name).
     *
     * @return string|null
     */
    public function getCustomerNameAttribute()
    {
        return $this->shipping_name;
    }

    /**
     * Get the total amount (alias for total).
     *
     * @return float
     */
    public function getTotalAmountAttribute()
    {
        return $this->total;
    }
}
