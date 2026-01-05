# AlphaVendor System Architecture

## User Role Hierarchy

```
┌─────────────────────────────────────────────────────────┐
│                         ADMIN                           │
│  • Full System Access                                   │
│  • Manage All Users                                     │
│  • Approve/Reject Applications                          │
│  • View System Statistics                               │
└─────────────────────────────────────────────────────────┘
                           │
          ┌────────────────┼────────────────┐
          │                │                │
┌─────────▼──────┐ ┌──────▼───────┐ ┌─────▼──────────┐
│   RETAILER     │ │  WHOLESALER  │ │   EXPORTER     │
│  • Products    │ │  • Bulk Sale │ │  • Int'l Sales │
│  • Orders      │ │  • Orders    │ │  • Shipping    │
│  • Dashboard   │ │  • Dashboard │ │  • Dashboard   │
└────────────────┘ └──────────────┘ └────────────────┘
                           │
                  ┌────────▼────────┐
                  │      USER       │
                  │  • Shopping     │
                  │  • Apply Role   │
                  │  • Dashboard    │
                  └─────────────────┘
```

## Application Flow

### User Registration & Role Application

```
┌──────────┐     ┌───────────┐     ┌──────────────┐
│ Register │────▶│   Login   │────▶│ User         │
│ Account  │     │ (user)    │     │ Dashboard    │
└──────────┘     └───────────┘     └──────┬───────┘
                                           │
                                           ▼
                                  ┌────────────────┐
                                  │  Apply for     │
                                  │  Vendor Role   │
                                  └────────┬───────┘
                                           │
                                           ▼
                                  ┌────────────────┐
                                  │  Application   │
                                  │    Pending     │
                                  └────────┬───────┘
                                           │
                          ┌────────────────┼────────────────┐
                          │                                 │
                    ┌─────▼──────┐                  ┌──────▼──────┐
                    │   ADMIN    │                  │    ADMIN    │
                    │  Approves  │                  │   Rejects   │
                    └─────┬──────┘                  └──────┬──────┘
                          │                                 │
                    ┌─────▼──────┐                  ┌──────▼──────┐
                    │ Role       │                  │ Application │
                    │ Upgraded!  │                  │ Rejected    │
                    └─────┬──────┘                  └──────┬──────┘
                          │                                 │
              ┌───────────▼────────────┐                   │
              │ Access Vendor Dashboard │                  │
              │ (Retailer/Wholesaler/  │                   │
              │      Exporter)         │                   │
              └────────────────────────┘                   │
                                                            │
                                           ┌────────────────▼────┐
                                           │ User remains User   │
                                           │ Can reapply later   │
                                           └─────────────────────┘
```

## Database Relationships

```
┌─────────────────────┐
│       USERS         │
│──────────────────── │
│ id                  │
│ name                │
│ email               │
│ password            │
│ role (enum)         │◀────────┐
│ status (enum)       │         │
│ timestamps          │         │
└──────────┬──────────┘         │
           │                    │
           │                    │ reviewed_by
           │ user_id            │
           │                    │
           ▼                    │
┌─────────────────────┐         │
│ ROLE_APPLICATIONS   │         │
│──────────────────── │         │
│ id                  │         │
│ user_id (FK)        │─────────┘
│ requested_role      │
│ reason              │
│ status (enum)       │
│ admin_notes         │
│ reviewed_by (FK)    │
│ reviewed_at         │
│ timestamps          │
└─────────────────────┘
```

## Route Protection Flow

```
┌───────────────┐
│   Request     │
│   /admin/*    │
└───────┬───────┘
        │
        ▼
┌───────────────┐
│ Middleware:   │
│ auth          │
└───────┬───────┘
        │
    ┌───▼───┐
    │ User  │ NO
    │logged?│────▶ Redirect to /login
    └───┬───┘
        │ YES
        ▼
┌───────────────┐
│ Middleware:   │
│ role:admin    │
└───────┬───────┘
        │
    ┌───▼───┐
    │ User  │ NO
    │admin? │────▶ 403 Forbidden
    └───┬───┘
        │ YES
        ▼
┌───────────────┐
│  Allow Access │
│  to Admin     │
│  Dashboard    │
└───────────────┘
```

