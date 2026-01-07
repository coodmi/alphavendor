<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'country',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the products for this supplier location
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
