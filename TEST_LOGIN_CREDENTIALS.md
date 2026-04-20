# Test Login Credentials for AlphaVendor

## All accounts use the same password: `password123`

---

## 1. Admin Account
- **Email:** admin@test.com
- **Password:** password123
- **Role:** Administrator
- **Access:** Full system access, manage all users, products, orders, settings

---

## 2. Regular User/Customer Account
- **Email:** user@test.com
- **Password:** password123
- **Role:** Customer
- **Access:** Browse products, place orders, manage wishlist, view order history

---

## 3. Retailer Account
- **Email:** retailer@test.com
- **Password:** password123
- **Role:** Retailer
- **Access:** Manage retail products, view orders, manage inventory, vendor dashboard

---

## 4. Wholesaler Account
- **Email:** wholesaler@test.com
- **Password:** password123
- **Role:** Wholesaler
- **Access:** Manage wholesale products, bulk orders, vendor dashboard, minimum order quantities

---

## 5. Exporter/Importer Account
- **Email:** exporter@test.com
- **Password:** password123
- **Role:** Exporter (Import/Export)
- **Access:** Manage import/export products, international orders, vendor dashboard

---

## Existing Accounts in Database

### Admin
- admin@alphavendor.com (ID: 1)

### Retailers
- retailer@example.com (ID: 2)
- retailer@vendor.com (ID: 6)
- retailer2@vendor.com (ID: 9)

### Wholesalers
- wholesaler@example.com (ID: 3)
- wholesaler@vendor.com (ID: 7)
- wholesaler2@vendor.com (ID: 10)

### Exporters/Importers
- importer@example.com (ID: 4)
- exporter@vendor.com (ID: 8)

### Regular Users
- user@example.com (ID: 5)

---

## Login URL
- **Website:** https://armarketbd.com/login
- **Local:** http://localhost/login

---

## Notes
- All test accounts (@test.com) have been created with password: `password123`
- Existing accounts may have different passwords set by the system
- To reset password for existing accounts, use the "Forgot Password" feature
- Admin can reset any user password from the admin panel

---

## Quick Test Steps

1. **Test Customer Flow:**
   - Login as: user@test.com
   - Browse products
   - Add to cart
   - Place order

2. **Test Vendor Flow:**
   - Login as: retailer@test.com (or wholesaler/exporter)
   - Access vendor dashboard
   - Add products
   - Manage orders

3. **Test Admin Flow:**
   - Login as: admin@test.com
   - Access admin panel
   - Manage users, products, orders
   - Configure site settings

---

**Created:** April 20, 2026
**Last Updated:** April 20, 2026
