# 🚀 Quick Start Deployment Guide

## Step-by-Step Deployment (5 Minutes)

### Step 1: Upload Files
Upload all project files to your hosting via FTP/cPanel File Manager.

### Step 2: Point Domain to Public Folder
Set your domain's document root to: `public/`

**cPanel Example:**
- Go to "Domains" → Select your domain → Change "Document Root" to: `/home/username/public_html/your-project/public`

### Step 3: Configure Database

1. Create a MySQL database in cPanel
2. Create a database user
3. Assign user to database with all privileges
4. Note down: database name, username, password

### Step 4: Setup .env File

Copy `.env.production` to `.env` and edit:

```env
APP_NAME="Your Shop Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### Step 5: Run Commands via SSH/Terminal

```bash
# Generate app key
php artisan key:generate

# Set permissions
chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 6: Login & Test

Visit: `https://yourdomain.com/login`

**Default Admin:**
- Email: `admin@example.com`
- Password: `password`

**⚠️ Change password immediately!**

---

## 📱 What's Included

✅ **E-commerce Features:**
- Product catalog with categories
- Shopping cart
- Checkout system
- Order management
- User accounts

✅ **Admin Panel:**
- Product management
- Order management
- Category management
- Page content management
- User management

✅ **Mobile Optimized:**
- Responsive design
- Touch-friendly navigation
- Mobile-optimized admin panel

✅ **Performance:**
- Cached routes, views, config
- Minified assets (22.80 KB CSS)
- Optimized database queries
- Browser caching enabled

---

## 🔧 If You Can't Use SSH

### Via cPanel Terminal or PHP Script

Create `install.php` in root:

```php
<?php
// Run this once, then DELETE this file!

echo "Running installation...\n";

// Generate key
exec('php artisan key:generate --force', $output1);
print_r($output1);

// Migrate
exec('php artisan migrate --force', $output2);
print_r($output2);

// Seed
exec('php artisan db:seed --force', $output3);
print_r($output3);

// Storage link
exec('php artisan storage:link', $output4);
print_r($output4);

// Optimize
exec('php artisan config:cache', $output5);
exec('php artisan route:cache', $output6);
exec('php artisan view:cache', $output7);

echo "Installation complete! DELETE THIS FILE NOW!";
?>
```

Visit: `https://yourdomain.com/install.php`

**⚠️ DELETE install.php after running!**

---

## ✅ Verification Checklist

After deployment, verify:

- [ ] Homepage loads correctly
- [ ] Shop page shows products
- [ ] Can add items to cart
- [ ] Admin login works
- [ ] Can upload images in admin
- [ ] Mobile view works properly
- [ ] Navigation scrolls on mobile
- [ ] All pages load without errors

---

## 🆘 Common Issues

### "500 Internal Server Error"
- Check `.env` file exists
- Run `php artisan key:generate`
- Check storage permissions: `chmod -R 775 storage`

### "Database connection error"
- Verify database credentials in `.env`
- Use `localhost` or `127.0.0.1` for DB_HOST
- Ensure database exists

### "Images not loading"
- Run `php artisan storage:link`
- Check storage permissions: `chmod -R 775 storage`

### "CSS/JS not loading"
- Ensure `public/build/` folder exists
- Check `APP_URL` in `.env` matches your domain

---

## 📞 Need Help?

Check logs: `storage/logs/laravel.log`

Enable debug (temporarily):
```env
APP_DEBUG=true
```

**Remember to set back to `false` after fixing!**

---

## 🎉 You're Done!

Your e-commerce platform is now live and ready to use!

**Next Steps:**
1. Change admin password
2. Add your products
3. Customize content
4. Configure payment methods
5. Test checkout process

Good luck! 🚀
