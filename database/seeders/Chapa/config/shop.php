<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration values for the shop functionality including tax rates,
    | shipping options, and other shop-related settings.
    |
    */

    'tax_rate' => env('SHOP_TAX_RATE', 0.08),

    'currency' => env('SHOP_CURRENCY', 'BDT'),

    'currency_symbol' => env('SHOP_CURRENCY_SYMBOL', '৳'),

    'default_order_status' => 'pending',

    'order_statuses' => [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'products_per_page' => 12,

    'featured_products_limit' => 8,
];
