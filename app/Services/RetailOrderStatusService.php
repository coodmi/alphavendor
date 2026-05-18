<?php

namespace App\Services;

use App\Exceptions\InvalidOrderTransitionException;
use App\Exceptions\UnauthorisedOrderTransitionException;
use App\Helpers\OrderStatus;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\User;

class RetailOrderStatusService
{
    /**
     * Validate and apply a status transition for a retail order.
     *
     * @throws InvalidOrderTransitionException        if the transition is not in the allowed map
     * @throws UnauthorisedOrderTransitionException   if the actor's role cannot set the target status
     */
    public function transition(Order $order, string $newStatus, User $actor): void
    {
        $currentStatus = $order->status;
        $actorRole     = $actor->role;

        // 1. Check the transition is allowed from the current status
        $allowedNext = OrderStatus::retailTransitions()[$currentStatus] ?? [];
        if (!in_array($newStatus, $allowedNext)) {
            throw new InvalidOrderTransitionException(
                "Transition from '{$currentStatus}' to '{$newStatus}' is not permitted."
            );
        }

        // 2. Check the actor's role is permitted to set the target status
        $permittedRoles = OrderStatus::retailRolePermissions()[$newStatus] ?? [];
        if (!in_array($actorRole, $permittedRoles)) {
            throw new UnauthorisedOrderTransitionException(
                "Your role ('{$actorRole}') is not authorised to set status '{$newStatus}'."
            );
        }

        // 3. Apply the transition
        $order->update(['status' => $newStatus]);

        // 4. Write audit log — non-blocking
        try {
            OrderStatusLog::create([
                'order_id'    => $order->id,
                'from_status' => $currentStatus,
                'to_status'   => $newStatus,
                'actor_id'    => $actor->id,
                'actor_role'  => $actorRole,
                'created_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error("RetailOrderStatusLog write failed for order {$order->id}: " . $e->getMessage());
        }

        // 5. Notify customer
        try {
            if ($order->user) {
                \App\Models\Notification::create([
                    'user_id' => $order->user->id,
                    'title'   => 'Order Status Updated',
                    'message' => "Your order #{$order->order_number} status has been updated to: "
                                 . OrderStatus::label($newStatus),
                    'type'    => 'info',
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error("Retail order notification failed for order {$order->id}: " . $e->getMessage());
        }
    }

    /**
     * Return the list of statuses the actor may transition this order to from its current state.
     * Used by views to build dynamic dropdowns.
     */
    public function allowedTransitionsFor(Order $order, User $actor): array
    {
        $possible        = OrderStatus::retailTransitions()[$order->status] ?? [];
        $rolePermissions = OrderStatus::retailRolePermissions();

        return array_values(
            array_filter($possible, fn($s) => in_array($actor->role, $rolePermissions[$s] ?? []))
        );
    }
}
