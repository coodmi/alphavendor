# Requirements Document

## Introduction

This feature implements a dedicated order status flow for wholesale and import orders in the AlphaVendor Laravel multi-vendor marketplace. Wholesale orders (placed with vendors of role `wholesaler`) and import orders (placed with vendors of role `exporter`) require a distinct 7-step status lifecycle that includes advance payment verification before order confirmation. This flow is separate from the existing retail order flow, which remains unchanged. The system enforces strict role-based rules on which actors (Admin, Employee, Seller, Customer) may transition an order to each status.

## Glossary

- **Order_Status_Machine**: The component responsible for validating and applying wholesale/import order status transitions.
- **Wholesale_Order**: An order placed against a vendor with role `wholesaler`.
- **Import_Order**: An order placed against a vendor with role `exporter`.
- **Retail_Order**: An order placed against a vendor with role `retailer`. Its status flow is not changed by this feature.
- **Admin**: A platform user with role `admin`. Has full authority over all status transitions.
- **Employee**: A platform user with role `employee`. Has the same transition authority as Admin for order statuses.
- **Seller**: A vendor user with role `wholesaler` or `exporter`. Has limited transition authority.
- **Customer**: A platform user with role `user`. Cannot change any order status.
- **Advance_Payment**: A partial upfront payment made by the customer before the order is confirmed.
- **Order_Status_Log**: An audit record capturing each status change, the actor who made it, and the timestamp.

---

## Requirements

### Requirement 1: Wholesale/Import Order Initial Status

**User Story:** As a customer, I want my wholesale or import order to start in a clear "pending advance payment" state, so that I know I need to pay the advance amount before the order proceeds.

#### Acceptance Criteria

1. WHEN a customer places a wholesale or import order AND advance payment is mandatory for the platform, THE Order_Status_Machine SHALL set the initial order status to `pending_advance_payment`.
2. IF advance payment is NOT mandatory, THEN a wholesale or import order SHALL be initialised to `pending` instead of `pending_advance_payment`.
3. IF an order is a Retail_Order, THEN THE Order_Status_Machine SHALL NOT set its initial status to `pending_advance_payment`; retail orders SHALL be initialised to `pending`.

---

### Requirement 2: Advance Payment Verification (Step 2)

**User Story:** As an admin or employee, I want to manually verify the customer's advance payment and update the order status, so that the order can proceed to confirmation.

#### Acceptance Criteria

1. WHEN an Admin or Employee submits a status update request for a wholesale/import order with current status `pending_advance_payment` and target status `advance_paid`, THE Order_Status_Machine SHALL apply the transition, persist the new status, and leave no other order fields mutated by the rejection path.
2. IF a Seller attempts to transition a wholesale/import order from `pending_advance_payment` to `advance_paid`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF a Customer attempts to transition a wholesale/import order from `pending_advance_payment` to `advance_paid`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
4. IF an Admin or Employee attempts to transition a wholesale/import order to `advance_paid` from any status other than `pending_advance_payment`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted from the current status, and SHALL leave the order status unchanged.
5. IF a status update request targets an order that does not exist, THEN THE Order_Status_Machine SHALL return a not-found error response without mutating any data.

---

### Requirement 3: Order Confirmation (Step 3)

**User Story:** As an admin or employee, I want to officially confirm a wholesale/import order after the advance payment is verified, so that the seller can begin processing it.

#### Acceptance Criteria

1. WHEN an Admin or Employee submits a status update request for a wholesale/import order with current status `advance_paid` and target status `order_confirmed`, THE Order_Status_Machine SHALL apply the transition and persist the new status.
2. IF a Seller attempts to transition a wholesale/import order to `order_confirmed`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF a Customer attempts to transition a wholesale/import order to `order_confirmed`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
4. IF an Admin or Employee attempts to transition a wholesale/import order to `order_confirmed` from any status other than `advance_paid`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted from the current status, and SHALL leave the order status unchanged.

---

### Requirement 4: Order Processing (Step 4)

**User Story:** As an admin, employee, or seller, I want to mark a confirmed wholesale/import order as processing, so that all parties know the order is being actively prepared.

#### Acceptance Criteria

1. WHEN an Admin, Employee, or Seller submits a status update request for a wholesale/import order with current status `order_confirmed` and target status `processing`, THE Order_Status_Machine SHALL apply the transition and persist the new status.
2. IF a Customer attempts to transition a wholesale/import order to `processing`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF any actor attempts to transition a wholesale/import order to `processing` from any status other than `order_confirmed`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted from the current status, and SHALL leave the order status unchanged.

---

### Requirement 5: Shipping (Step 5)

**User Story:** As a seller, admin, or employee, I want to mark a processing wholesale/import order as shipped, so that the customer knows their order is on its way.

#### Acceptance Criteria

1. WHEN an Admin, Employee, or Seller submits a status update request for a wholesale/import order with current status `processing` and target status `shipped`, THE Order_Status_Machine SHALL apply the transition and persist the new status.
2. IF a Customer attempts to transition a wholesale/import order to `shipped`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF any actor attempts to transition a wholesale/import order to `shipped` from any status other than `processing`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted from the current status, and SHALL leave the order status unchanged.

