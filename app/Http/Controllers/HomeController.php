<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $categories = [
            ['name' => 'Fashion', 'image' => 'fashion.jpg'],
            ['name' => 'Home Living', 'image' => 'home-living.jpg'],
            ['name' => 'Appliances', 'image' => 'appliances.jpg'],
            ['name' => 'Beauty Health', 'image' => 'beauty.jpg'],
            ['name' => 'Automotive', 'image' => 'automotive.jpg'],
            ['name' => 'Jewelry Watches', 'image' => 'jewelry.jpg'],
        ];

        // Fetch active banners ordered by sort_order
        $banners = Banner::active()->ordered()->get();

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

        return view('home', compact(
            'categories',
            'banners',
            'retailerProducts',
            'wholesalerProducts',
            'exporterProducts'
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
