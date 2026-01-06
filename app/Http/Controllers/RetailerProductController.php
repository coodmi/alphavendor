<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RetailerProductController extends Controller
{
    public function index()
    {
        // Get only products belonging to the authenticated retailer
        $products = Product::with(['category', 'brand'])
            ->where('vendor_id', Auth::id())
            ->latest()
            ->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('retailer.products.index', compact('products', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50'
        ]);

        // Handle image upload or URL
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['vendor_id'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product->load(['category', 'brand'])
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // Ensure retailer can only update their own products
        if ($product->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this product!'
            ], 403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50'
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if it's a stored file
            if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            // Delete old image if it's a stored file
            if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product->load(['category', 'brand'])
        ]);
    }

    public function destroy(Product $product)
    {
        // Ensure retailer can only delete their own products
        if ($product->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this product!'
            ], 403);
        }

        // Delete image if it's a stored file
        if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }
}
