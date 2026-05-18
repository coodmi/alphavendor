# Design Document — Wholesale/Import Order Status Flow

## Overview

This design implements a role-gated, 7-step order status state machine for wholesale and import orders in AlphaVendor. The existing `OrderStatus` helper, `Order` model, and `OrderController` are extended rather than replaced. A new `WholesaleOrderStatusController` handles the dedicated endpoint. A new `order_status_logs` table provides the audit trail. All transition logic lives in a single `WholesaleOrderStatusService` so every caller (Admin, Employee, Seller dashboards) uses the same rules.

---

## Architecture

```
HTTP PATCH /orders/{order}/wholesale-status
        │
        ▼
WholesaleOrderStatusController
        │  resolves actor role
        ▼
WholesaleOrderStatusService
        │  1. isWholesaleOrImportOrder()
        │  2. canActorTransition(role, from, to)
        │  3. order->update(['status' => $to])
        │  4. OrderStatusLog::record(...)
        ▼
Order (Eloquent model)   +   OrderStatusLog (new model)
```

Retail orders continue to use the existing `OrderController::updateStatus()` path — no changes there.

---

## Components

### 1. `WholesaleOrderStatusService` (new)
**Path:** `app/Services/WholesaleOrderStatusService.php`

Central state machine. Contains:
- `TRANSITIONS` — allowed `[from => [to, ...]]` map for wholesale/import orders
- `ROLE_PERMISSIONS` — which roles may trigger each target status
- `transition(Order $order, string $newStatus, User $actor): void` — validates and applies
- `allowedTransitionsFor(Order $order, User $actor): array` — returns list of statuses the actor may move to from current state (used by views to build dropdowns)

### 2. `WholesaleOrderStatusController` (new)
**Path:** `app/Http/Controllers/WholesaleOrderStatusController.php`

Single action: `update(Request $request, Order $order)`.
- Auth middleware: `auth`
- Validates `status` input against known enum values
- Delegates to `WholesaleOrderStatusService`
- Returns JSON 200 on success, 403/422 on rejection

### 3. `OrderStatusLog` model + migration (new)
**Path:** `app/Models/OrderStatusLog.php`
**Migration:** `database/migrations/2026_05_18_000001_create_order_status_logs_table.php`

Columns: `id`, `order_id`, `from_status`, `to_status`, `actor_id`, `actor_role`, `created_at` (UTC).

### 4. `OrderStatus` helper — extended (existing)
**Path:** `app/Helpers/OrderStatus.php`

Add two new static methods:
- `wholesaleTransitions(): array` — the allowed transition map
- `wholesaleRolePermissions(): array` — role → allowed target statuses map

### 5. `Order` model — minor addition (existing)
Add `isWholesaleOrImport(): bool` helper method.

### 6. Vendor dashboard order views — updated (existing)
`WholesalerDashboardController` and `ExporterDashboardController` order update methods are updated to call `WholesaleOrderStatusService` instead of directly setting status, so the same rules apply everywhere.

### 7. Admin & Employee order views — updated (existing)
`AdminController::updateOrderStatus()` and `EmployeeDashboardController::updateOrderStatus()` updated to call `WholesaleOrderStatusService` for wholesale/import orders, keeping existing retail logic unchanged.

---

## Data Models

### `order_status_logs` table

| Column       | Type         | Notes                          |
|--------------|--------------|--------------------------------|
| id           | bigint PK    | auto-increment                 |
| order_id     | bigint FK    | → orders.id, cascade delete   |
| from_status  | varchar(50)  | nullable (null = initial set)  |
| to_status    | varchar(50)  | not null                       |
| actor_id     | bigint FK    | → users.id, set null on delete |
| actor_role   | varchar(30)  | snapshot of role at log time   |
| created_at   | timestamp    | UTC, set on insert             |

No `updated_at` — logs are immutable.

---

## State Machine Definition

### Wholesale / Import Transition Map

```
pending_advance_payment  →  advance_paid          (Admin, Employee only)
advance_paid             →  order_confirmed        (Admin, Employee only)
order_confirmed          →  processing             (Admin, Employee, Seller)
processing               →  shipped                (Admin, Employee, Seller)
shipped                  →  delivered              (Admin, Employee only)

# Cancellation (from any non-terminal status except shipped/delivered)
pending_advance_payment  →  cancelled              (Admin, Employee only)
advance_paid             →  cancelled              (Admin, Employee only)
order_confirmed          →  cancelled              (Admin, Employee only)
processing               →  cancelled              (Admin, Employee only)
```

### Role Permission Matrix

| Target Status           | Admin | Employee | Seller | Customer |
|-------------------------|-------|----------|--------|----------|
| advance_paid            | ✅    | ✅       | ❌     | ❌       |
| order_confirmed         | ✅    | ✅       | ❌     | ❌       |
| processing              | ✅    | ✅       | ✅     | ❌       |
| shipped                 | ✅    | ✅       | ✅     | ❌       |
| delivered               | ✅    | ✅       | ❌     | ❌       |
| cancelled               | ✅    | ✅       | ❌     | ❌       |

### Retail Transition Map (unchanged)
```
pending → processing → shipped → delivered (terminal)
any non-delivered → cancelled
```

---

## API Endpoint

