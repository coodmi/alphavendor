# Fix for Total Amount Database Error

## Problem
After uploading images or accessing the admin panel, you're getting a 500 server error with the message:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'total_amount' in 'SELECT'
```

## Root Cause
The code was trying to use a `total_amount` column that doesn't exist in the database. The actual column is named `total`.

## Files Fixed
1. `app/Http/Controllers/AdminController.php` - Line 20: Changed `sum('total_amount')` to `sum('total')`
2. `app/Http/Controllers/ServicesController.php` - Line 141: Changed `'total_amount'` to `'total'` and added required fields

## Deployment Steps

### Step 1: Upload Fixed Files
Upload the following fixed files to your production server:
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/ServicesController.php`

### Step 2: Run the Fix Script
1. Upload `fix_total_amount_issue.php` to your server root
2. Run: `php fix_total_amount_issue.php`

### Step 3: Manual Commands (Alternative)
If you prefer to run commands manually:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Test
1. Try accessing `/admin` page
2. Try uploading a footer image
3. Check if the 500 error is resolved

## Additional Notes
- The Order model already has a `getTotalAmountAttribute()` accessor, so `$order->total_amount` will still work in views
- Database queries must use the actual column name `total`, not the accessor `total_amount`
- All required fields are now properly set when creating orders

## Verification
After deployment, check the Laravel logs at `storage/logs/laravel.log` to ensure no more database errors occur.