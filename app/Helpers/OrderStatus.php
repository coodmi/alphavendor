<?php

namespace App\Helpers;

class OrderStatus
{
    /**
     * All statuses with display config.
     */
    public static function all(): array
    {
        return [
            'pending_advance_payment' => [
                'label'  => 'Pending Advance Payment',
                'color'  => 'bg-orange-100 text-orange-700',
                'icon'   => 'fa-clock',
                'hex'    => '#f97316',
            ],
            'advance_paid' => [
                'label'  => 'Advance Paid',
                'color'  => 'bg-blue-100 text-blue-700',
                'icon'   => 'fa-money-bill-wave',
                'hex'    => '#3b82f6',
            ],
            'order_confirmed' => [
                'label'  => 'Order Confirmed',
                'color'  => 'bg-indigo-100 text-indigo-700',
                'icon'   => 'fa-check-circle',
                'hex'    => '#6366f1',
            ],
            'pending' => [
                'label'  => 'Pending',
                'color'  => 'bg-yellow-100 text-yellow-700',
                'icon'   => 'fa-hourglass-half',
                'hex'    => '#eab308',
            ],
            'processing' => [
                'label'  => 'Processing',
                'color'  => 'bg-cyan-100 text-cyan-700',
                'icon'   => 'fa-cog',
                'hex'    => '#06b6d4',
            ],
            'shipped' => [
                'label'  => 'Shipped',
                'color'  => 'bg-purple-100 text-purple-700',
                'icon'   => 'fa-shipping-fast',
                'hex'    => '#a855f7',
            ],
            'delivered' => [
                'label'  => 'Delivered',
                'color'  => 'bg-green-100 text-green-700',
                'icon'   => 'fa-check-double',
                'hex'    => '#22c55e',
            ],
            'cancelled' => [
                'label'  => 'Cancelled',
                'color'  => 'bg-red-100 text-red-700',
                'icon'   => 'fa-times-circle',
                'hex'    => '#ef4444',
            ],
            'refunded' => [
                'label'  => 'Refunded',
                'color'  => 'bg-pink-100 text-pink-700',
                'icon'   => 'fa-undo-alt',
                'hex'    => '#ec4899',
            ],
            'exchange' => [
                'label'  => 'Exchange',
                'color'  => 'bg-teal-100 text-teal-700',
                'icon'   => 'fa-exchange-alt',
                'hex'    => '#14b8a6',
            ],
            'returned' => [
                'label'  => 'Returned',
                'color'  => 'bg-gray-100 text-gray-700',
                'icon'   => 'fa-reply',
                'hex'    => '#6b7280',
            ],
        ];
    }

    public static function label(string $status): string
    {
        return self::all()[$status]['label'] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public static function color(string $status): string
    {
        return self::all()[$status]['color'] ?? 'bg-gray-100 text-gray-600';
    }

    public static function icon(string $status): string
    {
        return self::all()[$status]['icon'] ?? 'fa-circle';
    }

    /**
     * Statuses admin can set.
     */
    public static function adminStatuses(): array
    {
        return array_keys(self::all());
    }

    /**
     * Statuses a vendor (wholesaler/importer/retailer) can set.
     * Rule: vendor can ONLY mark as shipped.
     */
    public static function vendorStatuses(): array
    {
        return ['shipped'];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // RETAIL ORDER STATE MACHINE
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Allowed transitions for retail orders.
     *
     * Rules:
     *  - Seller (retailer) can ONLY set: shipped (from order_confirmed or processing)
     *  - Admin / Employee control all other transitions
     *  - delivered and cancelled are terminal states
     *
     * Format: [from_status => [to_status, ...]]
     */
    public static function retailTransitions(): array
    {
        return [
            'pending'         => ['order_confirmed', 'cancelled'],
            'order_confirmed' => ['processing', 'shipped', 'cancelled'],
            'processing'      => ['shipped', 'cancelled'],
            'shipped'         => ['delivered'],
            'delivered'       => [],   // terminal
            'cancelled'       => [],   // terminal
        ];
    }

    /**
     * Role permission map for retail order status transitions.
     *
     * Rules:
     *  - order_confirmed : Admin / Employee only
     *  - processing      : Admin / Employee only
     *  - shipped         : Admin / Employee / Seller (retailer)
     *  - delivered       : Admin / Employee only
     *  - cancelled       : Admin / Employee only  ← seller CANNOT cancel
     *
     * Format: [target_status => [allowed_roles, ...]]
     */
    public static function retailRolePermissions(): array
    {
        return [
            'order_confirmed' => ['admin', 'employee'],
            'processing'      => ['admin', 'employee'],
            'shipped'         => ['admin', 'employee', 'retailer'],
            'delivered'       => ['admin', 'employee'],
            'cancelled'       => ['admin', 'employee'],
        ];
    }

    /**
     * Determine initial status when order is placed.
     * Wholesale/Importer with advance payment setting → pending_advance_payment
     * Otherwise → pending
     */
    public static function initialStatus(string $vendorRole, bool $advanceMandatory = false): string
    {
        if (in_array($vendorRole, ['wholesaler', 'exporter', 'importer']) && $advanceMandatory) {
            return 'pending_advance_payment';
        }
        return 'pending';
    }

    /**
     * Allowed transitions for wholesale/import orders.
     *
     * Rules:
     *  - Seller (wholesaler/exporter/importer) can ONLY set: shipped (from processing)
     *  - Cancel is allowed from: pending_advance_payment, advance_paid, order_confirmed, processing
     *  - Cancel is NOT allowed after: shipped, delivered
     *  - delivered is a terminal state
     *
     * Format: [from_status => [to_status, ...]]
     */
    public static function wholesaleTransitions(): array
    {
        return [
            'pending_advance_payment' => ['advance_paid', 'cancelled'],
            'advance_paid'            => ['order_confirmed', 'cancelled'],
            'order_confirmed'         => ['processing', 'cancelled'],
            'processing'              => ['shipped', 'cancelled'],
            'shipped'                 => ['delivered'],   // ← no cancel after shipped
            'delivered'               => [],              // terminal
            'cancelled'               => [],              // terminal
        ];
    }

    /**
     * Role permission map for wholesale/import status transitions.
     *
     * Rules per your workflow:
     *  - advance_paid    : Admin / Employee only (payment verification)
     *  - order_confirmed : Admin / Employee only
     *  - processing      : Admin / Employee only  ← seller CANNOT set processing
     *  - shipped         : Admin / Employee / Seller (wholesaler, exporter, importer)
     *  - delivered       : Admin / Employee only
     *  - cancelled       : Admin / Employee only  ← seller CANNOT cancel
     *
     * Format: [target_status => [allowed_roles, ...]]
     */
    public static function wholesaleRolePermissions(): array
    {
        return [
            'advance_paid'    => ['admin', 'employee'],
            'order_confirmed' => ['admin', 'employee'],
            'processing'      => ['admin', 'employee'],                              // seller removed
            'shipped'         => ['admin', 'employee', 'wholesaler', 'exporter', 'importer'],
            'delivered'       => ['admin', 'employee'],
            'cancelled'       => ['admin', 'employee'],
        ];
    }
}
