<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class VendorPortal
{
    public static function routePrefix(): string
    {
        $role = Auth::user()?->role;

        return $role === 'importer' ? 'importer' : 'exporter';
    }

    public static function isSeller(): bool
    {
        return in_array(Auth::user()?->role, ['exporter', 'importer'], true);
    }

    public static function dashboardRoute(): string
    {
        return self::routePrefix() . '.dashboard';
    }

    public static function reportsRoute(): string
    {
        $prefix = self::routePrefix();

        return $prefix . '.reports.index';
    }
}
