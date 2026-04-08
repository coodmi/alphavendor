# 🚀 Quick Deploy Reference

## One-Command Deploy

```bash
# Fresh installation (WARNING: Deletes all data)
php artisan migrate:fresh --seed && php artisan storage:link && php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Safe Update (Preserves existing data)

```bash
# Update without deleting data
php artisan migrate && php artisan db:seed && php artisan cache:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Update Only CMS Content

```bash
# Update all CMS pages
php artisan db:seed --class=HeaderSettingSeeder && php artisan db:seed --class=FooterSettingSeeder && php artisan db:seed --class=HomePageSettingSeeder && php artisan db:seed --class=AboutPageSettingSeeder && php artisan db:seed --class=ContactPageSettingSeeder && php artisan db:seed --class=PrivacyPageSettingSeeder && php artisan db:seed --class=ServicesPageSettingSeeder && php artisan db:seed --class=ShopPageSettingSeeder && php artisan db:seed --class=BooksHeroSeeder && php artisan cache:clear
```

## Individual Seeders

```bash
# Site settings
php artisan db:seed --class=SiteSettingSeeder

# Header
php artisan db:seed --class=HeaderSettingSeeder

# Footer
php artisan db:seed --class=FooterSettingSeeder

# Home page
php artisan db:seed --class=HomePageSettingSeeder

# About page
php artisan db:seed --class=AboutPageSettingSeeder

# Contact page
php artisan db:seed --class=ContactPageSettingSeeder

# Privacy page
php artisan db:seed --class=PrivacyPageSettingSeeder

# Services page
php artisan db:seed --class=ServicesPageSettingSeeder

# Shop settings
php artisan db:seed --class=ShopPageSettingSeeder

# Books hero
php artisan db:seed --class=BooksHeroSeeder

# Payment methods
php artisan db:seed --class=PaymentMethodSeeder

# Global service options
php artisan db:seed --class=GlobalServiceOptionSeeder

# Checkout fields
php artisan db:seed --class=CheckoutFieldSeeder
```

## Cache Commands

```bash
# Clear all caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Rebuild caches (production)
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Clear specific cache
php artisan cache:forget site_setting_site_name
```

## Troubleshooting

```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache

# Re-link storage
php artisan storage:link

# Regenerate autoload
composer dump-autoload

# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Reset database (WARNING: Deletes all data)
php artisan migrate:fresh --seed
```

## Pre-Deployment Checklist

- [ ] Backup database: `mysqldump -u user -p database > backup.sql`
- [ ] Backup uploads: `tar -czf uploads.tar.gz public/uploads`
- [ ] Update .env file
- [ ] Set APP_DEBUG=false
- [ ] Set APP_ENV=production
- [ ] Update APP_URL
- [ ] Configure database credentials
- [ ] Configure mail settings

## Post-Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Seed database: `php artisan db:seed`
- [ ] Link storage: `php artisan storage:link`
- [ ] Clear caches: `php artisan cache:clear`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Test admin login
- [ ] Test user registration
- [ ] Test order flow
- [ ] Check all pages load
- [ ] Verify images display

## Emergency Rollback

```bash
# Restore database
mysql -u user -p database < backup.sql

# Restore uploads
tar -xzf uploads.tar.gz

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Default Credentials

**Admin User** (check AdminUserSeeder for actual credentials):
- Email: admin@example.com
- Password: (check seeder file)

**Test User** (check TestUserSeeder for actual credentials):
- Email: user@gmail.com
- Password: password

⚠️ **Change these immediately after first login!**

## Support Files

- **DATABASE_SEEDING.md** - Complete seeding documentation
- **DEPLOYMENT_GUIDE.md** - Detailed deployment guide
- **SEEDING_COMPLETE.md** - Summary of what was done

---

**Quick Tip**: Bookmark this file for fast reference during deployments!
