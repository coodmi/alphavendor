# 🔐 Test Login Credentials for AlphaVendor

## ⚠️ All test accounts use the same password: `password123`

---

## 🔴 1. ADMIN ACCOUNT (Super Admin)
- **Email:** `admin@test.com`
- **Password:** `password123`
- **Role:** Administrator
- **Dashboard:** Admin Dashboard
- **Access:** 
  - Full system access
  - Manage all users, products, orders
  - Site settings & configuration
  - Reports & analytics
  - Manage categories, brands, attributes
  - Approve products & orders
  - Create reminders for sellers
  - Set penalty rules

---

## 🟢 2. RETAILER ACCOUNT (Seller)
- **Email:** `retailer@test.com`
- **Password:** `password123`
- **Role:** Retailer (Seller)
- **Dashboard:** Retailer Dashboard
- **Access:** 
  - Add/Edit/Delete own products
  - View own orders
  - **Can ONLY mark orders as "Shipped"**
  - Manage inventory & stock
  - View product reviews & reply
  - Wallet & withdrawals
  - **Report Analysis Dashboard** 📊
  - View reminders from admin
  - Support tickets

---

## 🔵 3. WHOLESALER ACCOUNT (Bulk Seller)
- **Email:** `wholesaler@test.com`
- **Password:** `password123`
- **Role:** Wholesaler
- **Dashboard:** Wholesaler Dashboard
- **Access:** 
  - Manage wholesale products
  - Bulk orders with MOQ
  - Vendor dashboard
  - Advance payment workflow
  - Same features as Retailer

---

## 🟡 4. EXPORTER/IMPORTER ACCOUNT
- **Email:** `exporter@test.com`
- **Password:** `password123`
- **Role:** Exporter/Importer
- **Dashboard:** Exporter Dashboard
- **Access:** 
  - Manage import/export products
  - International orders
  - Vendor dashboard
  - Same features as Retailer

---

## 🟣 5. REGULAR USER/CUSTOMER ACCOUNT
- **Email:** `user@test.com`
- **Password:** `password123`
- **Role:** Customer
- **Dashboard:** User Dashboard
- **Access:** 
  - Browse products
  - Add to cart & wishlist
  - Place orders
  - View order history
  - Request returns & refunds
  - Submit support tickets
  - Manage profile & addresses

---

## 🌐 LOGIN URLs

### Live Site:
- **URL:** https://armarketbd.com/login
- **Admin Panel:** https://armarketbd.com/admin/dashboard
- **Retailer Panel:** https://armarketbd.com/retailer/dashboard

### Local Development:
- **URL:** http://localhost/login
- **Admin Panel:** http://localhost/admin/dashboard
- **Retailer Panel:** http://localhost/retailer/dashboard

---

## 📝 EXISTING DATABASE ACCOUNTS

### ⚠️ Existing Vendor Accounts (Password: `password`)

These accounts already exist in the database with password: `password` (NOT password123)

#### Retailer Accounts:
- **Email:** `retailer@vendor.com` | **Password:** `password` | **Name:** Fashion Empire Store
- **Email:** `retailer2@vendor.com` | **Password:** `password` | **Name:** Beauty Paradise

#### Wholesaler Accounts:
- **Email:** `wholesaler@vendor.com` | **Password:** `password` | **Name:** Tech Electronics Hub
- **Email:** `wholesaler2@vendor.com` | **Password:** `password` | **Name:** Sports World

#### Exporter Account:
- **Email:** `exporter@vendor.com` | **Password:** `password` | **Name:** Global Exports Inc

### 🔧 How to Create Test Accounts

If test accounts (@test.com) don't exist, run this command on the server:

```bash
php artisan test:create-accounts
```

This will create/update all test accounts with password: `password123`

**Note:** If you can't login with test accounts, they may not exist in the database yet. Use the existing vendor accounts above OR run the command to create test accounts.

---

## 🧪 QUICK TEST SCENARIOS

### 1️⃣ Test Customer Shopping Flow:
```
Login: user@test.com / password123
→ Browse products
→ Add to cart
→ Checkout
→ Place order
→ Track order status
```

### 2️⃣ Test Retailer/Seller Flow:
```
Login: retailer@test.com / password123
→ Go to Retailer Dashboard
→ Add new product (with all fields)
→ View orders
→ Mark order as "Shipped" (ONLY status seller can change)
→ Check Report Analysis Dashboard 📊
→ Reply to product reviews
→ View reminders from admin
→ Check wallet balance
```

### 3️⃣ Test Admin Flow:
```
Login: admin@test.com / password123
→ Go to Admin Dashboard
→ Approve products
→ Manage all orders (change any status)
→ Create reminder for sellers
→ Set delivery penalty rules
→ View reports & analytics
→ Manage categories with meta fields
```

### 4️⃣ Test Report Analysis (Retailer):
```
Login: retailer@test.com / password123
→ Sidebar → "Report Analysis"
→ Select date range
→ View statistics cards
→ Check line chart (orders over time)
→ Check pie chart (orders by status)
→ View top 10 selling products
```

---

## 🎯 KEY FEATURES TO TEST

### ✅ Product Management:
- Add product with minimum 5 images
- Add meta keywords (minimum 5)
- Set MOQ (Minimum Order Quantity)
- Add supplier location
- Add product video (optional)
- After adding, should redirect to dashboard

### ✅ Order Status Control:
- **Retailer:** Can ONLY change to "Shipped"
- **Admin/Employee:** Can change to any status
- Test unauthorized status change (should show error)

### ✅ Report Analysis:
- Filter by date range
- View all statistics
- Check charts rendering
- View top products

### ✅ Category with Meta:
- Add category with meta title
- Add minimum 5 meta keywords
- Add meta description
- Upload category image

### ✅ Reviews & Replies:
- Customer leaves review
- Seller views and replies
- Reply shows on product page

### ✅ Reminders:
- Admin creates reminder for seller
- Seller sees in "Reminders" inbox
- Unread count badge shows
- Mark as read functionality

### ✅ Penalties:
- Admin sets penalty rule (e.g., 3+ days = 500 BDT)
- Order delivered late
- Penalty auto-deducted from wallet
- Seller gets notification

---

## 🔒 PASSWORD RESET

If you need to reset password for any account:

### Via Forgot Password:
1. Go to login page
2. Click "Forgot Password"
3. Enter email
4. Check email for reset link

### Via Admin Panel:
1. Login as admin
2. Go to Users Management
3. Select user
4. Click "Reset Password"

---

## 📞 SUPPORT

If you face any login issues:
1. Clear browser cache
2. Try incognito/private mode
3. Check if account exists in database
4. Verify password is correct
5. Contact system administrator

---

**Created:** April 20, 2026  
**Last Updated:** May 19, 2026  
**Version:** 2.0
