# Implementation Plan: Wholesale Order Status Flow

## Overview

Centralise all order status transition logic in `App\Helpers\OrderStatus::allowedTransitions()`, wire every controller and view to that single source of truth, and add a customer-facing status timeline partial. Changes touch one helper class, four controllers, five Blade views, and one new partial — no migrations required.

## Tasks

- [x] 1. Add `allowedTransitions()` and fix `initialStatus()` in `OrderStatus` helper
  - Add `public static function allowedTransitions(string $currentStatus, string $actorRole): array` to `app/Helpers/OrderStatus.php`
  - Implement the admin/employee transition map: `pending_advance_payment` → `[advance_paid, cancelled]`, `advance_paid` → `[order_confirmed, cancelled]`, `order_confirmed` → `[processing, cancelled]`, `pending` → `[processing, cancelled]`, `processing` → `[shipped, cancelled]`, `shipped` → `[delivered]`, terminal statuses (`delivered`, `cancelled`, `refunded`, `returned`, `exchange`) → `[]`
  - Implement the vendor map: return `['shipped']` only when `$currentStatus === 'processing'`, empty array otherwise
  - Implement the customer map: always return `[]`
  - Fix `initialStatus()`: remove the `$advanceMandatory` guard for `wholesaler`, `importer`, `exporter` — those roles always return `pending_advance_payment` regardless of the flag; retailer still respects `$advanceMandatory`
  - _Requirements: 1.1, 1.4, 2.1, 2.2, 3.1, 8.1, 8.2, 8.3, 8.4_

  - [ ]* 1.1 Write property test — Property 1: advance-role initial status
    - For each of `wholesaler`, `importer`, `exporter`, assert `OrderStatus::initialStatus($role)` returns `pending_advance_payment` regardless of the `$advanceMandatory` flag value
    - **Property 1: Advance-role initial status**
    - **Validates: Requirements 1.1, 1.4**

  - [ ]* 1.2 Write property test — Property 2: admin/employee transition map completeness
    - For 100 random combinations of `$status` (from `array_keys(OrderStatus::all())`) and `$role` (`admin` or `employee`), assert `allowedTransitions()` returns exactly the expected set — no extra values, no missing values
    - **Property 2: Admin/employee transition map completeness**
    - **Validates: Requirements 2.1, 2.2, 8.1, 8.2**

  - [ ]* 1.3 Write property test — Property 5: vendor transition map
    - For 100 random statuses, assert `allowedTransitions($status, 'vendor')` returns `['shipped']` if and only if `$status === 'processing'`, and `[]` otherwise
    - **Property 5: Vendor transition map — only processing allows shipping**
    - **Validates: Requirements 3.1, 3.2, 3.3, 8.3**

  - [ ]* 1.4 Write property test — Property 6: customer always empty
    - For 100 random statuses, assert `allowedTransitions($status, 'customer')` always returns `[]`
    - **Property 6: Customer always gets empty transitions**
    - **Validates: Requirement 8.4**

- [x] 2. Guard `AdminController::updateOrderStatus()` with `allowedTransitions()`
  - Replace the open-list `in:` validation rule in `AdminController::updateOrderStatus()` with a guard that calls `OrderStatus::allowedTransitions($order->status, 'admin')`
  - If `$allowed` is empty, redirect back with error `'This order cannot be changed further.'`
  - Validate that the submitted `status` is in `$allowed`; on failure redirect back with error listing the allowed transitions
  - Keep the existing wallet credit (delivered) and wallet reversal (cancelled) side-effects unchanged
  - _Requirements: 2.3, 2.4, 2.5, 2.6, 2.7, 8.5_

  - [ ]* 2.1 Write unit tests for AdminController transition guard
    - Test: admin can advance `pending_advance_payment` → `advance_paid` (valid)
    - Test: admin cannot skip `pending_advance_payment` → `processing` (rejected, order unchanged)
    - Test: admin cannot cancel a `delivered` order (rejected)
    - Test: admin cannot change a `delivered` order to any status (terminal guard)
    - _Requirements: 2.3, 2.4, 2.5, 2.6_

  - [ ]* 2.2 Write property test — Property 3: invalid transitions always rejected
    - For 100 random `$status` values, pick a random target NOT in `allowedTransitions($status, 'admin')`, POST it to the admin update-status endpoint, assert redirect-back with error and `$order->fresh()->status` unchanged
    - **Property 3: Invalid transitions are always rejected**
    - **Validates: Requirements 2.3, 2.4, 2.5**

  - [ ]* 2.3 Write property test — Property 4: valid transitions always accepted
    - For 100 random `$status` values that have at least one allowed transition, pick a random target from `allowedTransitions($status, 'admin')`, POST it, assert `$order->fresh()->status` equals the target
    - **Property 4: Valid transitions are always accepted**
    - **Validates: Requirement 2.6**

