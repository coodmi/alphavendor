<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\RetailPageContent;
use Illuminate\Http\Request;

class RetailController extends Controller
{
    public function index(Request $request)
    {
        // Get dynamic content from database
        $content = RetailPageContent::getAllContent();

        // Get products with filters (same logic as shop page)
        $query = Product::with(['category', 'vendor', 'brand'])
            ->where('status', 'active')
            ->whereHas('vendor', function($q) {
                $q->where('role', 'retailer');
            });

        // Category filter
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Brand filter
        if ($request->has('brands') && !empty($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Rating filter
        if ($request->has('min_rating') && $request->min_rating !== null) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        switch ($request->get('sort', 'default')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('reviews_count', 'desc');
                break;
            default:
                $query->latest();
        }

        // Paginate
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Get categories with product counts (only for retailer products)
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('status', 'active')
                  ->whereHas('vendor', function($query) {
                      $query->where('role', 'retailer');
                  });
            }])
            ->having('products_count', '>', 0)
            ->get();

        // Get brands with product counts (only for retailer products)
        $brands = Brand::withCount(['products' => function($q) {
                $q->where('status', 'active')
                  ->whereHas('vendor', function($query) {
                      $query->where('role', 'retailer');
                  });
            }])
            ->having('products_count', '>', 0)
            ->get();

        return view('retail', compact('content', 'products', 'categories', 'brands'));
    }
}
