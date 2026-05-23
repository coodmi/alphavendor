<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySetting extends Model
{
    protected $fillable = ['extra_per_kg_charge'];

    protected $casts = [
        'extra_per_kg_charge' => 'decimal:2',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['extra_per_kg_charge' => 30]);
    }
}
