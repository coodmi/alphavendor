<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExporterProductController extends Controller
{
    public function index()
    {
        // Get only products belonging to the authenticated exporter
        $products = Product::with(['category', 'brand'])
            ->where('vendor_id', Auth::id())
            ->latest()
            ->get();

        // Get categories and brands belonging to the authenticated exporter or created by admin (vendor_id = null)
        $categories = Category::where(function($q) {
                $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id());
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $brands = Brand::where(function($q) {
                $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id());
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get certifications belonging to the authenticated exporter
        $certifications = \App\Models\Certification::where('vendor_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get active special offers
        $offers = \App\Models\SpecialOffer::where('is_active', true)->orderBy('sort_order')->get();
        
        // Get active shipping methods
        $shippingMethods = \App\Models\ShippingMethod::where('is_active', true)->orderBy('sort_order')->orderBy('zone')->get();

        // Get global attributes
        $attributes = \App\Models\Attribute::orderBy('sort_order')->orderBy('name')->get();

        return view('exporter.products.index', compact('products', 'categories', 'brands', 'certifications', 'offers', 'shippingMethods', 'attributes'));
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
            'minimum_order' => 'nullable|integer|min:1',
            'supplier_location' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
            'exporter_rating' => 'nullable|numeric|min:0|max:5'
        ]);

        // Verify category belongs to this exporter or is admin-created
        $category = Category::where('id', $validated['category_id'])
            ->where(function($q) { $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id()); })
            ->first();
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category selected!'
            ], 422);
        }

        // Verify brand belongs to this exporter or is admin-created (if provided)
        if (!empty($validated['brand_id'])) {
            $brand = Brand::where('id', $validated['brand_id'])
                ->where(function($q) { $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id()); })
                ->first();
            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid brand selected!'
                ], 422);
            }
        }

        // Handle image upload or URL
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['vendor_id'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['minimum_order'] = $validated['minimum_order'] ?? 1;

        $product = Product::create($validated);

        // Sync attributes
        if ($request->has('attributes')) {
            $attributesData = [];
            foreach ($request->attributes as $attributeId => $value) {
                $attributesData[$attributeId] = ['value' => $value];
            }
            $product->attributes()->sync($attributesData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product->load(['category', 'brand'])
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // Ensure exporter can only update their own products
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
            'minimum_order' => 'nullable|integer|min:1',
            'supplier_location' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
            'exporter_rating' => 'nullable|numeric|min:0|max:5'
        ]);

        // Verify category belongs to this exporter or is admin-created
        $category = Category::where('id', $validated['category_id'])
            ->where(function($q) { $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id()); })
            ->first();
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category selected!'
            ], 422);
        }

        // Verify brand belongs to this exporter or is admin-created (if provided)
        if (!empty($validated['brand_id'])) {
            $brand = Brand::where('id', $validated['brand_id'])
                ->where(function($q) { $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id()); })
                ->first();
            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid brand selected!'
                ], 422);
            }
        }

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
        $validated['minimum_order'] = $validated['minimum_order'] ?? 1;

        $product->update($validated);

        // Sync attributes
        if ($request->has('attributes')) {
            $attributesData = [];
            foreach ($request->attributes as $attributeId => $value) {
                $attributesData[$attributeId] = ['value' => $value];
            }
            $product->attributes()->sync($attributesData);
        } else {
            $product->attributes()->detach();
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product->load(['category', 'brand'])
        ]);
    }

    public function destroy(Product $product)
    {
        // Ensure exporter can only delete their own products
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
