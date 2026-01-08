<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of sellers (retailers, wholesalers, exporters)
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('status', 'active');

        // Filter by seller type
        if ($request->has('type') && $request->type !== '') {
            $query->where('role', $request->type);
        }

        // Search by name
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('roleApplications', function($subQuery) use ($request) {
                      $subQuery->where('business_name', 'like', '%' . $request->search . '%')
                               ->where('status', 'approved');
                  });
            });
        }

        // Paginate results with products count and orders
        $sellers = $query->with(['roleApplications' => function($q) {
            $q->where('status', 'approved')->latest();
        }])
        ->withCount('products')
        ->withCount(['orders as total_sales'])
        ->paginate(12);

        return view('sellers.index', compact('sellers'));
    }

    /**
     * Display seller's products
     */
    public function products(Request $request, $sellerId)
    {
        $seller = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('status', 'active')
            ->findOrFail($sellerId);

        $query = \App\Models\Product::where('vendor_id', $sellerId);

        // Search filter
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== '') {
            if ($seller->role === 'retailer') {
                $query->where('retail_price', '>=', $request->min_price);
            } elseif ($seller->role === 'wholesaler') {
                $query->where('wholesale_price', '>=', $request->min_price);
            } elseif ($seller->role === 'exporter') {
                $query->where('export_price', '>=', $request->min_price);
            }
        }

        if ($request->has('max_price') && $request->max_price !== '') {
            if ($seller->role === 'retailer') {
                $query->where('retail_price', '<=', $request->max_price);
            } elseif ($seller->role === 'wholesaler') {
                $query->where('wholesale_price', '<=', $request->max_price);
            } elseif ($seller->role === 'exporter') {
                $query->where('export_price', '<=', $request->max_price);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $priceColumn = $seller->role === 'retailer' ? 'retail_price' :
                              ($seller->role === 'wholesaler' ? 'wholesale_price' : 'export_price');
                $query->orderBy($priceColumn, 'asc');
                break;
            case 'price_high':
                $priceColumn = $seller->role === 'retailer' ? 'retail_price' :
                              ($seller->role === 'wholesaler' ? 'wholesale_price' : 'export_price');
                $query->orderBy($priceColumn, 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = \App\Models\Category::all();

        // Get seller's business information
        $businessInfo = $seller->roleApplications()
            ->where('status', 'approved')
            ->latest()
            ->first();

        return view('sellers.products', compact('seller', 'products', 'categories', 'businessInfo'));
    }
}
