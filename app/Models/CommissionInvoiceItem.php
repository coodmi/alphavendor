<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionInvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_type',
        'description',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    /**
     * Get the invoice
     */
    public function invoice()
    {
        return $this->belongsTo(CommissionInvoice::class, 'invoice_id');
    }

    /**
     * Get formatted item type
     */
    public function getFormattedTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->item_type));
    }
}