- [x] 3. Guard `EmployeeDashboardController::updateOrderStatus()` with `allowedTransitions()`
  - Replace the hard-coded `in:pending,processing,shipped,delivered,cancelled` validation with the same `allowedTransitions($order->status, 'employee')` guard used in task 2
  - Add the wallet credit (delivered) and wallet reversal (cancelled) side-effects that currently only exist in `AdminController` — mirror the exact logic from `AdminController::updateOrderStatus()`
  - Keep the existing customer `Notification::create()` call
  - _Requirements: 2.7, 5.1, 5.2, 5.3, 5.4_

  - [ ]* 3.1 Write unit tests for EmployeeDashboardController transition guard
    - Test: employee can advance `advance_paid` → `order_confirmed`
    - Test: employee cannot skip `advance_paid` → `shipped`
    - Test: employee cannot change a `cancelled` order
    - _Requirements: 5.2, 5.4_

- [x] 4. Checkpoint — Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Guard `OrderController::updateStatus()` (wholesaler/importer/exporter vendors)
  - Replace `in_array($order->status, ['pending', 'processing'])` with `!empty(OrderStatus::allowedTransitions($order->status, 'vendor'))`
  - Keep the existing `in:shipped` validation rule on the request
  - On failure return `redirect()->back()->with('error', 'Orders can only be marked as Shipped when they are in Processing status.')`
  - _Requirements: 3.2, 3.3, 3.4, 8.5_

  - [ ]* 5.1 Write unit tests for OrderController vendor guard
    - Test: vendor can ship a `processing` order (status updated to `shipped`)
    - Test: vendor cannot ship a `pending` order (rejected, order unchanged)
    - Test: vendor cannot ship an `advance_paid` order (rejected)
    - Test: vendor cannot update another vendor's order (403)
    - _Requirements: 3.2, 3.3, 3.5_

- [ ] 6. Guard `RetailerDashboardController::updateOrderStatus()` (retailer vendors)
  - Replace `in_array($order->status, ['pending', 'processing'])` with `!empty(OrderStatus::allowedTransitions($order->status, 'vendor'))`
  - Update the error message to match: `'Orders can only be marked as Shipped when they are in Processing status.'`
  - _Requirements: 3.2, 3.3, 3.4, 8.5_

  - [ ]* 6.1 Write unit tests for RetailerDashboardController vendor guard
    - Test: retailer can ship a `processing` order
    - Test: retailer cannot ship a `pending` order
    - Test: retailer cannot update another vendor's order (403)
    - _Requirements: 3.2, 3.3, 3.5_

- [ ] 7. Update `resources/views/admin/orders/index.blade.php` — contextual status dropdown
  - Replace the `@foreach(\App\Helpers\OrderStatus::all() ...)` full-list `<select>` with a contextual dropdown driven by `\App\Helpers\OrderStatus::allowedTransitions($order->status, 'admin')`
  - When `$allowed` is empty (terminal status), render a read-only `@include('partials.order-status-badge', ['status' => $order->status])` instead of a form
  - When `$allowed` is non-empty, render the `<select>` with the current status as a disabled selected option followed by the allowed next statuses using `OrderStatus::label()` for display text
  - Keep the payment status form unchanged
  - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

  - [ ]* 7.1 Write property test — Property 7: admin dropdown options match allowed transitions
    - For 100 random order statuses, render the admin orders index view and assert the `<select>` option values exactly match `allowedTransitions($status, 'admin')`; when allowed is empty, assert no `<select>` is rendered
    - **Property 7: Admin dropdown options match allowed transitions**
    - **Validates: Requirements 4.1, 4.2, 4.3**

- [ ] 8. Update `resources/views/employee/orders/index.blade.php` — contextual status dropdown
  - Replace the static modal `<select>` (which hard-codes 5 statuses) with a per-row contextual dropdown using `allowedTransitions($order->status, 'employee')`
  - When `$allowed` is empty, render a read-only status badge instead of the edit button/modal trigger
  - When `$allowed` is non-empty, render an inline `<select onchange="this.form.submit()">` (matching the admin view pattern) or keep the modal approach but populate it dynamically with only the allowed options passed via a `data-allowed` attribute
  - Use `OrderStatus::label()` and `OrderStatus::color()` for all status display
  - _Requirements: 5.1, 5.3, 5.4_

