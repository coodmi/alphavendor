# Design Document — Wholesale Order Status Flow

## Overview

This feature enforces a strict, role-gated order status progression for wholesale, importer, exporter, and advance-payment retail orders. The core change is replacing the current ad-hoc status checks scattered across four controllers and three Blade views with a single authoritative transition map in `App\Helpers\OrderStatus`, then wiring every controller and view to that map.

The result is:
- **Backend**: one method (`allowedTransitions`) is the single source of truth; all four controllers call it.
- **Admin/Employee UI**: the status dropdown shows only valid next steps for each order.
- **Vendor UI**: the "Mark as Shipped" button appears only when the order is in `processing` status.
- **Customer UI**: a visual status timeline on the order detail page.

### Current State vs Target State

| Area | Current | Target |
|---|---|---|
| `OrderStatus::initialStatus()` | Misses `exporter` role | Covers wholesaler, importer, exporter unconditionally |
| `AdminController::updateOrderStatus()` | Accepts any status in the full list | Validates against `allowedTransitions()` |
| `EmployeeDashboardController::updateOrderStatus()` | Accepts only 5 statuses, no advance-flow statuses | Validates against `allowedTransitions()` |
| `OrderController::updateStatus()` | Allows ship from `pending` or `processing` | Allows ship only from `processing` |
| `RetailerDashboardController::updateOrderStatus()` | Allows ship from `pending` or `processing` | Allows ship only from `processing` |
| Admin orders view | Full status dropdown | Contextual dropdown from `allowedTransitions()` |
| Vendor orders views | "Mark Shipped" shown for `pending` or `processing` | Shown only for `processing` |
| Customer order detail | No timeline | Status timeline partial |

---

## Architecture

The change is purely within the existing Laravel MVC structure — no new service classes, no database migrations, no new routes.

```
┌─────────────────────────────────────────────────────────────┐
│                    App\Helpers\OrderStatus                   │
│  + allowedTransitions(currentStatus, actorRole): array       │
│  (single source of truth for all transition rules)           │
└──────────────┬──────────────────────────────────────────────┘
               │ called by
       ┌───────┴────────────────────────────────────┐
       │                                            │
┌──────▼──────────────┐                   ┌────────▼──────────────────┐
│  Controllers (4)     │                   │  Blade Views (5)           │
│  AdminController     │                   │  admin/orders/index        │
│  EmployeeDashboard   │                   │  employee/orders/index     │
│  OrderController     │                   │  retailer/orders/index     │
│  RetailerDashboard   │                   │  orders/vendor-orders      │
└─────────────────────┘                   │  orders/show (+ timeline)  │
                                          └───────────────────────────┘
```

The transition map lives in one place. Controllers use it to validate incoming requests. Views use it to decide what to render.

---

## Components and Interfaces

### 1. `App\Helpers\OrderStatus` — new method

```php
/**
 * Returns the statuses that $actorRole may transition to from $currentStatus.
 *
 * @param  string  $currentStatus  The order's current status value.
 * @param  string  $actorRole      One of: 'admin', 'employee', 'vendor', 'customer'.
 * @return array<string>           List of permitted target statuses (may be empty).
 */
public static function allowedTransitions(string $currentStatus, string $actorRole): array
```

**Transition map for `admin` / `employee`:**

| Current status | Allowed next statuses |
|---|---|
| `pending_advance_payment` | `advance_paid`, `cancelled` |
| `advance_paid` | `order_confirmed`, `cancelled` |
| `order_confirmed` | `processing`, `cancelled` |
| `pending` | `processing`, `cancelled` |
| `processing` | `shipped`, `cancelled` |
| `shipped` | `delivered` |
| `delivered` | *(empty — terminal)* |
| `cancelled` | *(empty — terminal)* |
| `refunded` | *(empty — terminal)* |
| `returned` | *(empty — terminal)* |
| `exchange` | *(empty — terminal)* |

**Transition map for `vendor`:**

| Current status | Allowed next statuses |
|---|---|
| `processing` | `shipped` |
| *(any other)* | *(empty)* |

**Transition map for `customer`:** always empty.

### 2. `AdminController::updateOrderStatus()`

Replace the current open-list validation with a guard that calls `allowedTransitions()`:

```
1. Resolve $actorRole = 'admin'
2. $allowed = OrderStatus::allowedTransitions($order->status, $actorRole)
3. If $allowed is empty → return error "This order cannot be changed further."
4. Validate request: status must be in $allowed
5. If validation fails → return error with allowed transitions listed
6. Proceed with existing wallet/transaction side-effects
```

### 3. `EmployeeDashboardController::updateOrderStatus()`

Same guard as AdminController. Replace the current hard-coded 5-status validation with `allowedTransitions($order->status, 'employee')`. Add the wallet/notification side-effects that currently only exist in AdminController (order delivered → credit wallet; order cancelled → reverse pending balance).

