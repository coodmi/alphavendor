<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'vendor_id', 'subtotal', 'commission_amount',
        'commission_rate', 'vendor_earning', 'total', 'status', 'payment_status',
        'payment_method', 'shipping_address', 'shipping_city', 'shipping_state',
        'shipping_zip', 'shipping_country', 'phone', 'notes',
        'paperfly_tracking_number', 'paperfly_merchant_order_ref', 'delivery_status',
        'tracking_history', 'picked_at', 'in_transit_at', 'delivered_at'
    ];

    protected $casts = [
        'picked_at' => 'datetime',
        'in_transit_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function manualPayment()
    {
        return $this->hasOne(ManualPayment::class);
    }
}