---

### Requirement 6: Delivery Confirmation (Step 6)

**User Story:** As an admin or employee, I want to confirm delivery of a shipped wholesale/import order, so that the order lifecycle is completed and the seller can be paid.

#### Acceptance Criteria

1. WHEN an Admin or Employee submits a status update request for a wholesale/import order with current status `shipped` and target status `delivered`, THE Order_Status_Machine SHALL apply the transition and persist the new status.
2. IF a Seller attempts to transition a wholesale/import order to `delivered`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF a Customer attempts to transition a wholesale/import order to `delivered`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
4. IF an Admin or Employee attempts to transition a wholesale/import order to `delivered` from any status other than `shipped`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted from the current status, and SHALL leave the order status unchanged.

---

### Requirement 7: Order Cancellation

**User Story:** As an admin or employee, I want to cancel a wholesale/import order that has not yet been delivered, so that I can handle disputes, non-payment, or operational issues.

#### Acceptance Criteria

1. WHEN an Admin or Employee submits a status update request for a wholesale/import order with current status in `[pending_advance_payment, advance_paid, order_confirmed, processing]` and target status `cancelled`, THE Order_Status_Machine SHALL apply the transition and persist the new status.
2. IF a Seller attempts to cancel a wholesale/import order, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF a Customer attempts to cancel a wholesale/import order, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
4. IF an Admin or Employee attempts to set the status of a wholesale/import order to `cancelled` and the current status is `shipped` or `delivered`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted from the current status, and SHALL leave the order status unchanged.

---

### Requirement 8: Seller Forbidden Statuses

**User Story:** As a platform operator, I want to ensure sellers can never set certain statuses on wholesale/import orders, so that financial and operational controls remain with admin/employee staff.

#### Acceptance Criteria

1. IF a Seller attempts to set a wholesale/import order status to `pending_advance_payment`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
2. IF a Seller attempts to set a wholesale/import order status to `advance_paid`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
3. IF a Seller attempts to set a wholesale/import order status to `processing` (Sellers may only transition FROM `processing` to `shipped`, not TO `processing`), THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
4. IF a Seller attempts to set a wholesale/import order status to `delivered`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.
5. IF a Seller attempts to set a wholesale/import order status to `cancelled`, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the requester is not authorised, and SHALL leave the order status unchanged.

---

### Requirement 9: Retail Order Flow Unchanged

**User Story:** As a retailer, I want my existing order status flow to remain unaffected, so that my operations are not disrupted by changes to the wholesale/import flow.

#### Acceptance Criteria

1. WHILE an order is a Retail_Order, THE Order_Status_Machine SHALL permit only the following ordered transitions: `pending → processing`, `processing → shipped`, `shipped → delivered`, `delivered` (terminal), `any non-delivered → cancelled`.
2. WHILE an order is a Retail_Order, THE Order_Status_Machine SHALL NOT apply wholesale/import transition rules to it.
3. WHEN a Retail_Order has no status set, THE Order_Status_Machine SHALL initialise its status to `pending`.
4. IF a Retail_Order transition is attempted that is not in the permitted set, THEN THE Order_Status_Machine SHALL reject the request with an error response indicating the transition is not permitted.

---

### Requirement 10: Status Transition Audit Log

**User Story:** As an admin, I want every order status change to be recorded with the actor and timestamp, so that I have a full audit trail for dispute resolution and compliance.

#### Acceptance Criteria

1. WHEN any status transition is successfully applied to a wholesale/import order, THE Order_Status_Machine SHALL create an Order_Status_Log entry recording: order ID, previous status, new status, actor user ID, actor role, and timestamp in UTC ISO-8601 format.
2. THE Order_Status_Machine SHALL make Order_Status_Log entries retrievable by order ID.
3. IF creating an Order_Status_Log entry fails, THEN THE Order_Status_Machine SHALL allow the status transition to succeed and SHALL record the failure in the application's error output so it is observable without inspecting the database.

---

### Requirement 11: API Endpoint for Status Updates

**User Story:** As a developer, I want a single, role-aware HTTP endpoint for updating wholesale/import order status, so that all dashboards (Admin, Employee, Wholesaler, Exporter) can use a consistent interface.

#### Acceptance Criteria

1. THE System SHALL expose a `PATCH /orders/{order}/wholesale-status` HTTP endpoint that accepts a `status` parameter.
2. WHEN the endpoint receives a request, THE System SHALL authenticate the requesting user and determine their role before delegating to the Order_Status_Machine.
3. IF the Order_Status_Machine rejects the transition due to an authorisation failure, THEN THE System SHALL return an HTTP 403 response with an error message indicating the reason the transition was rejected.
4. IF the Order_Status_Machine rejects the transition due to an invalid state transition, THEN THE System SHALL return an HTTP 422 response with an error message indicating the reason the transition was rejected.
5. IF the `status` parameter is missing or not a recognised status value, THEN THE System SHALL return an HTTP 422 response.
6. IF the Order_Status_Machine accepts the transition, THEN THE System SHALL return an HTTP 200 response with the updated order data.
7. IF the requesting user is unauthenticated, THEN THE System SHALL return an HTTP 401 response.