- [ ] 9. Update `resources/views/retailer/orders/index.blade.php` — conditional "Mark as Shipped" button
  - Change the condition from `in_array($order->status, ['pending', 'processing'])` to `$order->status === 'processing'`
  - Remove the `@else` branch that shows "No action available" for non-processing orders (or keep it — it is harmless, but the button must not appear for non-processing orders)
  - _Requirements: 6.1, 6.2, 6.3, 6.4_

  - [ ]* 9.1 Write property test — Property 8: vendor button visibility (retailer view)
    - For 100 random order statuses, render the retailer orders index view and assert the "Mark Shipped" button is present if and only if `$status === 'processing'`
    - **Property 8: Vendor "Mark as Shipped" button visibility**
    - **Validates: Requirements 6.1, 6.2, 6.3**

- [ ] 10. Update `resources/views/orders/vendor-orders.blade.php` — conditional "Mark as Shipped" button
  - Change the condition from `in_array($order->status, ['pending', 'processing'])` to `$order->status === 'processing'`
  - Replace the inline `$sc` color map with `@include('partials.order-status-badge', ['status' => $order->status])` for consistency with other views
  - _Requirements: 6.1, 6.2, 6.3, 6.4_

  - [ ]* 10.1 Write property test — Property 8: vendor button visibility (vendor-orders view)
    - For 100 random order statuses, render the vendor-orders view and assert the "Mark Shipped" button is present if and only if `$status === 'processing'`
    - **Property 8: Vendor "Mark as Shipped" button visibility**
    - **Validates: Requirements 6.1, 6.2, 6.3**

- [ ] 11. Checkpoint — Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 12. Create `resources/views/partials/order-status-timeline.blade.php` (new file)
  - Detect flow type: if `$order->vendor->role` is `wholesaler`, `importer`, or `exporter`, use the advance flow (6 steps); if `retailer` or `user`, use the standard flow (4 steps: `pending` → `processing` → `shipped` → `delivered`)
  - Build the `$steps` array from `OrderStatus::label()` and `OrderStatus::icon()` for each step key
  - Compute `$currentIndex` using `array_search($order->status, array_column($steps, 'key'))`; classify each step as `completed` (index < current), `current` (index === current), or `future` (index > current)
  - When `$order->status === 'cancelled'`, render a distinct "Cancelled" banner instead of the step indicator
  - Render a horizontal step indicator with: completed steps in the `OrderStatus::color()` teal/green palette, current step highlighted, future steps in gray
  - Add accessibility attributes: `role="list"` on the container, `aria-label="Order status timeline"`, `aria-current="step"` on the current step, text labels alongside all icons (no icon-only steps)
  - Ensure color contrast ≥ 4.5:1 for all status text (use Tailwind classes consistent with `OrderStatus::color()`)
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 7.7_

  - [ ]* 12.1 Write property test — Property 9: advance-flow timeline step ordering
    - For each of the 6 advance-flow statuses, render the timeline partial and assert: steps appear in the correct sequence, steps before current have the completed CSS class, current step has `aria-current="step"`, steps after current have the future CSS class
    - **Property 9: Status timeline step ordering (advance flow)**
    - **Validates: Requirements 7.1, 7.3**

  - [ ]* 12.2 Write property test — Property 10: standard-flow timeline step ordering
    - For each of the 4 standard-flow statuses, render the timeline partial and assert the same completed/current/future classification for the 4-step sequence
    - **Property 10: Standard-flow timeline step ordering**
    - **Validates: Requirements 7.2, 7.3**

- [ ] 13. Include timeline partial in `resources/views/orders/show.blade.php`
  - Add `@include('partials.order-status-timeline', ['order' => $order])` immediately after the order header `<div>` (below the order number / placed-on date block, before the shipping/payment grid)
  - Ensure `$order->vendor` is eager-loaded in `OrderController::show()` (add `vendor` to the `with()` call if not already present)
  - _Requirements: 7.6_

- [ ] 14. Final checkpoint — Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for a faster MVP
- Each task references specific requirements for traceability
- Checkpoints at tasks 4, 11, and 14 ensure incremental validation
- Property tests validate universal correctness properties across all possible status values
- Unit tests validate specific examples and edge cases
- The `allowedTransitions()` method (task 1) must be complete before any controller or view task begins — all downstream tasks depend on it