```
PATCH /orders/{order}/wholesale-status
Authorization: Bearer <token>  (or session cookie)
Content-Type: application/json

Body: { "status": "advance_paid" }

Responses:
  200  { "message": "Status updated.", "order": { ...order fields... } }
  401  { "message": "Unauthenticated." }
  403  { "message": "You are not authorised to set this status." }
  404  { "message": "Order not found." }
  422  { "message": "Transition not permitted from current status." }
       { "errors": { "status": ["The status field is required."] } }
```

---

## Service Implementation Sketch

```php
// app/Services/WholesaleOrderStatusService.php

class WholesaleOrderStatusService
{
    // Allowed transitions: from → [to, ...]
    private const TRANSITIONS = [
        'pending_advance_payment' => ['advance_paid', 'cancelled'],
        'advance_paid'            => ['order_confirmed', 'cancelled'],
        'order_confirmed'         => ['processing', 'cancelled'],
        'processing'              => ['shipped', 'cancelled'],
        'shipped'                 => ['delivered'],
        'delivered'               => [],   // terminal
        'cancelled'               => [],   // terminal
    ];

    // Roles allowed to set each target status
    private const ROLE_PERMISSIONS = [
        'advance_paid'    => ['admin', 'employee'],
        'order_confirmed' => ['admin', 'employee'],
        'processing'      => ['admin', 'employee', 'wholesaler', 'exporter'],
        'shipped'         => ['admin', 'employee', 'wholesaler', 'exporter'],
        'delivered'       => ['admin', 'employee'],
        'cancelled'       => ['admin', 'employee'],
    ];

    public function transition(Order $order, string $newStatus, User $actor): void
    {
        $currentStatus = $order->status;
        $actorRole     = $actor->role;

        // 1. Validate transition is allowed from current status
        $allowed = self::TRANSITIONS[$currentStatus] ?? [];
        if (!in_array($newStatus, $allowed)) {
            throw new InvalidOrderTransitionException(
                "Transition from '{$currentStatus}' to '{$newStatus}' is not permitted."
            );
        }

        // 2. Validate actor has permission for target status
        $permittedRoles = self::ROLE_PERMISSIONS[$newStatus] ?? [];
        if (!in_array($actorRole, $permittedRoles)) {
            throw new UnauthorisedOrderTransitionException(
                "Role '{$actorRole}' is not authorised to set status '{$newStatus}'."
            );
        }

        // 3. Apply transition
        $order->update(['status' => $newStatus]);

        // 4. Audit log (non-blocking)
        try {
            OrderStatusLog::create([
                'order_id'    => $order->id,
                'from_status' => $currentStatus,
                'to_status'   => $newStatus,
                'actor_id'    => $actor->id,
                'actor_role'  => $actorRole,
            ]);
        } catch (\Throwable $e) {
            \Log::error("OrderStatusLog write failed for order {$order->id}: " . $e->getMessage());
        }
    }

    public function allowedTransitionsFor(Order $order, User $actor): array
    {
        $possible = self::TRANSITIONS[$order->status] ?? [];
        $permitted = self::ROLE_PERMISSIONS;
        return array_filter($possible, fn($s) => in_array($actor->role, $permitted[$s] ?? []));
    }
}
```

---

## Controller Implementation Sketch

```php
// app/Http/Controllers/WholesaleOrderStatusController.php

class WholesaleOrderStatusController extends Controller
{
    public function __construct(private WholesaleOrderStatusService $service) {}

    public function update(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(OrderStatus::all()))],
        ]);

        // Only applies to wholesale/import orders
        if (!$order->isWholesaleOrImport()) {
            return response()->json(['message' => 'Use the standard order status endpoint for retail orders.'], 422);
        }

        try {
            $this->service->transition($order, $request->status, $request->user());
        } catch (UnauthorisedOrderTransitionException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (InvalidOrderTransitionException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Status updated.',
            'order'   => $order->fresh()->load('vendor', 'user'),
        ]);
    }
}
```

---

## Files to Create

| File | Type |
|------|------|
| `app/Services/WholesaleOrderStatusService.php` | New |
| `app/Http/Controllers/WholesaleOrderStatusController.php` | New |
| `app/Models/OrderStatusLog.php` | New |
| `app/Exceptions/InvalidOrderTransitionException.php` | New |
| `app/Exceptions/UnauthorisedOrderTransitionException.php` | New |
| `database/migrations/2026_05_18_000001_create_order_status_logs_table.php` | New |

## Files to Modify

| File | Change |
|------|--------|
| `routes/web.php` | Add `PATCH /orders/{order}/wholesale-status` route |
| `app/Helpers/OrderStatus.php` | Add `wholesaleTransitions()` and `wholesaleRolePermissions()` |
| `app/Models/Order.php` | Add `isWholesaleOrImport()` method |
| `app/Http/Controllers/AdminController.php` | Route wholesale/import orders through service in `updateOrderStatus()` |
| `app/Http/Controllers/EmployeeDashboardController.php` | Route wholesale/import orders through service in `updateOrderStatus()` |
| `app/Http/Controllers/OrderController.php` | Update `updateStatus()` (vendor) to use service; restrict to `shipped` only from `processing` |
| `resources/views/employee/orders/index.blade.php` | Use `allowedTransitionsFor()` to build status dropdown |
| `resources/views/admin/orders/` (show/index) | Use `allowedTransitionsFor()` to build status dropdown |
| `resources/views/orders/vendor-orders.blade.php` | Show only permitted transitions for seller |
