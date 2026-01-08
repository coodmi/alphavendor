<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Get products with filters
        $query = Product::with(['category', 'vendor', 'brand'])
            ->where('status', 'active');

        // Category filter - include products from child categories
        if ($request->has('categories') && !empty($request->categories)) {
            $categoryIds = $request->categories;
            // Get all child category IDs for selected parent categories
            $childCategoryIds = Category::whereIn('parent_category_id', $categoryIds)->pluck('id')->toArray();
            // Merge parent and child category IDs
            $allCategoryIds = array_merge($categoryIds, $childCategoryIds);
            $query->whereIn('category_id', $allCategoryIds);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Brand filter - include products from child brands
        if ($request->has('brands') && !empty($request->brands)) {
            $brandIds = $request->brands;
            // Get all child brand IDs for selected parent brands
            $childBrandIds = Brand::whereIn('parent_brand_id', $brandIds)->pluck('id')->toArray();
            // Merge parent and child brand IDs
            $allBrandIds = array_merge($brandIds, $childBrandIds);
            $query->whereIn('brand_id', $allBrandIds);
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

        // Paginate - 16 items per page
        $perPage = $request->get('per_page', 16);
        $products = $query->paginate($perPage)->withQueryString();

        // Get only parent categories (admin categories) with product counts including child products
        $categories = Category::where('is_active', true)
            ->whereNull('vendor_id') // Only admin categories
            ->with('children')
            ->get()
            ->map(function($category) {
                // Count products from parent and all children
                $productCount = $category->products()->where('status', 'active')->count();
                foreach($category->children as $child) {
                    $productCount += $child->products()->where('status', 'active')->count();
                }
                $category->products_count = $productCount;
                return $category;
            })
            ->filter(function($category) {
                return $category->products_count > 0;
            });

        // Get only parent brands (admin brands) - show all since parent-child system is new
        $brands = Brand::where('is_active', true)
            ->whereNull('vendor_id') // Only admin brands
            ->orderBy('name')
            ->get()
            ->map(function($brand) {
                // Count products from parent and all children
                $productCount = $brand->products()->where('status', 'active')->count();
                $children = Brand::where('parent_brand_id', $brand->id)->get();
                foreach($children as $child) {
                    $productCount += $child->products()->where('status', 'active')->count();
                }
                $brand->products_count = $productCount;
                return $brand;
            });

        return view('shop', compact('products', 'categories', 'brands'));
    }
}
