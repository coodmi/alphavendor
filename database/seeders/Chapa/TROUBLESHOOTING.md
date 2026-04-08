# 🔧 Troubleshooting Guide

## Common Deployment Issues & Solutions

---

## Issue 1: PHP Version Error ⚠️

**Error Message:**
```
Composer detected issues in your platform. 
Your Composer dependencies require a PHP version ">= 8.2.0".
```

**Solution:**
1. Login to cPanel
2. Find "Select PHP Version" or "MultiPHP Manager"
3. Change to PHP 8.2 or 8.3
4. Apply and refresh

**Detailed Guide:** See `QUICK_FIX_PHP.md`

---

## Issue 2: 500 Internal Server Error

**Possible Causes:**

### A. Missing .env file
**Solution:**
```bash
# Copy .env.production to .env
cp .env.production .env

# Edit with your database credentials
nano .env

# Generate key
php artisan key:generate
```

### B. Wrong permissions
**Solution:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### C. Missing APP_KEY
**Solution:**
```bash
php artisan key:generate
```

### D. Check error logs
**Location:** `storage/logs/laravel.log`

---

## Issue 3: Database Connection Error

**Error Message:**
```
SQLSTATE[HY000] [1045] Access denied for user
```

**Solution:**

1. **Check .env file:**
```env
DB_CONNECTION=mysql
DB_HOST=localhost        # Try 127.0.0.1 if localhost doesn't work
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_pass
```

2. **Verify database exists:**
   - Login to cPanel → MySQL Databases
   - Check database is created
   - Check user has privileges

3. **Test connection:**
```bash
php artisan migrate:status
```

4. **Clear config cache:**
```bash
php artisan config:clear
php artisan config:cache
```

---

## Issue 4: Blank White Page

**Possible Causes:**

### A. Debug mode off
**Temporary fix (to see error):**
```env
APP_DEBUG=true
```
**Remember to set back to `false` after fixing!**

### B. Missing vendor folder
**Solution:**
```bash
composer install --optimize-autoloader --no-dev
```

### C. Cache issues
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Issue 5: CSS/JS Not Loading

**Symptoms:** Page loads but no styling

**Solutions:**

### A. Check public/build folder exists
```bash
ls -la public/build/
```

If missing, run locally:
```bash
npm run build
```
Then upload `public/build/` folder

### B. Check APP_URL in .env
```env
APP_URL=https://yourdomain.com
```

### C. Clear browser cache
Press: `Ctrl + Shift + Delete` or `Ctrl + F5`

---

## Issue 6: Images Not Loading

**Error:** Uploaded images show 404

**Solutions:**

### A. Create storage link
```bash
php artisan storage:link
```

### B. Check permissions
```bash
chmod -R 775 storage/app/public
```

### C. Verify symlink exists
```bash
ls -la public/storage
```
Should show: `public/storage -> ../storage/app/public`

---

## Issue 7: Routes Not Working

**Error:** 404 on all pages except homepage

**Solutions:**

### A. Check .htaccess exists in public/
```bash
ls -la public/.htaccess
```

### B. Enable mod_rewrite (Apache)
Contact hosting support to enable

### C. Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### D. Check document root
Must point to: `public/` folder

---

## Issue 8: Admin Can't Login

**Solutions:**

### A. Run admin seeder
```bash
php artisan db:seed --class=AdminUserSeeder
```

### B. Create admin manually
```bash
php artisan tinker
```
Then:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = bcrypt('password');
$user->is_admin = true;
$user->save();
```

### C. Check database
```sql
SELECT * FROM users WHERE is_admin = 1;
```

---

## Issue 9: File Upload Fails

**Error:** Can't upload images in admin

**Solutions:**

### A. Check upload limits in .htaccess
```apache
php_value upload_max_filesize 20M
php_value post_max_size 20M
```

### B. Check storage permissions
```bash
chmod -R 775 storage/app/public
```

### C. Check disk space
```bash
df -h
```

---

## Issue 10: Session/Login Issues

**Error:** Can't stay logged in

**Solutions:**

### A. Check session driver in .env
```env
SESSION_DRIVER=database
```

### B. Run session table migration
```bash
php artisan migrate
```

### C. Clear sessions
```bash
php artisan cache:clear
```

### D. Check session table exists
```sql
SHOW TABLES LIKE 'sessions';
```

---

## Quick Diagnostic Commands

Run these to check system status:

```bash
# Check PHP version
php -v

# Check Laravel status
php artisan about

# Check database connection
php artisan migrate:status

# Check permissions
ls -la storage/
ls -la bootstrap/cache/

# Check .env file
cat .env | grep APP_KEY

# View recent errors
tail -50 storage/logs/laravel.log
```

---

## Emergency Debug Mode

If nothing works, enable debug temporarily:

1. Edit `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

2. Clear cache:
```bash
php artisan config:clear
```

3. Visit your site - you'll see detailed error messages

4. **IMPORTANT:** Set back to production after fixing:
```env
APP_DEBUG=false
APP_ENV=production
```

---

## Contact Hosting Support Template

If you need help from hosting:

```
Subject: Need PHP 8.2+ and mod_rewrite enabled

Hello,

I'm deploying a Laravel application and need:

1. PHP 8.2 or higher enabled for: yourdomain.com
2. Apache mod_rewrite module enabled
3. Document root set to: /path/to/project/public

Current issue: [describe your error]

Thank you!
```

---

## Deployment Checklist

Use this to verify everything:

- [ ] PHP 8.2+ enabled
- [ ] Document root points to `public/`
- [ ] `.env` file exists and configured
- [ ] `APP_KEY` generated
- [ ] Database credentials correct
- [ ] Migrations run successfully
- [ ] Database seeded
- [ ] Storage link created
- [ ] Permissions set (775 on storage)
- [ ] Caches optimized
- [ ] `.htaccess` exists in public/
- [ ] `public/build/` folder exists
- [ ] Can login to admin panel
- [ ] Images upload successfully
- [ ] Mobile view works

---

## Still Stuck?

1. **Check logs:** `storage/logs/laravel.log`
2. **Enable debug:** `APP_DEBUG=true` (temporarily)
3. **Google the error:** Copy exact error message
4. **Contact hosting:** They can check server logs
5. **Check Laravel docs:** https://laravel.com/docs

---

## Most Common Issues (90% of problems)

1. ✅ **PHP version too old** → Change to 8.2+
2. ✅ **Missing .env file** → Create from .env.production
3. ✅ **Wrong permissions** → chmod 775 storage
4. ✅ **No APP_KEY** → php artisan key:generate
5. ✅ **Wrong document root** → Point to public/

**Fix these first!**

---

**Good luck! 🚀**
