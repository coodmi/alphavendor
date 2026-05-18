# Implementation Tasks

## Task 1: Create Custom Exceptions
Create two lightweight exception classes used by the service.

- [ ] Create `app/Exceptions/InvalidOrderTransitionException.php` extending `\RuntimeException`
- [ ] Create `app/Exceptions/UnauthorisedOrderTransitionException.php` extending `\RuntimeException`

**Requirements:** 2, 3, 4, 5, 6, 7, 8

---

## Task 2: Create `order_status_logs` Migration and Model
Set up the audit log table and Eloquent model.

- [ ] Create migration `database/migrations/2026_05_18_000001_create_order_status_logs_table.php` with columns: `id`, `order_id` (FK→orders), `from_status` (varchar 50, nullable), `to_status` (varchar 50), `actor_id` (FK→users, set null on delete), `actor_role` (varchar 30), `created_at` (timestamp UTC, no `updated_at`)
- [ ] Create `app/Models/OrderStatusLog.php` with `$fillable`, `public $timestamps = false`, `created_at` cast to datetime, and `order()` / `actor()` relationships

**Requirements:** 10

---

## Task 3: Extend `OrderStatus` Helper
Add wholesale transition map and role permission map as static methods.

- [ ] Add `wholesaleTransitions(): array` to `app/Helpers/OrderStatus.php` returning the full `[from => [to, ...]]` map
- [ ] Add `wholesaleRolePermissions(): array` returning `[target_status => [roles]]` map

**Requirements:** 2, 3, 4, 5, 6, 7, 8

---

## Task 4: Add `isWholesaleOrImport()` to Order Model
Simple helper used by controller and service to detect order type.

- [ ] Add `isWholesaleOrImport(): bool` to `app/Models/Order.php` — returns `true` if `$this->vendor->role` is `wholesaler` or `exporter`
- [ ] Add `statusLogs()` hasMany relationship to `OrderStatusLog`

**Requirements:** 1, 9

---

## Task 5: Create `WholesaleOrderStatusService`
Core state machine — validates transitions and writes audit log.

- [ ] Create `app/Services/WholesaleOrderStatusService.php`
- [ ] Implement `transition(Order $order, string $newStatus, User $actor): void` — checks allowed transitions, checks role permissions, calls `$order->update()`, writes `OrderStatusLog` in a non-blocking try/catch
- [ ] Implement `allowedTransitionsFor(Order $order, User $actor): array` — returns filtered list of statuses the actor may move to from current state

**Requirements:** 2, 3, 4, 5, 6, 7, 8, 10

---

## Task 6: Create `WholesaleOrderStatusController`
HTTP layer — validates input, delegates to service, returns JSON.

- [ ] Create `app/Http/Controllers/WholesaleOrderStatusController.php`
- [ ] Implement `update(Request $request, Order $order): JsonResponse`
  - Validate `status` against `Rule::in(array_keys(OrderStatus::all()))`
  - Return 422 if order is not wholesale/import
  - Catch `UnauthorisedOrderTransitionException` → 403
  - Catch `InvalidOrderTransitionException` → 422
  - Return 200 with updated order on success

**Requirements:** 11

---

## Task 7: Register Route
Add the new endpoint to `routes/web.php`.

- [ ] Add `Route::patch('/orders/{order}/wholesale-status', [WholesaleOrderStatusController::class, 'update'])->middleware('auth')->name('orders.wholesale-status');` inside the authenticated routes group

**Requirements:** 11

---

## Task 8: Update `AdminController::updateOrderStatus()`
Route wholesale/import orders through the service; keep retail logic unchanged.

- [ ] In `AdminController::updateOrderStatus()`, detect if order is wholesale/import via `$order->isWholesaleOrImport()`
- [ ] If wholesale/import: inject/instantiate `WholesaleOrderStatusService`, call `transition()`, catch exceptions and redirect back with error
- [ ] If retail: keep existing logic (direct `$order->update()` + wallet/transaction handling)

**Requirements:** 2, 3, 4, 5, 6, 7, 9

---

## Task 9: Update `EmployeeDashboardController::updateOrderStatus()`
Same pattern as Task 8 for the employee dashboard.

- [ ] Detect wholesale/import, delegate to `WholesaleOrderStatusService::transition()`
- [ ] Update validation rule from `in:pending,processing,shipped,delivered,cancelled` to include all wholesale statuses: `in:pending_advance_payment,advance_paid,order_confirmed,pending,processing,shipped,delivered,cancelled`
- [ ] Catch exceptions and redirect back with error message

**Requirements:** 2, 3, 4, 5, 6, 7

---

## Task 10: Update `OrderController::updateStatus()` (Vendor)
Restrict vendor (seller) to only the `shipped` transition from `processing`.

- [ ] Replace the current direct `$order->update(['status' => 'shipped'])` with a call to `WholesaleOrderStatusService::transition()`
- [ ] Remove the manual `in_array($order->status, ['pending', 'processing'])` guard — the service enforces `processing → shipped` only
- [ ] Catch `InvalidOrderTransitionException` and `UnauthorisedOrderTransitionException`, redirect back with error

**Requirements:** 5, 8

---

## Task 11: Update Employee Orders View — Status Dropdown
Build the status dropdown dynamically from allowed transitions.

- [ ] In `resources/views/employee/orders/index.blade.php`, pass `$allowedStatuses` per order (computed in controller via `$service->allowedTransitionsFor($order, auth()->user())`)
- [ ] Update `EmployeeDashboardController::orders()` to inject service and compute allowed transitions for each order, passing as `$orderAllowedStatuses` keyed by order ID
- [ ] Replace hardcoded `<option>` list in status modal with `@foreach($orderAllowedStatuses[$order->id] as $status)` loop using `OrderStatus::label($status)`

**Requirements:** 2, 3, 4, 5, 6, 7

---

## Task 12: Update Admin Orders View — Status Dropdown
Same as Task 11 for the admin orders view.

- [ ] Update `AdminController::orders()` to compute allowed transitions per order
- [ ] Update `resources/views/admin/orders/index.blade.php` (or show view) status dropdown to use dynamic options

**Requirements:** 2, 3, 4, 5, 6, 7

---

## Task 13: Update Vendor Orders View
Show only the `shipped` option (from `processing`) for wholesale/import sellers.

- [ ] In `resources/views/orders/vendor-orders.blade.php`, use `$service->allowedTransitionsFor($order, auth()->user())` to conditionally show/hide the status update button
- [ ] Update `OrderController::vendorOrders()` to pass allowed transitions

**Requirements:** 5, 8

---

## Task 14: Run Migration on Live Server
Deploy the new `order_status_logs` table.

- [ ] Commit all files
- [ ] Push to `main`
- [ ] On live server: `git pull origin main && php artisan migrate`

**Requirements:** 10