### 4. `OrderController::updateStatus()` (wholesaler/importer/exporter vendors)

```
1. $allowed = OrderStatus::allowedTransitions($order->status, 'vendor')
2. Validate: status must be 'shipped' AND in $allowed
3. If $allowed is empty → return error "Orders can only be marked as Shipped when they are in Processing status."
4. Update order status
```

### 5. `RetailerDashboardController::updateOrderStatus()`

Same guard as `OrderController::updateStatus()` — replace `in_array($order->status, ['pending', 'processing'])` with `!empty(OrderStatus::allowedTransitions($order->status, 'vendor'))`.

### 6. `resources/views/admin/orders/index.blade.php`

Replace the current full-list `<select>` with a contextual dropdown:

```blade
@php
    $allowed = \App\Helpers\OrderStatus::allowedTransitions($order->status, 'admin');
@endphp
@if(empty($allowed))
    {{-- Terminal status: read-only badge --}}
    @include('partials.order-status-badge', ['status' => $order->status])
@else
    <form action="..." method="POST">
        @csrf @method('PATCH')
        <select name="status" onchange="this.form.submit()">
            <option value="{{ $order->status }}" selected disabled>
                {{ \App\Helpers\OrderStatus::label($order->status) }} (current)
            </option>
            @foreach($allowed as $val)
                <option value="{{ $val }}">{{ \App\Helpers\OrderStatus::label($val) }}</option>
            @endforeach
        </select>
    </form>
@endif
```

### 7. `resources/views/employee/orders/index.blade.php`

Apply the same contextual dropdown pattern as the admin view, using `allowedTransitions($order->status, 'employee')`.

### 8. `resources/views/retailer/orders/index.blade.php`

Change the condition from `in_array($order->status, ['pending', 'processing'])` to `$order->status === 'processing'`.

### 9. `resources/views/orders/vendor-orders.blade.php`

Same change as #8. Also replace the inline `$sc` color map with `@include('partials.order-status-badge')` for consistency.

### 10. `resources/views/orders/show.blade.php`

Add the timeline partial just below the order header section:

```blade
@include('partials.order-status-timeline', ['order' => $order])
```

### 11. `resources/views/partials/order-status-timeline.blade.php` (new file)

Determines which step sequence to display based on whether the order is an advance order, then renders a horizontal step indicator. See Data Models section for the step sequences.

---

## Data Models

No schema changes. All logic operates on the existing `orders.status` string column.

### Status Sequences

**Advance flow** (initial status = `pending_advance_payment`):

```
pending_advance_payment → advance_paid → order_confirmed → processing → shipped → delivered
```

**Standard flow** (initial status = `pending`):

```
pending → processing → shipped → delivered
```

**Detecting flow type in the timeline partial:**

```php
$advanceStatuses = ['pending_advance_payment', 'advance_paid', 'order_confirmed'];
$isAdvanceOrder  = in_array($order->status, $advanceStatuses)
                || in_array($order->status, ['processing', 'shipped', 'delivered', 'cancelled'])
                   && $order->created_at /* heuristic: check first item status or a flag */;
```

A cleaner approach: add a helper method `OrderStatus::isAdvanceFlow(string $status): bool` that returns `true` for the six advance-flow statuses. For orders already past `processing`, the view can check whether the order's `status` was ever `pending_advance_payment` — but since we don't store history, the simplest reliable heuristic is: if the order's vendor role is `wholesaler`, `importer`, or `exporter`, it is an advance order. The `order->vendor->role` relationship is already eager-loaded on the show page.

**Timeline step data structure:**

```php
[
    ['key' => 'pending_advance_payment', 'label' => 'Pending Advance Payment'],
    ['key' => 'advance_paid',            'label' => 'Advance Paid'],
    ['key' => 'order_confirmed',         'label' => 'Order Confirmed'],
    ['key' => 'processing',              'label' => 'Processing'],
    ['key' => 'shipped',                 'label' => 'Shipped'],
    ['key' => 'delivered',               'label' => 'Delivered'],
]
```

**Step state classification:**

```php
$currentIndex = array_search($order->status, array_column($steps, 'key'));
// step index < $currentIndex  → 'completed'
// step index === $currentIndex → 'current'
// step index > $currentIndex  → 'future'
```

### `OrderStatus::initialStatus()` fix

The current implementation only returns `pending_advance_payment` for wholesaler/exporter/importer when `$advanceMandatory` is `true`. Per Requirement 1.1 and 1.4, wholesale/importer/exporter orders should always start at `pending_advance_payment` regardless of the advance payment setting. The fix:

