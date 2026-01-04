<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RetailController extends Controller
{
    public function index()
    {
        // Retail categories
        $categories = [
            ['name' => 'Electronics', 'count' => 234, 'icon' => 'fa-laptop'],
            ['name' => 'Fashion & Apparel', 'count' => 567, 'icon' => 'fa-tshirt'],
            ['name' => 'Home & Living', 'count' => 321, 'icon' => 'fa-couch'],
            ['name' => 'Sports & Outdoor', 'count' => 198, 'icon' => 'fa-basketball-ball'],
            ['name' => 'Beauty & Personal Care', 'count' => 445, 'icon' => 'fa-spa'],
            ['name' => 'Toys & Games', 'count' => 276, 'icon' => 'fa-gamepad'],
        ];

        // Featured retail products
        $products = [];
        for ($i = 1; $i <= 12; $i++) {
            $products[] = [
                'id' => $i,
                'name' => 'Retail Product ' . $i,
                'price' => rand(15, 150),
                'old_price' => rand(180, 250),
                'rating' => rand(35, 50) / 10,
                'reviews' => rand(20, 300),
                'stock' => rand(5, 100),
                'badge' => $i % 3 == 0 ? 'Hot Deal' : ($i % 5 == 0 ? 'Limited' : null)
            ];
        }

        // Retail stores
        $stores = [
            ['name' => 'Tech Retail Hub', 'rating' => 4.8, 'products' => 156, 'sales' => 2340],
            ['name' => 'Fashion Outlet', 'rating' => 4.9, 'products' => 289, 'sales' => 3890],
            ['name' => 'Home Essentials', 'rating' => 4.7, 'products' => 234, 'sales' => 1920],
            ['name' => 'Sports Arena', 'rating' => 4.6, 'products' => 178, 'sales' => 1450],
        ];

        return view('retail', compact('categories', 'products', 'stores'));
    }
}
