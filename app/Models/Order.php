<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'vendor_id', 'subtotal', 'commission_amount',
        'commission_rate', 'cod_commission_amount', 'cod_commission_rate', 
        'vendor_earning', 'total', 'total_amount', 'delivery_charge', 'status', 'payment_status',
        'payment_method', 'shipping_address', 'shipping_city', 'shipping_state',
        'shipping_zip', 'shipping_country', 'phone', 'notes',
        'customer_name', 'customer_phone', 'customer_email',
        'paid_at', 'confirmed_at', 'penalty_applied',
        'paperfly_tracking_number', 'paperfly_merchant_order_ref', 'delivery_status',
        'tracking_history', 'picked_at', 'in_transit_at', 'delivered_at'
    ];

    protected $casts = [
        'picked_at'       => 'datetime',
        'in_transit_at'   => 'datetime',
        'delivered_at'    => 'datetime',
        'paid_at'         => 'datetime',
        'confirmed_at'    => 'datetime',
        'penalty_applied' => 'boolean',
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

    public function orderItems()
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

    public function violations()
    {
        return $this->hasMany(SellerViolation::class);
    }

    /**
     * Alias accessor for commission_amount
     */
    public function getCommissionAttribute()
    {
        return $this->commission_amount ?? 0;
    }

    /**
     * Total commission (category + COD)
     */
    public function getTotalCommissionAttribute()
    {
        return ($this->commission_amount ?? 0) + ($this->cod_commission_amount ?? 0);
    }

    /**
     * Check if this order belongs to a wholesale or import vendor.
     * Covers: wholesaler, exporter, importer roles.
     */
    public function isWholesaleOrImport(): bool
    {
        return in_array($this->vendor->role ?? '', ['wholesaler', 'exporter', 'importer']);
    }

    /**
     * Status change audit logs for this order.
     */
    public function statusLogs()
    {
        return $this->hasMany(\App\Models\OrderStatusLog::class);
    }
}
