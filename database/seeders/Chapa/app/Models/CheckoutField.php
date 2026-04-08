<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutField extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'field_key',
        'label',
        'placeholder',
        'type',
        'is_required',
        'is_visible',
        'sort_order',
        'options',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'options' => 'array',
        'sort_order' => 'integer',
    ];
}
