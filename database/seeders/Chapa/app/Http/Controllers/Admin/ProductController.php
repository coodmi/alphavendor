<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the shop-style product grid for dashboard AJAX.
     */
    public function storefront(Request $request)
    {
        $products = Product::with('category')->latest()->paginate(12);
        if ($request->ajax()) {
            return view('shop.partials.products-grid', compact('products'))->render();
        }
        return view('admin.products.storefront', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'format' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'min_pages' => 'nullable|integer|min:1',
            'max_pages' => 'nullable|integer|min:1',
            'rating' => 'nullable|numeric|between:0,5',
            'popularity' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'boolean',
            'is_active' => 'boolean',
            'is_shop_only' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $imageName, 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Set defaults
        $validated['stock'] = $request->has('stock');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_shop_only'] = $request->has('is_shop_only');
        $validated['rating'] = $validated['rating'] ?? 4.5;
        $validated['popularity'] = $validated['popularity'] ?? rand(50, 100);
        $validated['min_quantity'] = $validated['min_quantity'] ?? 1;

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'format' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'min_quantity' => 'nullable|integer|min:1',
            'min_pages' => 'nullable|integer|min:1',
            'max_pages' => 'nullable|integer|min:1',
            'rating' => 'nullable|numeric|between:0,5',
            'popularity' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'boolean',
            'is_active' => 'boolean',
            'is_shop_only' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }

            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $imageName, 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Set boolean values
        $validated['stock'] = $request->has('stock');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_shop_only'] = $request->has('is_shop_only');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image && str_starts_with($product->image, '/storage/')) {
            $imagePath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($imagePath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Product {$status} successfully!");
    }
}