<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Policies\CategoryPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Application Service Provider.
 *
 * This provider is responsible for bootstrapping application services,
 * registering repository bindings, and configuring view composers.
 *
 * Repository Pattern Usage:
 * -------------------------
 * Repositories are bound to their interfaces in the service container,
 * enabling dependency injection throughout the application.
 *
 * Example usage in a controller:
 * ```php
 * public function __construct(
 *     private ProductRepositoryInterface $productRepository
 * ) {}
 *
 * public function show(int $id): Response
 * {
 *     $product = $this->productRepository->find($id);
 *     return view('products.show', compact('product'));
 * }
 * ```
 *
 * Example usage in a service:
 * ```php
 * public function __construct(
 *     private ProductRepositoryInterface $products,
 *     private CategoryRepositoryInterface $categories
 * ) {}
 * ```
 *
 * @see config/repositories.php for repository configuration
 * @see App\Repositories\Contracts for repository interfaces
 * @see App\Repositories\Eloquent for repository implementations
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Binds repository interfaces to their Eloquent implementations
     * based on the configuration in config/repositories.php.
     * This enables dependency injection of repositories throughout the application.
     */
    public function register(): void
    {
        $this->registerRepositories();
    }

    /**
     * Register repository bindings from configuration.
     *
     * Reads the repository bindings from config/repositories.php and
     * registers each interface to its concrete implementation in the
     * service container.
     *
     * Benefits of this approach:
     * - Centralized configuration for all repository bindings
     * - Easy to swap implementations (e.g., for testing)
     * - Supports caching and other repository features via config
     */
    protected function registerRepositories(): void
    {
        $bindings = config('repositories.bindings', []);

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);

        // Share service categories with admin sidebar
        View::composer('admin.layouts.sidebar', function ($view) {
            $serviceCategories = ServiceCategory::where('is_active', true)
                ->withCount('products')
                ->orderBy('name')
                ->get();
            $view->with('serviceCategories', $serviceCategories);
        });

        // Share header content with all views that use header partial
        View::composer('partials.header', function ($view) {
            // First try to get from HeaderSetting model
            $headerSetting = \App\Models\HeaderSetting::getActive();
            
            if ($headerSetting) {
                $headerContent = $headerSetting->getFormattedData();
            } else {
                // Fallback to PageSection for backward compatibility
                $headerSection = PageSection::where('page', 'header')
                    ->where('section_key', 'main')
                    ->where('is_active', true)
                    ->first();

                $defaults = [
                    'logo' => '/logo.png',
                    'site_name' => config('site.name', 'Chapakhana'),
                    'phone' => config('site.phone', '+880 1XXX-XXXXXX'),
                    'search_placeholder' => 'What are you looking for?',
                    'help_url' => '#',
                    'login_url' => '/login',
                    'register_url' => '/register',
                    'cart_url' => '/cart',
                    'show_search' => true,
                    'show_phone' => true,
                    'show_help' => true,
                    'navigation' => [
                        ['title' => 'Home', 'url' => '/', 'pattern' => '/'],
                        ['title' => 'Shop', 'url' => '/shop', 'pattern' => 'shop'],
                        ['title' => 'Services', 'url' => '/services', 'pattern' => 'services'],
                        ['title' => 'Magazines, Books & Catalogs', 'url' => '/services/magazines-books-catalogs', 'pattern' => 'services/magazines-books-catalogs*'],
                        ['title' => 'Marketing Material', 'url' => '/services/marketing-material', 'pattern' => 'services/marketing-material*'],
                        ['title' => 'Business Cards', 'url' => '/services/business-cards', 'pattern' => 'services/business-cards*'],
                        ['title' => 'Invitation & Stationery', 'url' => '/services/postcards-invitations', 'pattern' => 'services/postcards-invitations*'],
                        ['title' => 'Banners', 'url' => '/services/banners', 'pattern' => 'services/banners*'],
                        ['title' => 'Stationery', 'url' => '/services/stationery', 'pattern' => 'services/stationery*'],
                    ],
                ];

                // Merge saved content with defaults (saved content takes precedence)
                $headerContent = $headerSection ? array_merge($defaults, $headerSection->content) : $defaults;
            }

            $view->with('headerContent', $headerContent);
        });

        // Share footer content with all views that use footer partial
        View::composer('partials.footer', function ($view) {
            // First try to get from FooterSetting model
            $footerSetting = \App\Models\FooterSetting::getActive();
            
            if ($footerSetting) {
                $footerContent = $footerSetting->getFormattedData();
            } else {
                // Fallback to PageSection for backward compatibility
                $footerSection = PageSection::where('page', 'footer')
                    ->where('section_key', 'main')
                    ->where('is_active', true)
                    ->first();

                // Get service categories - limit to 5 for footer
                $serviceCategories = ServiceCategory::where('is_active', true)
                    ->orderBy('name')
                    ->limit(5)
                    ->get()
                    ->map(function ($category) {
                        return [
                            'title' => $category->name,
                            'url' => '/services/' . $category->slug,
                        ];
                    })
                    ->toArray();

                $footerContent = $footerSection ? $footerSection->content : [
                    'logo' => '/logo.png',
                    'company_info' => [
                        'name' => config('site.name', 'Chapakhana'),
                        'description' => config('site.meta.description', 'Quality print and marketing materials delivered with care.'),
                    ],
                    'contact' => [
                        'address' => 'ঢাকা, বাংলাদেশ',
                        'phone' => config('site.phone', '+880 1XXX-XXXXXX'),
                        'email' => config('site.email', 'info@chapakhana.com'),
                    ],
                    'social_links' => [],
                    'quick_links' => [
                        ['title' => 'About', 'url' => '/about'],
                        ['title' => 'Services', 'url' => '/services'],
                        ['title' => 'Contact', 'url' => '/contact'],
                        ['title' => 'Privacy & Policy', 'url' => '/privacy'],
                        ['title' => 'Terms & Condition', 'url' => '/terms'],
                    ],
                    'service_links' => $serviceCategories,
                    'copyright' => '© '.date('Y').' chapakhana. All rights reserved.',
                ];

                // Add service categories to footer content if not present
                if (!isset($footerContent['service_links'])) {
                    $footerContent['service_links'] = $serviceCategories;
                }
            }

            $view->with('footerContent', $footerContent);
        });

        // Share home page content with home page view
        View::composer('pages.home', function ($view) {
            // First try to get from HomePageSetting model
            $homePageSetting = \App\Models\HomePageSetting::getActive();
            
            if ($homePageSetting) {
                $homeContent = $homePageSetting->getFormattedData();
            } else {
                // Fallback to PageSection for backward compatibility
                $homeSections = PageSection::where('page', 'home')
                    ->where('is_active', true)
                    ->get()
                    ->keyBy('section_key');

                $homeContent = [
                    'hero_slider' => $homeSections->get('hero_slider')?->content ?? [
                        'slides' => [],
                        'stats' => [
                            'percentage' => '93',
                            'label' => 'of our customers would buy again',
                            'reviews_count' => '256,839',
                        ],
                    ],
                    'headline' => $homeSections->get('headline')?->content ?? [
                        'title' => 'আপনার স্বপ্নকে বাস্তবে রূপান্তরিত করুন',
                        'description' => 'চাপাখানা হলো আপনার বিশ্বস্ত প্রিন্টিং সঙ্গী। উচ্চমানের প্রিন্টিং সেবা, দ্রুত ডেলিভারি এবং প্রতিযোগিতামূলক মূল্যে আমরা আপনার ব্যবসায়িক লক্ষ্য অর্জনে সহায়তা করি।',
                    ],
                    'how_to_order' => $homeSections->get('how_to_order')?->content ?? [
                        'title' => '০ টাকা বিনিয়োগে শুরু করুন',
                        'steps' => [
                            ['number' => '১', 'title' => 'পণ্য নির্বাচন করুন', 'description' => '১০০০+ উচ্চমানের পণ্য থেকে আপনার পছন্দের পণ্য বেছে নিন'],
                            ['number' => '২', 'title' => 'ডিজাইন যুক্ত করুন', 'description' => 'সহজ এবং মজাদার উপায়ে আপনার পণ্যের ডিজাইন করুন!'],
                            ['number' => '৩', 'title' => 'বিক্রয় শুরু করুন', 'description' => 'আপনি লাভের মার্জিন নির্ধারণ করুন, উৎপাদন ও ডেলিভারি আমরা করবো'],
                        ],
                        'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                        'video_poster' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=1200&h=1200&fit=crop',
                    ],
                    'best_sellers' => $homeSections->get('best_sellers')?->content ?? [
                        'title' => 'জনপ্রিয় পণ্য',
                        'products' => [],
                    ],
                    'testimonials' => $homeSections->get('testimonials')?->content ?? [
                        'title' => 'গ্রাহকদের মতামত',
                        'subtitle' => 'সারা বাংলাদেশ জুড়ে হাজারো ব্যবসায়ীর বিশ্বস্ত সঙ্গী',
                        'items' => [],
                    ],
                    'offer_banner' => $homeSections->get('offer_banner')?->content ?? [
                        'title' => 'বিশেষ অফার!',
                        'subtitle' => 'প্রথম অর্ডারে পাচ্ছেন ২০% ছাড়',
                        'description' => 'নতুন গ্রাহকরা সকল প্রিন্টিং সার্ভিসে বিশেষ ছাড় উপভোগ করতে পারবেন। সীমিত সময়ের অফার!',
                        'cta_text' => 'এখনই অফার নিন',
                        'cta_url' => '/shop',
                    ],
                    'trust_section' => $homeSections->get('trust_section')?->content ?? [
                        'title' => 'যারা আমাদের বিশ্বাস করেন',
                        'subtitle' => 'শত শত প্রতিষ্ঠান তাদের প্রিন্টিং এর জন্য আমাদের উপর আস্থা রাখেন',
                        'brands' => [],
                    ],
                ];
            }

            $view->with('homeContent', $homeContent);
        });
    }
}
