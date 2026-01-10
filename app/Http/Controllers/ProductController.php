<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'vendor', 'brand']);

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $products = $query->get();
        
        // Admin sees only global categories and brands (with null vendor_id)
        $categories = Category::whereNull('vendor_id')
            ->where('is_active', true)
            ->get();
        $brands = Brand::whereNull('vendor_id')
            ->where('is_active', true)
            ->get();
        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'admin'])->get();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'vendors'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:users,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products',
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['rating'] = 0;
        $validated['reviews_count'] = 0;

        Product::create($validated);

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:users,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Display shop page with products
     */
    public function shop(Request $request)
    {
        $query = Product::with(['category', 'vendor', 'brand'])
            ->where('status', 'active')
            ->whereHas('vendor', function($q) {
                $q->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
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

        // Vendor type filter
        if ($request->has('vendor_types') && !empty($request->vendor_types)) {
            $query->whereHas('vendor', function($q) use ($request) {
                $q->whereIn('role', $request->vendor_types);
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'default');
        switch ($sortBy) {
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

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->withQueryString();

        // Get only parent categories (admin categories) with product counts including child products from all vendor types
        $categories = Category::where('is_active', true)
            ->whereNull('vendor_id') // Only admin categories
            ->with(['children' => function($q) {
                $q->where('is_active', true)
                  ->whereHas('vendor', function($query) {
                      $query->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
                  });
            }])
            ->get()
            ->map(function($category) {
                // Count products from parent and all children (all vendor types)
                $productCount = $category->products()
                    ->where('status', 'active')
                    ->whereHas('vendor', function($query) {
                        $query->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
                    })
                    ->count();
                foreach($category->children as $child) {
                    $productCount += $child->products()
                        ->where('status', 'active')
                        ->whereHas('vendor', function($query) {
                            $query->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
                        })
                        ->count();
                }
                $category->products_count = $productCount;
                return $category;
            })
            ->filter(function($category) {
                return $category->products_count > 0;
            })
            ->values();

        // Get only parent brands (admin brands) with product counts including child products from all vendor types
        $brands = Brand::where('is_active', true)
            ->whereNull('vendor_id') // Only admin brands
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function($brand) {
                // Count products from parent and all children (all vendor types)
                $productCount = $brand->products()
                    ->where('status', 'active')
                    ->whereHas('vendor', function($query) {
                        $query->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
                    })
                    ->count();
                $children = Brand::where('parent_brand_id', $brand->id)
                    ->whereHas('vendor', function($query) {
                        $query->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
                    })
                    ->get();
                foreach($children as $child) {
                    $productCount += $child->products()
                        ->where('status', 'active')
                        ->whereHas('vendor', function($query) {
                            $query->whereIn('role', ['retailer', 'wholesaler', 'exporter']);
                        })
                        ->count();
                }
                $brand->products_count = $productCount;
                return $brand;
            })
            ->filter(function($brand) {
                return $brand->products_count > 0;
            })
            ->values();

        // Get vendor types with counts
        $vendorTypes = User::selectRaw('role, COUNT(DISTINCT products.id) as products_count')
            ->join('products', 'users.id', '=', 'products.vendor_id')
            ->whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('products.status', 'active')
            ->groupBy('role')
            ->get();

        return view('shop', compact('products', 'categories', 'brands', 'vendorTypes'));
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = Product::with(['category', 'vendor', 'brand'])->findOrFail($id);

        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
