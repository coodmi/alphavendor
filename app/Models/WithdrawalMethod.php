<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    protected $fillable = [
        'vendor_id', 'type', 'account_type', 'account_name', 'account_number', 'bank_name',
        'branch_name', 'routing_number', 'swift_code', 'bkash_number', 'nagad_number',
        'rocket_number', 'additional_details', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    const TYPE_BANK   = 'bank';
    const TYPE_BKASH  = 'bkash';
    const TYPE_NAGAD  = 'nagad';
    const TYPE_ROCKET = 'rocket';

    const ACCOUNT_PERSONAL = 'personal';
    const ACCOUNT_MERCHANT = 'merchant';

    public static function getTypes()
    {
        return [
            self::TYPE_BANK   => 'Bank Transfer',
            self::TYPE_BKASH  => 'bKash',
            self::TYPE_NAGAD  => 'Nagad',
            self::TYPE_ROCKET => 'Rocket',
        ];
    }

    public function getTypeNameAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function isMerchant()
    {
        return $this->account_type === self::ACCOUNT_MERCHANT;
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
