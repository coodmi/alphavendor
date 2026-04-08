<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ShopProductController extends Controller
{
    /**
     * Display a listing of shop-only products.
     */
    public function index()
    {
        $products = Product::with('category')
            ->where('is_shop_only', true)
            ->latest()
            ->paginate(15);

        return view('admin.shop.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new shop product.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.shop.products.create', compact('categories'));
    }

    /**
     * Store a newly created shop product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'format' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'min_pages' => 'nullable|integer|min:1',
            'max_pages' => 'nullable|integer|min:1',
            'stock' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            // Generate placeholder image URL
            $validated['image'] = 'https://placehold.co/400x300?text=' . urlencode($validated['title']);
        }

        // Set base_price to price if not provided
        if (!isset($validated['base_price'])) {
            $validated['base_price'] = $validated['price'];
        }

        // Ensure this is a shop-only product
        $validated['is_shop_only'] = true;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['stock'] = $request->boolean('stock', true);

        Product::create($validated);

        return redirect()->route('admin.shop-products.index')
            ->with('success', 'Shop product created successfully!');
    }

    /**
     * Display the specified shop product.
     */
    public function show(Product $shopProduct)
    {
        // Ensure this is a shop-only product
        if (!$shopProduct->is_shop_only) {
            abort(404);
        }

        return view('admin.shop.products.show', compact('shopProduct'));
    }

    /**
     * Show the form for editing the specified shop product.
     */
    public function edit(Product $shopProduct)
    {
        // Ensure this is a shop-only product
        if (!$shopProduct->is_shop_only) {
            abort(404);
        }

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.shop.products.edit', compact('shopProduct', 'categories'));
    }

    /**
     * Update the specified shop product in storage.
     */
    public function update(Request $request, Product $shopProduct)
    {
        // Ensure this is a shop-only product
        if (!$shopProduct->is_shop_only) {
            abort(404);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'format' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'min_pages' => 'nullable|integer|min:1',
            'max_pages' => 'nullable|integer|min:1',
            'stock' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is not a placeholder
            if ($shopProduct->image && !str_contains($shopProduct->image, 'placehold.co')) {
                Storage::disk('public')->delete($shopProduct->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Set base_price to price if not provided
        if (!isset($validated['base_price'])) {
            $validated['base_price'] = $validated['price'];
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['stock'] = $request->boolean('stock', true);

        $shopProduct->update($validated);

        return redirect()->route('admin.shop-products.index')
            ->with('success', 'Shop product updated successfully!');
    }

    /**
     * Remove the specified shop product from storage.
     */
    public function destroy(Product $shopProduct)
    {
        // Ensure this is a shop-only product
        if (!$shopProduct->is_shop_only) {
            abort(404);
        }

        // Delete image if it exists and is not a placeholder
        if ($shopProduct->image && !str_contains($shopProduct->image, 'placehold.co')) {
            Storage::disk('public')->delete($shopProduct->image);
        }

        $shopProduct->delete();

        return redirect()->route('admin.shop-products.index')
            ->with('success', 'Shop product deleted successfully!');
    }

    /**
     * Toggle the active status of a shop product.
     */
    public function toggleStatus(Product $shopProduct)
    {
        // Ensure this is a shop-only product
        if (!$shopProduct->is_shop_only) {
            abort(404);
        }

        $shopProduct->update(['is_active' => !$shopProduct->is_active]);

        $status = $shopProduct->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Product {$status} successfully!",
            'is_active' => $shopProduct->is_active
        ]);
    }
}