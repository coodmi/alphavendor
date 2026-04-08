<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->is_admin || $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create orders
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update order status.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        return $user->is_admin;
    }
}
