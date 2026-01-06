<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;

class WholesaleController extends Controller
{
    public function index(Request $request)
    {
        // Get all wholesalers
        $wholesalers = User::where('role', 'wholesaler')
            ->where('status', 'active')
            ->get();

        // Build query for wholesale products
        $query = Product::with(['category', 'brand', 'vendor'])
            ->whereHas('vendor', function($q) {
                $q->where('role', 'wholesaler')
                  ->where('status', 'active');
            })
            ->where('status', 'active');

        // Filter by minimum order if provided
        if ($request->has('minimum_order') && $request->minimum_order) {
            $minOrder = $request->minimum_order;
            if ($minOrder === '10-50') {
                $query->whereBetween('minimum_order', [10, 50]);
            } elseif ($minOrder === '50-100') {
                $query->whereBetween('minimum_order', [50, 100]);
            } elseif ($minOrder === '100-500') {
                $query->whereBetween('minimum_order', [100, 500]);
            } elseif ($minOrder === '500+') {
                $query->where('minimum_order', '>=', 500);
            }
        }

        // Filter by supplier location if provided
        if ($request->has('supplier_location') && $request->supplier_location) {
            $query->where('supplier_location', $request->supplier_location);
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand if provided
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Get products
        $products = $query->latest()->paginate(12);

        // Get unique supplier locations from products
        $supplierLocations = Product::whereHas('vendor', function($q) {
                $q->where('role', 'wholesaler')->where('status', 'active');
            })
            ->where('status', 'active')
            ->whereNotNull('supplier_location')
            ->distinct()
            ->pluck('supplier_location')
            ->sort()
            ->values();

        // Get minimum order ranges
        $minOrderRanges = [
            '10-50' => '10-50 Units',
            '50-100' => '50-100 Units',
            '100-500' => '100-500 Units',
            '500+' => '500+ Units',
        ];

        // Get wholesale categories
        $categories = Category::whereHas('vendor', function($q) {
                $q->where('role', 'wholesaler');
            })
            ->where('is_active', true)
            ->has('products')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        // Get wholesale brands
        $brands = Brand::whereHas('vendor', function($q) {
                $q->where('role', 'wholesaler');
            })
            ->where('is_active', true)
            ->has('products')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('wholesale', compact(
            'products',
            'supplierLocations',
            'minOrderRanges',
            'categories',
            'brands'
        ));
    }
}
