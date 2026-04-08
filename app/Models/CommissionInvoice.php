<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'order_id',
        'seller_id',
        'invoice_date',
        'order_amount',
        'delivery_charge',
        'category_commission_rate',
        'category_commission_amount',
        'cod_commission_rate',
        'cod_commission_amount',
        'total_commission',
        'penalty_amount',
        'total_deduction',
        'net_vendor_earning',
        'status',
        'pdf_path'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'order_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'category_commission_rate' => 'decimal:2',
        'category_commission_amount' => 'decimal:2',
        'cod_commission_rate' => 'decimal:2',
        'cod_commission_amount' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_vendor_earning' => 'decimal:2'
    ];

    /**
     * Get the order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the seller
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get invoice items
     */
    public function items()
    {
        return $this->hasMany(CommissionInvoiceItem::class, 'invoice_id');
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -5)) + 1 : 1;
        return 'INV-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Mark as paid
     */
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
    }
}
