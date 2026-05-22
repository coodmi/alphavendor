<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\PromoBanner;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch active hero slides
        $heroSlides = \App\Models\HeroSlide::active()->ordered()->get();

        // Fetch active global categories (same as Shop page)
        $categories = Category::whereNull('vendor_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch active admin brands (no vendor_id)
        $brands = Brand::whereNull('vendor_id')
            ->where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Fetch active banners ordered by sort_order with special offer relationship
        $banners = Banner::with('specialOffer')->active()->ordered()->get();

        // Fetch active promo banners ordered by sort_order
        $promoBanners = PromoBanner::active()->ordered()->get();

        // Fetch active special offers for the Special Offers section
        $specialOffers = \App\Models\SpecialOffer::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->take(4)
            ->get();

        // Get Best Products - Top rated and featured products (limit to 4 for one row)
        $bestProducts = Product::where('status', 'active')
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();

        // Get retailer vendor IDs
        $retailerIds = User::where('role', 'retailer')->pluck('id');
        $retailerProducts = Product::whereIn('vendor_id', $retailerIds)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Get wholesaler vendor IDs
        $wholesalerIds = User::where('role', 'wholesaler')->pluck('id');
        $wholesalerProducts = Product::with('supplierLocation')
            ->whereIn('vendor_id', $wholesalerIds)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Get exporter vendor IDs
        $exporterIds = User::where('role', 'exporter')->pluck('id');
        $exporterProducts = Product::whereIn('vendor_id', $exporterIds)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Get top featured vendors (sellers with most sales)
        $featuredVendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('status', 'active')
            ->with(['roleApplications' => function($q) {
                $q->where('status', 'approved')->latest();
            }, 'vendorBadge'])
            ->withCount('products')
            ->withCount(['orders as total_sales' => function($query) {
                $query->where('orders.status', 'completed');
            }])
            ->orderBy('total_sales', 'desc')
            ->take(4)
            ->get();

        return view('home', compact(
            'heroSlides',
            'categories',
            'brands',
            'banners',
            'promoBanners',
            'specialOffers',
            'bestProducts',
            'retailerProducts',
            'wholesalerProducts',
            'exporterProducts',
            'featuredVendors'
        ));
    }

    public function about()
    {
        $content = \App\Models\AboutPageContent::getContent()->toArray();
        return view('about', compact('content'));
    }

    public function contact()
    {
        $content = \App\Models\ContactPageContent::getContent()->toArray();
        return view('contact', compact('content'));
    }

    public function shippingInfo()
    {
        $page = \App\Models\PageContent::getByType('shipping');
        return view('shipping-info', compact('page'));
    }

    public function terms()
    {
        $page = \App\Models\PageContent::getByType('terms');
        return view('terms', compact('page'));
    }

    public function privacy()
    {
        $page = \App\Models\PageContent::getByType('privacy');
        return view('privacy', compact('page'));
    }

    public function allOffers()
    {
        $specialOffers = \App\Models\SpecialOffer::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('special-offers.index', compact('specialOffers'));
    }
}
