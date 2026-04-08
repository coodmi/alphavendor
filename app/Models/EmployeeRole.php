<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'access_level',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get employees with this role
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'employee_role_id');
    }

    /**
     * Scope to get only active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get access level badge color
     */
    public function getAccessLevelColorAttribute(): string
    {
        return match($this->access_level) {
            'basic' => 'bg-blue-100 text-blue-700',
            'extended' => 'bg-purple-100 text-purple-700',
            'full' => 'bg-green-100 text-green-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get access level label
     */
    public function getAccessLevelLabelAttribute(): string
    {
        return match($this->access_level) {
            'basic' => 'Basic Access',
            'extended' => 'Extended Access',
            'full' => 'Full Access',
            default => 'Unknown',
        };
    }
}
