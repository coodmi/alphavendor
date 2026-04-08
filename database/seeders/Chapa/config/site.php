<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration values are used throughout the application for
    | site-wide settings and content.
    |
    */

    'name' => env('SITE_NAME', 'chapakhana'),
    'tagline' => 'Every page tells your story',
    'phone' => env('SITE_PHONE', '(844) 938-6754'),
    'email' => env('SITE_EMAIL', 'info@chapakhana.com'),

    'social' => [
        'facebook' => env('SOCIAL_FACEBOOK', ''),
        'twitter' => env('SOCIAL_TWITTER', ''),
        'instagram' => env('SOCIAL_INSTAGRAM', ''),
        'linkedin' => env('SOCIAL_LINKEDIN', ''),
    ],

    'meta' => [
        'description' => 'Quality print and marketing materials delivered with care.',
        'keywords' => 'printing, marketing materials, business cards, books, magazines',
        'author' => 'Chapakhana',
    ],

    'business' => [
        'hours' => 'Mon-Fri: 9AM-6PM',
        'timezone' => 'Asia/Dhaka',
        'currency' => 'BDT',
        'currency_symbol' => '৳',
    ],
];
