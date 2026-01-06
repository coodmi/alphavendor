<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

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

        return view('home', compact('categories', 'banners'));
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
