<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\PromoBanner;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch active global categories (same as Shop page)
        $categories = Category::whereNull('vendor_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch active banners ordered by sort_order
        $banners = Banner::active()->ordered()->get();

        // Fetch active promo banners ordered by sort_order
        $promoBanners = PromoBanner::active()->ordered()->get();

        // Get Today's Deals - Featured products or products with discount
        $todayDeals = Product::where('status', 'active')
            ->where(function($query) {
                $query->where('is_featured', true)
                    ->orWhereColumn('old_price', '>', 'price');
            })
            ->orderBy('created_at', 'desc')
            ->take(8)
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
        $wholesalerProducts = Product::whereIn('vendor_id', $wholesalerIds)
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
            }])
            ->withCount('products')
            ->withCount(['orders as total_sales' => function($query) {
                $query->where('orders.status', 'completed');
            }])
            ->orderBy('total_sales', 'desc')
            ->take(4)
            ->get();

        return view('home', compact(
            'categories',
            'banners',
            'promoBanners',
            'todayDeals',
            'retailerProducts',
            'wholesalerProducts',
            'exporterProducts',
            'featuredVendors'
        ));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
