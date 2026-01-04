<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('home', compact('categories'));
    }
}
