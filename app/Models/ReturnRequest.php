<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'order_item_id',
        'user_id',
        'vendor_id',
        'product_id',
        'type',
        'reason',
        'reason_details',
        'quantity',
        'amount',
        'status',
        'customer_notes',
        'admin_notes',
        'vendor_notes',
        'images',
        'refund_method',
        'refund_amount',
        'refund_processed_at',
        'refund_transaction_id',
        'exchange_product_id',
        'exchange_product_variant',
        'return_tracking_number',
        'return_courier',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'images' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'refund_processed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            if (!$return->return_number) {
                $return->return_number = 'RET-' . strtoupper(uniqid());
            }
        });
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function exchangeProduct()
    {
        return $this->belongsTo(Product::class, 'exchange_product_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Helper methods
    public function canBeApproved()
    {
        return in_array($this->status, ['pending']);
    }

    public function canBeRejected()
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    public function canBeRefunded()
    {
        return $this->status === 'received' && $this->type === 'refund';
    }

    public function isCompleted()
    {
        return in_array($this->status, ['refunded', 'completed', 'cancelled']);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'shipped_back' => 'bg-indigo-100 text-indigo-800',
            'received' => 'bg-teal-100 text-teal-800',
            'refunded' => 'bg-green-100 text-green-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeBadgeClass()
    {
        return match($this->type) {
            'return' => 'bg-blue-100 text-blue-800',
            'refund' => 'bg-green-100 text-green-800',
            'exchange' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
