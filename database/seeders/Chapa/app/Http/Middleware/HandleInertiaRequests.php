<?php

namespace App\Http\Middleware;

use App\Models\PageSection;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Get site logo from header section - always fetch fresh
        $headerSection = PageSection::where('page', 'header')
            ->where('section_key', 'main')
            ->where('is_active', true)
            ->first();

        $siteLogo = $headerSection?->content['logo'] ?? '/logo.png';

        // Get site name and favicon from SiteSetting (if table exists), with fallbacks
        // Always fetch fresh from database to avoid cache issues
        try {
            // Clear cache before fetching to ensure fresh data
            \Illuminate\Support\Facades\Cache::forget('site_setting_site_name');
            \Illuminate\Support\Facades\Cache::forget('site_setting_favicon');
            
            $siteName = SiteSetting::where('key', 'site_name')->value('value') ?? $headerSection?->content['site_name'] ?? config('site.name', 'Chapakhana');
            $favicon = SiteSetting::where('key', 'favicon')->value('value') ?? '/favicon.ico';
            // Add cache-busting timestamp
            $faviconWithVersion = $favicon . '?v=' . time();
        } catch (\Exception $e) {
            // Fallback if table doesn't exist yet
            $siteName = $headerSection?->content['site_name'] ?? config('site.name', 'Chapakhana');
            $favicon = '/favicon.ico';
            $faviconWithVersion = $favicon . '?v=' . time();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'csrf_token' => csrf_token(),
            'site' => [
                'logo' => $siteLogo,
                'name' => $siteName,
                'favicon' => $faviconWithVersion,
                // Add timestamp to force refresh when settings change
                'updated_at' => time(),
            ],
        ];
    }
}
