# AlphaVendor - Multi-Vendor Laravel Application

A complete multi-vendor Laravel application with role-based authentication and individual dashboards for Admin, Retailer, Wholesaler, Exporter, and regular Users.

## Features

### 🔐 Authentication System
- User registration and login
- Role-based access control
- Secure password hashing
- Session management

### 👥 User Roles
1. **Admin** - Full system access
   - Manage all users (create, edit, delete)
   - View and manage role applications
   - Approve/reject vendor applications
   - System-wide statistics dashboard

2. **Retailer** - Sell individual products
   - Personal dashboard
   - Product management (coming soon)
   - Order tracking

3. **Wholesaler** - Bulk sales
   - Personal dashboard
   - Bulk product management (coming soon)
   - Wholesale order tracking

4. **Exporter** - International sales
   - Personal dashboard
   - Export product management (coming soon)
   - International shipping management

5. **User** - Regular customers
   - Personal dashboard
   - Apply for vendor roles
   - Shopping and order history

### ✨ Key Functionality
- Users can apply for vendor roles (Retailer, Wholesaler, Exporter)
- Admin reviews and approves/rejects applications
- Automatic role assignment upon approval
- Individual dashboards for each role
- Protected routes with middleware

## Installation

### 1. Clone the Repository
```bash
cd "d:\Office File\Alpha vendor\alphavendor"
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
Make sure your `.env` file is configured with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alphavendor
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Seed Database (Create Admin User)
```bash
php artisan db:seed
```

### 6. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Login Credentials

After seeding, you can login with these accounts:

### Admin Account
- **Email:** admin@alphavendor.com
- **Password:** password

### Test Accounts
- **Retailer:** retailer@example.com / password
- **Wholesaler:** wholesaler@example.com / password
- **Exporter:** exporter@example.com / password
- **User:** user@example.com / password

## Usage Guide

### For Regular Users
1. Register a new account at `/register`
2. Login with your credentials
3. Access your dashboard
4. Apply for a vendor role from your dashboard
5. Wait for admin approval

### For Admin
1. Login with admin credentials
2. Access Admin Dashboard
3. Manage Users:
   - Add new users with specific roles
   - Edit existing user details
   - Change user roles and status
4. Review Applications:
   - View all pending applications
   - Approve or reject with notes
   - Automatic role assignment on approval

### For Vendors (Retailer/Wholesaler/Exporter)
1. Login with vendor credentials
2. Access your role-specific dashboard
3. Manage your products and orders (coming soon)

## Routes Overview

### Public Routes
- `/` - Home page
- `/shop` - Shop page
- `/retail` - Retail page
- `/wholesale` - Wholesale page
- `/export` - Export page
- `/login` - Login page
- `/register` - Registration page

### Authenticated Routes
- `/dashboard` - Redirects to role-specific dashboard
- `/logout` - Logout (POST)

### Admin Routes (Protected)
- `/admin/dashboard` - Admin dashboard
- `/admin/users` - User management
- `/admin/users/create` - Add new user
- `/admin/users/{user}/edit` - Edit user
- `/admin/applications` - View role applications
- `/admin/applications/{application}` - View application details
- `/admin/applications/{application}/approve` - Approve application
- `/admin/applications/{application}/reject` - Reject application

### Vendor Routes (Protected)
- `/retailer/dashboard` - Retailer dashboard
- `/wholesaler/dashboard` - Wholesaler dashboard
- `/exporter/dashboard` - Exporter dashboard

### User Routes (Protected)
- `/user/dashboard` - User dashboard
- `/apply-role` - Apply for vendor role

## Database Structure

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `role` - Enum: admin, retailer, wholesaler, exporter, user
- `status` - Enum: active, inactive, pending
- `timestamps`

### Role Applications Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `requested_role` - Enum: retailer, wholesaler, exporter
- `reason` - Text explanation
- `status` - Enum: pending, approved, rejected
- `admin_notes` - Admin's notes
- `reviewed_by` - Foreign key to admin user
- `reviewed_at` - Timestamp
- `timestamps`

## Middleware

### CheckRole Middleware
Protects routes based on user roles:
```php
Route::middleware('role:admin')->group(function () {
    // Admin only routes
});

Route::middleware('role:retailer,wholesaler')->group(function () {
    // Multiple roles allowed
});
```

## Development Notes

### Adding New Features
- Controllers are located in `app/Http/Controllers/`
- Models are in `app/Models/`
- Views are in `resources/views/`
- Routes are defined in `routes/web.php`

### Customization
- Modify dashboard views in `resources/views/dashboards/`
- Update styles inline or in `public/css/`
- Add new middleware in `app/Http/Middleware/`

## Security Features
- CSRF protection on all forms
- Password hashing with bcrypt
- Role-based access control
- Session management
- Input validation
- Protected routes

## Future Enhancements
- Product management for vendors
- Order processing system
- Payment integration
- Email notifications
- Advanced analytics
- Multi-language support
- API for mobile apps

## Troubleshooting

### Migration Issues
```bash
php artisan migrate:fresh --seed
```

### Cache Issues
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Permission Issues
Make sure storage and bootstrap/cache directories are writable:
```bash
chmod -R 775 storage bootstrap/cache
```

## Support
For issues or questions, please contact the development team.

## License
This is a proprietary application for AlphaVendor.

---

**Version:** 1.0.0  
**Last Updated:** January 5, 2026
