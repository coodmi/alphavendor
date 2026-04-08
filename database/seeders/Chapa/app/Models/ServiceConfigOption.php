<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceConfigOption extends Model
{
    protected $fillable = [
        'service_product_id',
        'option_name',
        'option_type',
        'option_values',
        'option_prices',
        'default_value',
        'display_order',
        'is_required',
    ];

    protected $casts = [
        'option_values' => 'array',
        'option_prices' => 'array',
        'is_required' => 'boolean',
    ];

    public function serviceProduct()
    {
        return $this->belongsTo(ServiceProduct::class);
    }
}
