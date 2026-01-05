# 🚀 Quick Start Guide - AlphaVendor

## Server is Running! ✅
**URL:** http://127.0.0.1:8000

---

## 🔑 Login Credentials

### Admin Account
```
Email: admin@alphavendor.com
Password: password
```

### Test Accounts
```
Retailer:   retailer@example.com   / password
Wholesaler: wholesaler@example.com / password
Exporter:   exporter@example.com   / password
User:       user@example.com       / password
```

---

## 🎯 Test the Complete Flow

### 1️⃣ Test User Role Application Flow
1. Register new account at `/register`
2. Login and go to dashboard
3. Click "Apply Now" button
4. Choose role (Retailer/Wholesaler/Exporter)
5. Write application reason (min 20 chars)
6. Submit application
7. See "Application Pending" message

### 2️⃣ Test Admin Approval Flow
1. Logout from user account
2. Login as admin (admin@alphavendor.com)
3. Go to Admin Dashboard
4. Click "View Applications"
5. Click "View" on pending application
6. Add admin notes (optional)
7. Click "Approve Application"
8. User's role is automatically upgraded!

### 3️⃣ Test Admin User Management
1. Login as admin
2. Click "Manage Users"
3. Click "+ Add New User"
4. Fill in user details
5. Select role and status
6. Click "Create User"
7. User can immediately login with chosen role!

---

## 📋 Available URLs

| URL | Access | Description |
|-----|--------|-------------|
| `/` | Public | Home page |
| `/login` | Guest | Login form |
| `/register` | Guest | Registration |
| `/dashboard` | Auth | Auto-redirect to role dashboard |
| `/admin/dashboard` | Admin | Admin dashboard |
| `/admin/users` | Admin | User management |
| `/admin/applications` | Admin | Role applications |
| `/retailer/dashboard` | Retailer | Retailer dashboard |
| `/wholesaler/dashboard` | Wholesaler | Wholesaler dashboard |
| `/exporter/dashboard` | Exporter | Exporter dashboard |
| `/user/dashboard` | User | User dashboard |
| `/apply-role` | User | Apply for vendor role |

---

## 🛠️ Common Commands

```bash
# Start server
php artisan serve

# Stop server
Ctrl+C

# Reset everything
php artisan migrate:fresh --seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## ✨ Key Features Implemented

✅ User Authentication (Login/Register/Logout)
✅ Role-Based Access Control (5 roles)
✅ Individual Dashboards for Each Role
✅ User Role Application System
✅ Admin Approval/Rejection Workflow
✅ Admin User Management (CRUD)
✅ Protected Routes with Middleware
✅ Professional UI with Responsive Design
✅ Success/Error Flash Messages
✅ Database Seeded with Test Data

---

## 🎨 Role Colors & Icons

| Role | Icon | Color |
|------|------|-------|
| Admin | ⚡ | Red |
| Retailer | 🏪 | Blue |
| Wholesaler | 📦 | Purple |
| Exporter | 🌍 | Green |
| User | 👤 | Gray |

---

## 📁 Important Files

**Controllers:**
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/RoleApplicationController.php`

**Models:**
- `app/Models/User.php`
- `app/Models/RoleApplication.php`

**Middleware:**
- `app/Http/Middleware/CheckRole.php`

**Routes:**
- `routes/web.php`

**Views:**
- `resources/views/dashboards/`
- `resources/views/admin/`
- `resources/views/auth/`

---

## 🔍 Testing Checklist

- [ ] Register new user
- [ ] Login with test accounts
- [ ] View role-specific dashboards
- [ ] Apply for vendor role (as user)
- [ ] View applications (as admin)
- [ ] Approve/Reject applications (as admin)
- [ ] Create new user (as admin)
- [ ] Edit user details (as admin)
- [ ] Test protected routes
- [ ] Logout functionality

---

## 💡 Tips

1. **Always login as admin first** to see the full system capabilities
2. **Create a test user** to experience the application flow
3. **Check flash messages** for success/error feedback
4. **Use different browsers** to test multiple roles simultaneously
5. **Read IMPLEMENTATION_SUMMARY.md** for complete details

---

## 🆘 Troubleshooting

**Can't login?**
- Run: `php artisan migrate:fresh --seed`

**Routes not working?**
- Run: `php artisan route:clear`

**Styles not loading?**
- Check `public/css/style.css` exists
- Hard refresh browser (Ctrl+F5)

**Server stopped?**
- Run: `php artisan serve`

---

**Status:** ✅ READY TO USE
**Current Time:** Project fully implemented
**Server:** http://127.0.0.1:8000

🎉 **Happy Testing!**
