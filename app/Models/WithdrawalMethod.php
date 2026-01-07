<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    protected $fillable = [
        'vendor_id', 'type', 'account_name', 'account_number', 'bank_name',
        'routing_number', 'swift_code', 'paypal_email', 'additional_details', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
