# Production Deployment Checklist

## ✅ Completed Optimizations

### 1. Cache Optimization
- ✅ Views cached (`php artisan view:cache`)
- ✅ Routes cached (`php artisan route:cache`)
- ✅ Config cached (`php artisan config:cache`)
- ✅ Events cached (`php artisan event:cache`)
- ✅ Assets built for production (`npm run build`)

## 🔧 Manual Steps Required

### 2. Composer Optimization
Run this command on your server:
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Environment Configuration

Update your `.env` file for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 4. Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Generate new `APP_KEY` if not set: `php artisan key:generate`
- [ ] Set proper file permissions:
  ```bash
  chmod -R 755 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```
- [ ] Configure HTTPS/SSL certificate
- [ ] Set up firewall rules
- [ ] Enable CSRF protection (already enabled)
- [ ] Configure rate limiting (already configured)

### 5. Database Optimization

```bash
# Run migrations
php artisan migrate --force

# Seed initial data if needed
php artisan db:seed --class=DatabaseSeeder
```

### 6. Web Server Configuration

#### Apache (.htaccess already configured)
- Ensure `mod_rewrite` is enabled
- Point document root to `/public` directory

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. Performance Optimization

```bash
# Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.revalidate_freq=0

# Install Redis for caching
sudo apt-get install redis-server
```

### 8. Queue Workers (Optional but Recommended)

```bash
# Install supervisor
sudo apt-get install supervisor

# Create worker config: /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600

# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 9. Scheduled Tasks (Cron)

Add to crontab:
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 10. Monitoring & Logging

```bash
# Set up log rotation
sudo nano /etc/logrotate.d/laravel

/path/to/your/project/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 11. Backup Strategy

- [ ] Set up automated database backups
- [ ] Back up uploaded files in `storage/app/public`
- [ ] Back up `.env` file securely
- [ ] Test restore procedures

### 12. Post-Deployment Commands

Run these after deploying:
```bash
# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan optimize

# Link storage
php artisan storage:link

# Restart queue workers
php artisan queue:restart
```

## 📊 Performance Checklist

- ✅ Assets minified and compressed
- ✅ Images optimized
- ✅ Database indexes added
- ✅ Eager loading implemented
- ✅ Query optimization done
- ✅ Caching strategy implemented
- [ ] CDN configured (optional)
- [ ] Redis/Memcached installed
- [ ] HTTP/2 enabled
- [ ] Gzip compression enabled

## 🔍 Testing Checklist

Before going live:
- [ ] Test all major user flows
- [ ] Test admin panel functionality
- [ ] Test payment processing
- [ ] Test email notifications
- [ ] Test file uploads
- [ ] Test on mobile devices
- [ ] Run security scan
- [ ] Check SSL certificate
- [ ] Test error pages (404, 500)
- [ ] Verify analytics tracking

## 🚀 Deployment Commands Summary

```bash
# On your server, run these in order:

# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Run migrations
php artisan migrate --force

# 4. Clear and rebuild caches
php artisan optimize:clear
php artisan optimize

# 5. Link storage
php artisan storage:link

# 6. Restart services
php artisan queue:restart
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

## 📝 Notes

- All Laravel caches have been optimized
- Assets are built and minified
- Database has proper indexes
- Security middleware is configured
- Rate limiting is enabled
- CSRF protection is active
- Input sanitization is implemented

## 🆘 Troubleshooting

If you encounter issues:

1. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   ```

2. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Check permissions:**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

4. **Rebuild caches:**
   ```bash
   php artisan optimize
   ```

## 🔗 Useful Links

- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Laravel Forge](https://forge.laravel.com) - Automated deployment
- [Laravel Vapor](https://vapor.laravel.com) - Serverless deployment

---

**Your application is now optimized and ready for production deployment!**
