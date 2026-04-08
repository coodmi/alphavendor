<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'permissions',
        'is_system_role',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system_role' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get users with this role
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role', 'key');
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []) || in_array('all_permissions', $this->permissions ?? []);
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions()
    {
        return [
            'view_products' => 'View Products',
            'create_products' => 'Create Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'manage_orders' => 'Manage Orders',
            'view_analytics' => 'View Analytics',
            'manage_users' => 'Manage Users',
            'export_docs' => 'Export Documents',
            'import_docs' => 'Import Documents',
            'bulk_pricing' => 'Bulk Pricing',
            'place_orders' => 'Place Orders',
            'all_permissions' => 'All Permissions',
        ];
    }
}