<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = Product::with(['category', 'vendor'])->latest()->get();
        $categories = Category::where('is_active', true)->get();
        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'admin'])->get();
        
        return view('admin.products.index', compact('products', 'categories', 'vendors'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:users,id',
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
    public function shop()
    {
        $products = Product::with(['category', 'vendor'])
            ->where('status', 'active')
            ->latest()
            ->get();
            
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('shop', compact('products', 'categories'));
    }
}
