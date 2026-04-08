<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_name',
        'instructions',
        'logo',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get active payment methods ordered by display_order.
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }
}
