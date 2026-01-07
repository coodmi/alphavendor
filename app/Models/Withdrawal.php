<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'vendor_id', 'withdrawal_method_id', 'withdrawal_number', 'amount',
        'status', 'notes', 'admin_notes', 'approved_at', 'completed_at', 'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function withdrawalMethod()
    {
        return $this->belongsTo(WithdrawalMethod::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
