<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        // Sample data for display
        $products = [];
        for ($i = 1; $i <= 24; $i++) {
            $products[] = [
                'id' => $i,
                'name' => 'Product Name ' . $i,
                'price' => rand(20, 200),
                'old_price' => rand(250, 300),
                'rating' => rand(35, 50) / 10,
                'reviews' => rand(50, 500),
                'badge' => $i % 3 == 0 ? 'Sale' : ($i % 5 == 0 ? 'New' : null)
            ];
        }

        return view('shop', compact('products'));
    }
}
