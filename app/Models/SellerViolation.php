<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerViolation extends Model
{
    protected $fillable = [
        'seller_id',
        'order_id',
        'rule_id',
        'violation_date',
        'penalty_amount',
        'status',
        'admin_notes',
        'notified_at',
        'resolved_at'
    ];

    protected $casts = [
        'violation_date' => 'date',
        'penalty_amount' => 'decimal:2',
        'notified_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    /**
     * Get the seller
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the violation rule
     */
    public function rule()
    {
        return $this->belongsTo(ViolationRule::class);
    }

    /**
     * Get notifications for this violation
     */
    public function notifications()
    {
        return $this->hasMany(ViolationNotification::class, 'violation_id');
    }

    /**
     * Mark as applied
     */
    public function markAsApplied()
    {
        $this->update([
            'status' => 'applied',
            'resolved_at' => now()
        ]);
    }

    /**
     * Mark as waived
     */
    public function markAsWaived($adminNotes = null)
    {
        $this->update([
            'status' => 'waived',
            'admin_notes' => $adminNotes,
            'resolved_at' => now()
        ]);
    }

    /**
     * Get pending violations for a seller
     */
    public static function getPendingForSeller($sellerId)
    {
        return self::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->with('rule')
            ->get();
    }

    /**
     * Get pending violations for an order
     */
    public static function getPendingForOrder($orderId)
    {
        return self::where('order_id', $orderId)
            ->where('status', 'pending')
            ->with('rule')
            ->get();
    }
}
