# Requirements Document

## Introduction

This feature enforces a strict 7-step order status flow for wholesale and importer orders, with role-based permissions controlling which actors may advance an order to each step. The same flow applies to retail orders where the customer made an advance payment. The implementation touches four areas: backend transition validation in `AdminController` and `EmployeeDashboardController`, vendor-side restriction enforcement in `OrderController` and `RetailerDashboardController`, admin/employee UI that surfaces only valid next statuses, vendor UI that shows the "Mark as Shipped" button only when the current status is `processing`, and a customer-facing status timeline on the order detail page.

## Glossary

- **Order_Status_Flow**: The ordered sequence of statuses an order must pass through: `pending_advance_payment` → `advance_paid` → `order_confirmed` → `processing` → `shipped` → `delivered`. `cancelled` is a terminal side-exit available from steps 1–4.
- **Status_Transition_Guard**: The server-side logic (in `AdminController`, `EmployeeDashboardController`, `OrderController`, and `RetailerDashboardController`) that rejects any status change that does not follow the allowed transition map.
- **Admin**: A user whose `role` is `admin`.
- **Employee**: A user whose `role` is `employee`.
- **Vendor**: A user whose `role` is `wholesaler`, `importer`, `exporter`, or `retailer` — acting as the seller of the ordered products.
- **Customer**: A user whose `role` is `user` — the buyer of the ordered products.
- **Advance_Order**: An order whose initial status is `pending_advance_payment` (wholesale/importer orders, or retail orders where advance payment is mandatory).
- **Status_Timeline**: A visual progress bar or step indicator shown to the Customer on the order detail page, reflecting the current position in the Order_Status_Flow.
- **OrderStatus_Helper**: The existing `App\Helpers\OrderStatus` class that defines all status labels, colors, and icons.

## Requirements

---

### Requirement 1: Automatic Initial Status Assignment

**User Story:** As an Admin, I want wholesale and importer orders to automatically start at `pending_advance_payment`, so that the advance payment step is never skipped.

#### Acceptance Criteria

1. WHEN an order is created for a vendor whose `role` is `wholesaler` or `importer`, THE Order_Status_Flow SHALL set the order's initial `status` to `pending_advance_payment`.
2. WHEN an order is created for a vendor whose `role` is `retailer` and the `AdvancePaymentSetting.is_mandatory` flag is `true`, THE Order_Status_Flow SHALL set the order's initial `status` to `pending_advance_payment`.
3. WHEN an order is created for a vendor whose `role` is `retailer` and the `AdvancePaymentSetting.is_mandatory` flag is `false`, THE Order_Status_Flow SHALL set the order's initial `status` to `pending`.
4. WHEN an order is created for a vendor whose `role` is `exporter`, THE Order_Status_Flow SHALL set the order's initial `status` to `pending_advance_payment`.

---

### Requirement 2: Admin and Employee Status Transition Enforcement

**User Story:** As an Admin or Employee, I want the system to prevent me from skipping steps in the order flow, so that orders always progress in the correct sequence.

#### Acceptance Criteria

1. THE Status_Transition_Guard SHALL define the following allowed forward transitions for Advance_Orders:
   - `pending_advance_payment` → `advance_paid`
   - `advance_paid` → `order_confirmed`
   - `order_confirmed` → `processing`
   - `processing` → `shipped`
   - `shipped` → `delivered`
2. THE Status_Transition_Guard SHALL define the following allowed cancellation transitions:
   - `pending_advance_payment` → `cancelled`
   - `advance_paid` → `cancelled`
   - `order_confirmed` → `cancelled`
   - `processing` → `cancelled`
3. IF an Admin or Employee submits a status change that is not in the allowed transition map for the order's current status, THEN THE Status_Transition_Guard SHALL reject the request and return an error message identifying the invalid transition.
4. IF an Admin or Employee attempts to change the status of an order whose current status is `shipped` or `delivered` to `cancelled`, THEN THE Status_Transition_Guard SHALL reject the request with an error message stating that cancellation is not permitted after shipping.
5. IF an Admin or Employee attempts to change the status of an order whose current status is `delivered` to any other status, THEN THE Status_Transition_Guard SHALL reject the request with an error message stating that delivered orders cannot be changed.
6. WHEN a valid status transition is submitted by an Admin or Employee, THE Status_Transition_Guard SHALL update the order's `status` field and redirect back with a success message.
7. THE Status_Transition_Guard SHALL apply the same transition rules in both `AdminController::updateOrderStatus()` and `EmployeeDashboardController::updateOrderStatus()`.

---

### Requirement 3: Vendor Status Restriction Enforcement

**User Story:** As a system owner, I want vendors to be able to mark orders as shipped only when the order is in `processing` status, so that vendors cannot manipulate the flow at any other step.

#### Acceptance Criteria

1. WHEN a Vendor submits a status update request, THE Status_Transition_Guard SHALL accept only `shipped` as the target status value; all other values SHALL be rejected with a validation error.
2. IF a Vendor submits a request to mark an order as `shipped` and the order's current status is NOT `processing`, THEN THE Status_Transition_Guard SHALL reject the request and return an error message stating that orders can only be marked as shipped when they are in `processing` status.
3. WHEN a Vendor submits a request to mark an order as `shipped` and the order's current status IS `processing`, THE Status_Transition_Guard SHALL update the order's `status` to `shipped` and redirect back with a success message.
4. THE Status_Transition_Guard SHALL enforce the vendor restriction in both `OrderController::updateStatus()` (for wholesaler/importer/exporter vendors) and `RetailerDashboardController::updateOrderStatus()` (for retailer vendors).
5. IF a Vendor submits a status update for an order that does not belong to that Vendor, THEN THE Status_Transition_Guard SHALL reject the request with a 403 Forbidden response.

