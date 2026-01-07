<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'vendor_id', 'subtotal', 'commission_amount',
        'commission_rate', 'vendor_earning', 'total', 'status', 'payment_status',
        'payment_method', 'shipping_address', 'shipping_city', 'shipping_state',
        'shipping_zip', 'shipping_country', 'phone', 'notes'
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
}
