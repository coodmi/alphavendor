<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $currencyHelper = app_path('Helpers/currency.php');
        if (file_exists($currencyHelper)) {
            require_once $currencyHelper;
        }
        // ...existing code...
    }
}
