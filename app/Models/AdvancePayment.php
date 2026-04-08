<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'vendor_id',
        'quantity',
        'total_amount',
        'advance_percentage',
        'advance_amount',
        'remaining_amount',
        'payment_method',
        'contact_number',
        'notes',
        'status',
        'transaction_id',
        'bank_name',
        'bank_account_holder',
        'bank_account_number',
        'admin_notes',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-blue-100 text-blue-700',
            'rejected' => 'bg-red-100 text-red-700',
            'paid' => 'bg-green-100 text-green-700',
            'completed' => 'bg-purple-100 text-purple-700',
            'cancelled' => 'bg-gray-100 text-gray-700',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-700';
    }
}