```php
public static function initialStatus(string $vendorRole, bool $advanceMandatory = false): string
{
    if (in_array($vendorRole, ['wholesaler', 'exporter', 'importer'])) {
        return 'pending_advance_payment';
    }
    if ($vendorRole === 'retailer' && $advanceMandatory) {
        return 'pending_advance_payment';
    }
    return 'pending';
}
```

---

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Advance-role initial status

*For any* vendor whose role is `wholesaler`, `importer`, or `exporter`, `OrderStatus::initialStatus(role)` SHALL return `pending_advance_payment`, regardless of the `$advanceMandatory` flag.

**Validates: Requirements 1.1, 1.4**

---

### Property 2: Admin/employee transition map completeness

*For any* current order status and actor role of `admin` or `employee`, `OrderStatus::allowedTransitions(status, role)` SHALL return exactly the set of statuses defined in the transition map — no more, no fewer.

**Validates: Requirements 2.1, 2.2, 8.1, 8.2**

---

### Property 3: Invalid transitions are always rejected

*For any* current order status and any target status that is NOT in `allowedTransitions(currentStatus, 'admin')`, submitting that target status to `AdminController::updateOrderStatus()` or `EmployeeDashboardController::updateOrderStatus()` SHALL result in a rejection (redirect back with error, no status change on the order).

**Validates: Requirements 2.3, 2.4, 2.5**

---

### Property 4: Valid transitions are always accepted

*For any* current order status and any target status that IS in `allowedTransitions(currentStatus, 'admin')`, submitting that target status to `AdminController::updateOrderStatus()` SHALL result in the order's status being updated to the target status.

**Validates: Requirements 2.6**

---

### Property 5: Vendor transition map — only processing allows shipping

*For any* current order status, `OrderStatus::allowedTransitions(status, 'vendor')` SHALL return `['shipped']` if and only if `status === 'processing'`, and an empty array for all other statuses.

**Validates: Requirements 3.1, 3.2, 3.3, 8.3**

---

### Property 6: Customer always gets empty transitions

*For any* current order status, `OrderStatus::allowedTransitions(status, 'customer')` SHALL always return an empty array.

**Validates: Requirements 8.4**

---

### Property 7: Admin dropdown options match allowed transitions

*For any* order with any current status, the status dropdown rendered in `admin/orders/index.blade.php` SHALL contain option values that exactly match the array returned by `allowedTransitions($order->status, 'admin')`. When the allowed array is empty, no dropdown SHALL be rendered.

**Validates: Requirements 4.1, 4.2, 4.3**

---

### Property 8: Vendor "Mark as Shipped" button visibility

*For any* order, the "Mark as Shipped" button SHALL be present in the rendered vendor order list view if and only if `$order->status === 'processing'`. This property holds for both `vendor-orders.blade.php` and `retailer/orders/index.blade.php`.

**Validates: Requirements 6.1, 6.2, 6.3**

---

### Property 9: Status timeline step ordering

*For any* order whose status is in the advance flow, the rendered timeline partial SHALL display the six advance-flow steps in the correct sequence, with all steps before the current status marked as completed, the current step marked as current, and all subsequent steps marked as future.

**Validates: Requirements 7.1, 7.3**

---

### Property 10: Standard-flow timeline step ordering

*For any* order whose status is in the standard flow (initial status `pending`), the rendered timeline partial SHALL display the four standard-flow steps in the correct sequence, with the same completed/current/future classification as Property 9.

**Validates: Requirements 7.2, 7.3**

---

**Property Reflection — redundancy check:**

- Properties 3 and 4 are complementary (reject invalid / accept valid) and together cover the full transition guard behavior. Neither subsumes the other.
- Properties 9 and 10 cover different step sequences (advance vs standard) and cannot be merged without losing specificity about which steps appear.
- Properties 2 and 5 both test `allowedTransitions()` but for different actor roles — kept separate.
- Properties 7 and 8 test view rendering, not the helper — kept separate from 2/5.

No redundancies identified.

---

## Error Handling

### Controller-level errors

| Scenario | Response |
|---|---|
| Target status not in `allowedTransitions()` | `redirect()->back()->with('error', 'Invalid status transition. Allowed next statuses: ...')` |
| Order is terminal (`delivered`, `cancelled`, etc.) | `redirect()->back()->with('error', 'This order cannot be changed further.')` |
| Vendor tries to ship a non-processing order | `redirect()->back()->with('error', 'Orders can only be marked as Shipped when they are in Processing status.')` |
| Vendor tries to update another vendor's order | `abort(403)` |

### View-level safeguards

- Terminal-status orders render a read-only badge instead of a dropdown — no form is submitted.
- The "Mark as Shipped" button is absent for non-processing orders — no accidental submission.

### Backward compatibility

