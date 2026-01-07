<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'code',
        'description',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'certificate_number',
        'document',
        'is_active',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the vendor that owns the certification
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get products with this certification
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'certification_product');
    }
}