## Feature Matrix

| Feature | User | Retailer | Wholesaler | Exporter | Admin |
|---------|------|----------|------------|----------|-------|
| Register | ✓ | - | - | - | - |
| Login | ✓ | ✓ | ✓ | ✓ | ✓ |
| Shopping | ✓ | ✓ | ✓ | ✓ | ✓ |
| Apply for Role | ✓ | - | - | - | - |
| Sell Products | - | ✓ | ✓ | ✓ | - |
| Bulk Sales | - | - | ✓ | - | - |
| Int'l Shipping | - | - | - | ✓ | - |
| View Applications | - | - | - | - | ✓ |
| Approve Roles | - | - | - | - | ✓ |
| Manage Users | - | - | - | - | ✓ |
| System Stats | - | - | - | - | ✓ |

## Authentication Flow

```
                    ┌──────────────────┐
                    │   Guest User     │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │  Visit /login    │
                    │   or /register   │
                    └────────┬─────────┘
                             │
              ┌──────────────┼──────────────┐
              │                             │
    ┌─────────▼────────┐         ┌─────────▼────────┐
    │  Login with      │         │  Register new    │
    │  credentials     │         │  account         │
    └─────────┬────────┘         └─────────┬────────┘
              │                             │
              └──────────────┬──────────────┘
                             │
                    ┌────────▼─────────┐
                    │  Create Session  │
                    │  Store user_id   │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │ Redirect to      │
                    │ /dashboard       │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │ Check user role  │
                    └────────┬─────────┘
                             │
          ┌──────────────────┼──────────────────┐
          │                  │                  │
    ┌─────▼─────┐    ┌──────▼──────┐    ┌─────▼──────┐
    │   Admin   │    │   Vendor    │    │    User    │
    │ Dashboard │    │  Dashboard  │    │  Dashboard │
    └───────────┘    └─────────────┘    └────────────┘
```

## Security Layers

```
┌────────────────────────────────────────────┐
│        Application Layer                   │
│  • Input Validation                        │
│  • CSRF Protection                         │
│  • Password Hashing                        │
└──────────────┬─────────────────────────────┘
               │
┌──────────────▼─────────────────────────────┐
│        Middleware Layer                    │
│  • Authentication Check                    │
│  • Role Verification                       │
│  • Session Management                      │
└──────────────┬─────────────────────────────┘
               │
┌──────────────▼─────────────────────────────┐
│        Database Layer                      │
│  • SQL Injection Prevention (Eloquent)     │
│  • Foreign Key Constraints                 │
│  • Data Integrity                          │
└────────────────────────────────────────────┘
```

## Data Flow: Role Application Approval

```
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│  User    │────▶│  Submit  │────▶│  Stored  │────▶│  Admin   │
│  Applies │     │  Form    │     │  in DB   │     │  Notified│
└──────────┘     └──────────┘     └──────────┘     └─────┬────┘
                                                           │
                                                           │
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌─────▼────┐
│  User    │◀────│  Update  │◀────│  Update  │◀────│  Admin   │
│  Gets    │     │  User    │     │  App     │     │  Reviews │
│  Upgrade │     │  Role    │     │  Status  │     └──────────┘
└──────────┘     └──────────┘     └──────────┘
```

---

## Summary

This architecture provides:

✅ **Clear Role Separation** - Each role has distinct capabilities
✅ **Secure Access Control** - Multiple security layers protect routes
✅ **Flexible Role Assignment** - Users can apply or admin can directly assign
✅ **Audit Trail** - All applications tracked with timestamps and reviewers
✅ **Scalable Design** - Easy to add new roles or features
✅ **Professional Flow** - Industry-standard authentication and authorization

**Status:** Fully Implemented ✓