- Orders already in `delivered`, `cancelled`, `refunded`, `returned`, or `exchange` status are unaffected — they simply have empty allowed-transition arrays and render as read-only.
- The `initialStatus()` fix changes behavior only for new orders; existing orders are not migrated.

---

## Testing Strategy

### Unit tests (PHPUnit)

Focus on the `OrderStatus` helper and the transition guard logic in isolation.

**`OrderStatusHelperTest`:**
- `allowedTransitions('pending_advance_payment', 'admin')` returns `['advance_paid', 'cancelled']`
- `allowedTransitions('delivered', 'admin')` returns `[]`
- `allowedTransitions('processing', 'vendor')` returns `['shipped']`
- `allowedTransitions('pending', 'vendor')` returns `[]`
- `allowedTransitions('anything', 'customer')` returns `[]`
- `initialStatus('wholesaler')` returns `'pending_advance_payment'`
- `initialStatus('retailer', false)` returns `'pending'`
- `initialStatus('retailer', true)` returns `'pending_advance_payment'`

**Controller guard tests (Feature tests with `actingAs`):**
- Admin can advance `pending_advance_payment` → `advance_paid`
- Admin cannot skip `pending_advance_payment` → `processing`
- Admin cannot cancel a `delivered` order
- Vendor can ship a `processing` order
- Vendor cannot ship a `pending` order
- Vendor cannot update another vendor's order (403)

### Property-based tests (Pest + `pestphp/pest-plugin-faker` or a PBT library)

The PHP ecosystem's most practical PBT option for Laravel is using Pest with randomized data generation. Each property test runs a minimum of 100 iterations.

**Property 1 — Advance-role initial status:**
```
// Feature: wholesale-order-status-flow, Property 1: advance-role initial status
for 100 iterations:
    $role = random_choice(['wholesaler', 'importer', 'exporter'])
    assert OrderStatus::initialStatus($role) === 'pending_advance_payment'
```

**Property 2 — Transition map completeness:**
```
// Feature: wholesale-order-status-flow, Property 2: admin/employee transition map completeness
for 100 iterations:
    $status = random_choice(array_keys(OrderStatus::all()))
    $role   = random_choice(['admin', 'employee'])
    $result = OrderStatus::allowedTransitions($status, $role)
    assert is_array($result)
    assert each value in $result is a key in OrderStatus::all()
    assert $result matches the expected map entry for $status
```

**Property 3 — Invalid transitions rejected:**
```
// Feature: wholesale-order-status-flow, Property 3: invalid transitions rejected
for 100 iterations:
    $status  = random_choice(array_keys(OrderStatus::all()))
    $allowed = OrderStatus::allowedTransitions($status, 'admin')
    $invalid = random_choice(array_diff(array_keys(OrderStatus::all()), $allowed))
    if $invalid exists:
        POST to admin update-status with $invalid
        assert response redirects back with error
        assert order->fresh()->status === $status (unchanged)
```

**Property 5 — Vendor transition map:**
```
// Feature: wholesale-order-status-flow, Property 5: vendor transition map
for 100 iterations:
    $status = random_choice(array_keys(OrderStatus::all()))
    $result = OrderStatus::allowedTransitions($status, 'vendor')
    if $status === 'processing':
        assert $result === ['shipped']
    else:
        assert $result === []
```

**Property 6 — Customer always empty:**
```
// Feature: wholesale-order-status-flow, Property 6: customer always empty
for 100 iterations:
    $status = random_choice(array_keys(OrderStatus::all()))
    assert OrderStatus::allowedTransitions($status, 'customer') === []
```

**Property 8 — Vendor button visibility:**
```
// Feature: wholesale-order-status-flow, Property 8: vendor button visibility
for 100 iterations:
    $status = random_choice(array_keys(OrderStatus::all()))
    render vendor-orders view with an order of $status
    if $status === 'processing':
        assert rendered HTML contains 'Mark Shipped' button
    else:
        assert rendered HTML does not contain 'Mark Shipped' button
```

**Properties 9 & 10 — Timeline step ordering:**
```
// Feature: wholesale-order-status-flow, Property 9/10: timeline step ordering
for 100 iterations:
    $status = random_choice(advance_flow_statuses or standard_flow_statuses)
    render timeline partial with order of $status
    assert steps appear in correct sequence
    assert steps before current have 'completed' class
    assert current step has 'current' class
    assert steps after current have 'future' class
```

### Integration tests

- End-to-end: place a wholesale order, verify initial status is `pending_advance_payment`
- End-to-end: admin advances order through full flow to `delivered`, verify wallet is credited
- End-to-end: admin attempts to cancel a `delivered` order, verify rejection

### Accessibility

The timeline partial must include:
- `aria-label` on the timeline container
- `aria-current="step"` on the current step
- Text labels alongside all icons (not icon-only)
- Color contrast ratio ≥ 4.5:1 for all status text (manual verification required)
