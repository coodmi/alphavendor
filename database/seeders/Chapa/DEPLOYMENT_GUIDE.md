# Chapakhana - Deployment Guide

## Quick Deployment Steps

### 1. Fresh Installation (New Server)

```bash
# Clone repository
git clone <repository-url>
cd chapakhana

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Update .env with your database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=chapakhana
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seed all data
php artisan migrate:fresh --seed

# Link storage
php artisan storage:link

# Build assets
npm run build

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### 2. Update Existing Installation

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm install

# Run new migrations
php artisan migrate

# Seed new/updated content (safe to run multiple times)
php artisan db:seed

# Rebuild assets
npm run build

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Seeding Only CMS Content

If you only want to update CMS content without affecting products/orders:

```bash
# Update site settings
php artisan db:seed --class=SiteSettingSeeder

# Update header
php artisan db:seed --class=HeaderSettingSeeder

# Update footer
php artisan db:seed --class=FooterSettingSeeder

# Update home page
php artisan db:seed --class=HomePageSettingSeeder

# Update about page
php artisan db:seed --class=AboutPageSettingSeeder

# Update contact page
php artisan db:seed --class=ContactPageSettingSeeder

# Update privacy page
php artisan db:seed --class=PrivacyPageSettingSeeder

# Update services page
php artisan db:seed --class=ServicesPageSettingSeeder

# Update shop hero
php artisan db:seed --class=ShopHeroSectionSeeder

# Update shop page settings
php artisan db:seed --class=ShopPageSettingSeeder

# Update books hero
php artisan db:seed --class=BooksHeroSeeder

# Clear cache
php artisan cache:clear
```

## What Gets Seeded

### ✅ User Accounts
- Admin user (default credentials in AdminUserSeeder)
- Test user (default credentials in TestUserSeeder)

### ✅ Shop Data
- Categories (Business Cards, Brochures, Books, etc.)
- Product formats (sizes, paper types, binding options)
- Products with pricing and specifications
- Shop-only products

### ✅ Service Data
- Service categories (Magazines, Books, Catalogs, etc.)
- Service products with configuration options

### ✅ E-commerce Configuration
- Payment methods (bKash, Nagad, Rocket, Bank Transfer, COD)
- Checkout form fields
- Global service options (Design packages, Quantity, Delivery, Courier)

### ✅ Site Settings
- Site information (name, tagline, description)
- Contact details (email, phone, WhatsApp, address)
- Social media links
- Business settings (currency, shipping rates)
- SEO settings
- Feature toggles

### ✅ CMS Content
- **Header**: Navigation, logo, search, cart
- **Footer**: Company info, links, social media, newsletter
- **Home Page**: Hero slider, testimonials, features, stats
- **About Page**: Company story, mission, vision, team
- **Contact Page**: Contact form, map, information
- **Privacy Page**: Privacy policy content
- **Services Page**: Service listings, features
- **Shop Page**: Shop configuration, filters
- **Books Hero**: Books category hero section

## Environment Configuration

### Required Environment Variables

```env
# Application
APP_NAME="চাপাখানা"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chapakhana
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail (for contact forms, order notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="info@chapakhana.com"
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=public

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## Server Requirements

- PHP >= 8.1
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer
- Node.js >= 16.x
- NPM or Yarn

### PHP Extensions Required
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD or Imagick (for image processing)

## Post-Deployment Checklist

- [ ] Database migrated and seeded
- [ ] Storage linked (`php artisan storage:link`)
- [ ] Assets built (`npm run build`)
- [ ] Caches cleared and rebuilt
- [ ] File permissions set correctly (775 for storage and bootstrap/cache)
- [ ] Environment variables configured
- [ ] Admin login working
- [ ] Test order flow
- [ ] Check all pages load correctly
- [ ] Verify images display properly
- [ ] Test contact form
- [ ] Test payment methods
- [ ] Check mobile responsiveness

## Troubleshooting

### Storage Issues
```bash
# Re-link storage
php artisan storage:link

# Fix permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Issues
```bash
# Reset database (WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Run only new migrations
php artisan migrate

# Check migration status
php artisan migrate:status
```

### Asset Issues
```bash
# Rebuild assets
npm run build

# Clear browser cache
# Or use hard refresh (Ctrl+Shift+R)
```

## Backup Before Deployment

Always backup before deploying:

```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup uploads
tar -czf uploads_backup_$(date +%Y%m%d_%H%M%S).tar.gz public/uploads

# Backup .env
cp .env .env.backup
```

## Rollback Plan

If deployment fails:

```bash
# Restore database
mysql -u username -p database_name < backup_YYYYMMDD_HHMMSS.sql

# Restore uploads
tar -xzf uploads_backup_YYYYMMDD_HHMMSS.tar.gz

# Restore .env
cp .env.backup .env

# Clear caches
php artisan cache:clear
php artisan config:clear
```

## Production Optimization

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize for production
php artisan optimize
```

## Security Checklist

- [ ] APP_DEBUG=false in production
- [ ] Strong APP_KEY generated
- [ ] Database credentials secured
- [ ] File permissions set correctly (not 777)
- [ ] HTTPS enabled
- [ ] CSRF protection enabled
- [ ] SQL injection protection (using Eloquent ORM)
- [ ] XSS protection (using Blade templating)
- [ ] Regular backups scheduled

## Monitoring

After deployment, monitor:
- Application logs: `storage/logs/laravel.log`
- Server logs: `/var/log/apache2/` or `/var/log/nginx/`
- Database performance
- Disk space usage
- Memory usage

## Support

For issues or questions:
- Check documentation in `/docs` folder
- Review `DATABASE_SEEDING.md` for seeding details
- Check Laravel logs in `storage/logs/`
- Contact development team

---

**Last Updated**: January 22, 2026
**Version**: 1.0.0
