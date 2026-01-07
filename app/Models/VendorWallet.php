<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorWallet extends Model
{
    protected $fillable = [
        'vendor_id', 'balance', 'pending_balance', 'total_earned', 'total_withdrawn'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vendor_id', 'vendor_id');
    }
}
