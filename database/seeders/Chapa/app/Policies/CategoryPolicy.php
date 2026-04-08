<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any categories.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view categories list
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(?User $user, Category $category): bool
    {
        // Anyone can view active categories, admin can view all
        if ($user?->is_admin) {
            return true;
        }

        return $category->is_active;
    }

    /**
     * Determine whether the user can create categories.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, Category $category): bool
    {
        // Can only delete if category has no products
        if ($category->products()->exists()) {
            return false;
        }

        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->is_admin && ! $category->products()->exists();
    }
}
