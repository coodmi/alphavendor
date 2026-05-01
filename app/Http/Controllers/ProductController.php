<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Brand;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'vendor', 'brand', 'attributes']);

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
        $attributes = Attribute::orderBy('sort_order')->orderBy('name')->get();
        $offers = \App\Models\SpecialOffer::where('is_active', true)->orderBy('sort_order')->get();
        $shippingMethods = \App\Models\ShippingMethod::where('is_active', true)->orderBy('sort_order')->orderBy('zone')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'vendors', 'attributes', 'offers', 'shippingMethods'));
    }

    /**
     * Save product as draft (called when user clicks outside modal)
     */
    public function saveDraft(Request $request)
    {
        try {
            $data = $request->only([
                'name', 'sku', 'category_id', 'vendor_id', 'brand_id',
                'price', 'old_price', 'stock', 'description', 'badge',
            ]);

            // Need at least a name or price to save
            if (empty($data['name']) && empty($data['price'])) {
                return response()->json(['success' => false, 'message' => 'Nothing to save']);
            }

            // Set required defaults
            $data['status']        = 'draft';
            $data['vendor_id']     = !empty($data['vendor_id']) ? $data['vendor_id'] : auth()->id();
            $data['category_id']   = !empty($data['category_id']) ? $data['category_id'] : null;
            $data['price']         = !empty($data['price']) ? $data['price'] : 0;
            $data['stock']         = !empty($data['stock']) ? $data['stock'] : 0;
            $data['name']          = !empty($data['name']) ? $data['name'] : 'Draft Product ' . now()->format('H:i:s');
            $data['rating']        = 0;
            $data['reviews_count'] = 0;
            $data['image']         = null; // no image required for drafts

            // Handle image if provided
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            // Check if there's already a draft for this user to update
            $existingDraft = \App\Models\Product::where('vendor_id', auth()->id())
                ->where('status', 'draft')
                ->latest()
                ->first();

            if ($existingDraft) {
                unset($data['image']); // don't overwrite image on update unless new one provided
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('products', 'public');
                }
                $existingDraft->update($data);
                $product = $existingDraft;
            } else {
                $product = \App\Models\Product::create($data);
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Draft saved successfully',
                'product_id' => $product->id,
                'saved_at'   => now()->format('H:i:s'),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        // Check if vendor is approved
        $vendor = auth()->user();
        if (in_array($vendor->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            if ($vendor->status !== 'active') {
                return redirect()->back()->with('error', 'Your account must be approved by admin before you can add products. Current status: ' . ucfirst($vendor->status));
            }
        }

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
            'free_shipping' => 'boolean',
            'special_offer_id' => 'nullable|exists:special_offers,id',
            'badge' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['free_shipping'] = $request->has('free_shipping');
        $validated['rating'] = 0;
        $validated['reviews_count'] = 0;

        $product = Product::create($validated);

        // Handle attributes - save values with proper type handling
        if ($request->has('attributes')) {
            $attributesData = [];
            $requiredAttributes = \App\Models\Attribute::where('is_required', true)->get();

            // Validate required attributes
            foreach ($requiredAttributes as $reqAttr) {
                $val = $request->input("attributes.{$reqAttr->id}");
                if (empty($val)) {
                    $product->delete(); // rollback
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(["attributes.{$reqAttr->id}" => "The {$reqAttr->name} attribute is required."]);
                }
            }

            foreach ($request->attributes as $attributeId => $value) {
                if (!empty($value)) {
                    $attributesData[$attributeId] = ['value' => $value];
                }
            }
            $product->attributes()->sync($attributesData);
        }

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
            'free_shipping' => 'boolean',
            'special_offer_id' => 'nullable|exists:special_offers,id',
            'badge' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['free_shipping'] = $request->has('free_shipping');

        $product->update($validated);

        // Handle attributes
        if ($request->has('attributes')) {
            $attributesData = [];
            foreach ($request->attributes as $attributeId => $value) {
                if (!empty($value)) {
                    $attributesData[$attributeId] = ['value' => $value];
                }
            }
            $product->attributes()->sync($attributesData);
        } else {
            $product->attributes()->detach();
        }

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

        // Category filter - supports ?categories[]=id (sidebar) or ?category=slug (home page links)
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        } elseif ($request->filled('category')) {
            $cat = \App\Models\Category::where('slug', $request->category)->first();
            if ($cat) $query->where('category_id', $cat->id);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Brand filter - supports ?brands[]=id (sidebar) or ?brand=slug (home page links)
        if ($request->has('brands') && !empty($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        } elseif ($request->filled('brand')) {
            $brand = \App\Models\Brand::where('slug', $request->brand)->first();
            if ($brand) $query->where('brand_id', $brand->id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
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
