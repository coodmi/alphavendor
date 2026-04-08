# Chapakhana

Chapakhana is a product of Nex Group and Developed by Alphainno.

```bash
npm run build && "/c/Program Files/7-Zip/7z.exe" a -tzip ../chapakhana-updated-4.zip . '-xr!node_modules' '-xr!.git' -mx=1
```

### Cpanel Deploy

```bash
rm -f database/migrations/2026_01_13_100000_add_performance_indexes_to_products_table.php
rm -f database/migrations/2026_01_13_100001_add_performance_indexes_to_orders_table.php
rm -f database/migrations/2026_01_13_100002_add_performance_indexes_to_order_items_table.php
rm -f database/migrations/2026_01_13_100003_add_performance_indexes_to_categories_table.php

rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/*.php

php artisan key:generate && php artisan migrate:fresh --seed && rm -rf public/storage && php artisan storage:link
```