---

### Requirement 4: Admin Orders UI — Contextual Status Dropdown

**User Story:** As an Admin, I want the order list to show only the valid next statuses in the status dropdown, so that I cannot accidentally set an invalid status.

#### Acceptance Criteria

1. WHEN the Admin orders list page renders a status dropdown for an order, THE Admin_Orders_View SHALL populate the dropdown with only the statuses that are valid next transitions from the order's current status, as defined by the Status_Transition_Guard transition map.
2. WHEN an order's current status is `delivered`, THE Admin_Orders_View SHALL render the status field as a read-only display (not a dropdown) showing "Delivered".
3. WHEN an order's current status is `cancelled`, THE Admin_Orders_View SHALL render the status field as a read-only display (not a dropdown) showing "Cancelled".
4. THE Admin_Orders_View SHALL display the current status as the selected/highlighted value in the dropdown or read-only display.
5. THE Admin_Orders_View SHALL use the `OrderStatus_Helper` labels and color classes for all status values rendered in the dropdown and badges.

---

### Requirement 5: Employee Dashboard — Same Status Permissions as Admin

**User Story:** As an Employee, I want to manage order statuses with the same permissions as an Admin, so that I can fully support order operations without needing admin credentials.

#### Acceptance Criteria

1. THE Employee_Orders_View SHALL render a status dropdown for each order using the same valid-next-transitions logic as the Admin_Orders_View.
2. WHEN an Employee submits a status update, THE Status_Transition_Guard SHALL apply the same transition rules as it does for Admin submissions.
3. THE Employee_Orders_View SHALL include all seven statuses in the Order_Status_Flow (`pending_advance_payment`, `advance_paid`, `order_confirmed`, `processing`, `shipped`, `delivered`, `cancelled`) as possible values, subject to the transition rules.
4. WHEN an order's current status is `delivered` or `cancelled`, THE Employee_Orders_View SHALL render the status field as a read-only display, not a dropdown.

---

### Requirement 6: Vendor Orders UI — Conditional "Mark as Shipped" Button

**User Story:** As a Vendor, I want to see the "Mark as Shipped" button only when my order is in `processing` status, so that I am not confused by a button that will be rejected by the server.

#### Acceptance Criteria

1. WHEN the Vendor orders list page renders an order whose `status` is `processing`, THE Vendor_Orders_View SHALL display a "Mark as Shipped" button for that order.
2. WHEN the Vendor orders list page renders an order whose `status` is NOT `processing`, THE Vendor_Orders_View SHALL NOT display the "Mark as Shipped" button for that order.
3. THE Vendor_Orders_View SHALL apply criterion 1 and 2 to both the wholesaler/importer/exporter vendor view (`resources/views/orders/vendor-orders.blade.php`) and the retailer vendor view (`resources/views/retailer/orders/index.blade.php`).
4. WHEN the "Mark as Shipped" button is clicked, THE Vendor_Orders_View SHALL prompt the Vendor for confirmation before submitting the form.

---

### Requirement 7: Customer Order Detail — Status Timeline

**User Story:** As a Customer, I want to see a visual timeline of my order's progress on the order detail page, so that I can understand where my order is in the fulfillment process at a glance.

#### Acceptance Criteria

1. WHEN a Customer views the order detail page for an Advance_Order, THE Status_Timeline SHALL display the following steps in sequence: "Pending Advance Payment", "Advance Paid", "Order Confirmed", "Processing", "Shipped", "Delivered".
2. WHEN a Customer views the order detail page for a non-advance order (initial status `pending`), THE Status_Timeline SHALL display the following steps in sequence: "Pending", "Processing", "Shipped", "Delivered".
3. THE Status_Timeline SHALL visually distinguish completed steps (all steps before the current status) from the current step and from future steps.
4. WHEN an order's `status` is `cancelled`, THE Status_Timeline SHALL display a "Cancelled" indicator instead of the normal progress timeline.
5. THE Status_Timeline SHALL use the color and icon definitions from the `OrderStatus_Helper` for each step.
6. THE Status_Timeline SHALL be rendered on the customer order detail page at `resources/views/orders/show.blade.php`.
7. THE Status_Timeline SHALL be accessible, using sufficient color contrast and text labels alongside icons so that status is not conveyed by color alone.

---

### Requirement 8: Transition Map Centralisation in OrderStatus Helper

**User Story:** As a developer, I want the allowed status transitions to be defined in one place, so that all controllers and views derive their rules from a single source of truth.

#### Acceptance Criteria

1. THE OrderStatus_Helper SHALL expose a static method `allowedTransitions(string $currentStatus, string $actorRole): array` that returns the list of statuses the given actor may transition to from the given current status.
2. WHEN `actorRole` is `admin` or `employee`, THE OrderStatus_Helper SHALL return all valid next statuses per the Admin/Employee transition map defined in Requirement 2.
3. WHEN `actorRole` is `vendor` (wholesaler, importer, exporter, or retailer), THE OrderStatus_Helper SHALL return `['shipped']` only if `currentStatus` is `processing`, and an empty array otherwise.
4. WHEN `actorRole` is `customer`, THE OrderStatus_Helper SHALL return an empty array.
5. THE Status_Transition_Guard in all four controllers SHALL call `OrderStatus_Helper::allowedTransitions()` to determine whether a requested transition is permitted, rather than duplicating the transition logic inline.
