<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view products list
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(?User $user, Product $product): bool
    {
        // Anyone can view active products, admin can view all
        if ($user?->is_admin) {
            return true;
        }

        return $product->is_active && $product->stock;
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the product.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the product.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->is_admin;
    }
}
