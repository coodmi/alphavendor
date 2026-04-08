# Production Deployment Guide

## 🚀 Quick Deployment Steps

### 1. Upload Files to Hosting
Upload all project files to your hosting server (via FTP, cPanel File Manager, or Git).

**Important folders to upload:**
- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `public/`
- `resources/`
- `routes/`
- `storage/`
- `vendor/` (if already generated, otherwise run composer install on server)
- All root files (`.htaccess`, `artisan`, `composer.json`, etc.)

### 2. Set Document Root
Point your domain's document root to the `public/` folder.

**Example paths:**
- cPanel: `public_html/your-project/public`
- Direct: `/var/www/html/your-project/public`

### 3. Configure Environment (.env)

Create/edit `.env` file in the root directory:

```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Generate Application Key

If you don't have an APP_KEY, run:
```bash
php artisan key:generate
```

### 5. Set Permissions

Set correct permissions for storage and cache:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

Or via cPanel File Manager:
- Right-click `storage` folder → Change Permissions → Set to 775
- Right-click `bootstrap/cache` folder → Change Permissions → Set to 775

### 6. Install Dependencies (if needed)

If `vendor/` folder is not uploaded:
```bash
composer install --optimize-autoloader --no-dev
```

### 7. Run Database Migrations

```bash
php artisan migrate --force
```

### 8. Seed Database (First Time Only)

```bash
php artisan db:seed --force
```

**Important Seeders:**
- `AdminUserSeeder` - Creates admin account
- `CategorySeeder` - Creates product categories
- `HeaderSettingSeeder` - Header configuration
- `FooterSettingSeeder` - Footer configuration
- `HomePageSettingSeeder` - Home page content

### 9. Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

### 10. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 11. Clear All Caches (if needed)

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📋 Post-Deployment Checklist

- [ ] `.env` file configured with production database credentials
- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] Document root points to `public/` folder
- [ ] Storage and cache folders have correct permissions (775)
- [ ] Database migrations completed successfully
- [ ] Database seeded with initial data
- [ ] Storage link created (`php artisan storage:link`)
- [ ] All caches optimized (config, route, view, event)
- [ ] Admin account created (check database or run AdminUserSeeder)
- [ ] Test login functionality
- [ ] Test file uploads (products, images)
- [ ] Test cart and checkout flow
- [ ] Verify all pages load correctly
- [ ] Check mobile responsiveness

---

## 🔐 Default Admin Credentials

After running `AdminUserSeeder`:
- **Email:** admin@example.com
- **Password:** password

**⚠️ IMPORTANT:** Change these credentials immediately after first login!

---

## 🗂️ Important Folders Structure

```
your-project/
├── public/              ← Document root (point domain here)
│   ├── index.php
│   ├── .htaccess
│   ├── build/          ← Compiled assets
│   └── uploads/        ← User uploaded files
├── storage/
│   ├── app/
│   │   └── public/     ← Linked to public/storage
│   ├── framework/
│   └── logs/
├── .env                ← Environment configuration
└── artisan
```

---

## 🔧 Common Issues & Solutions

### Issue: 500 Internal Server Error
**Solution:**
1. Check `.env` file exists and is configured
2. Run `php artisan key:generate`
3. Check storage permissions (775)
4. Check error logs in `storage/logs/laravel.log`

### Issue: Database Connection Error
**Solution:**
1. Verify database credentials in `.env`
2. Ensure database exists on server
3. Check database user has proper permissions
4. Test connection: `php artisan migrate:status`

### Issue: Images Not Loading
**Solution:**
1. Run `php artisan storage:link`
2. Check `public/storage` symlink exists
3. Verify storage folder permissions (775)
4. Check uploaded files are in `storage/app/public/`

### Issue: CSS/JS Not Loading
**Solution:**
1. Ensure `public/build/` folder exists
2. Run `npm run build` locally and upload `public/build/`
3. Check `APP_URL` in `.env` matches your domain
4. Clear browser cache

### Issue: Routes Not Working
**Solution:**
1. Check `.htaccess` file exists in `public/` folder
2. Ensure mod_rewrite is enabled on server
3. Clear route cache: `php artisan route:clear`
4. Recache routes: `php artisan route:cache`

---

## 📱 Mobile Optimization

The project is fully optimized for mobile:
- ✅ Responsive header with touch-friendly navigation
- ✅ Mobile-optimized admin dashboard
- ✅ Touch-scrollable navigation menu
- ✅ Responsive product grids
- ✅ Mobile-friendly forms and buttons

---

## 🔄 Updating the Application

When you need to update:

1. Upload new files
2. Clear caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```
3. Run migrations (if any):
   ```bash
   php artisan migrate --force
   ```
4. Optimize again:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 📞 Support

If you encounter issues:
1. Check `storage/logs/laravel.log` for errors
2. Enable debug mode temporarily: `APP_DEBUG=true` in `.env`
3. Check server error logs (usually in cPanel or `/var/log/apache2/`)

---

## ✅ Production Ready

Your application is now optimized and ready for production deployment!

**Optimizations Applied:**
- ✅ Config cached
- ✅ Routes cached
- ✅ Views cached
- ✅ Events cached
- ✅ Assets minified (CSS: 22.80 KB gzipped)
- ✅ Mobile responsive
- ✅ Touch-optimized navigation
- ✅ Security headers configured
- ✅ Input sanitization enabled
- ✅ Admin middleware protected

**Performance Features:**
- Database query optimization
- Eager loading relationships
- Image optimization
- Asset minification
- Browser caching via .htaccess
- Gzip compression enabled

Good luck with your deployment! 🚀
