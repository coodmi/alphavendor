<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share site settings with all views
        View::composer('*', function ($view) {
            $siteSettings = SiteSetting::getSettings();
            $view->with('siteSettings', $siteSettings);
        });
    }
}
