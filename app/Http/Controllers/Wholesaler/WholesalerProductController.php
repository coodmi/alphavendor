<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\SyncsProductGallery;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SupplierLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WholesalerProductController extends Controller
{
    use SyncsProductGallery;

    public function index()
    {
        $products = Product::with(['category', 'brand', 'images', 'attributes'])
            ->where('vendor_id', Auth::id())
            ->latest()
            ->get();

        $categories = Category::where(function ($q) {
            $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id());
        })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where(function ($q) {
            $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id());
        })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $supplierLocations = SupplierLocation::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $offers = \App\Models\SpecialOffer::where('is_active', true)->orderBy('sort_order')->get();
        $shippingMethods = \App\Models\ShippingMethod::where('is_active', true)->orderBy('sort_order')->orderBy('zone')->get();
        $attributes = Attribute::orderBy('sort_order')->orderBy('name')->get();

        return view('wholesaler.products.index', compact(
            'products',
            'categories',
            'brands',
            'supplierLocations',
            'offers',
            'shippingMethods',
            'attributes'
        ));
    }

    public function store(Request $request)
    {
        $this->validateProductMetaKeywords($request);
        $this->validateGalleryImages($request, false);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|url',
            'gallery_images' => 'required|array|min:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_order' => 'required|integer|min:1',
            'supplier_location_id' => 'required|exists:supplier_locations,id',
            'sku' => 'required|string|max:255|unique:products,sku',
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'video' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:1000',
            'meta_description' => 'nullable|string|max:500',
            'special_offer_id' => 'nullable|exists:special_offers,id',
        ]);

        if (! $this->verifyCategoryAndBrand($validated)) {
            return response()->json(['success' => false, 'message' => 'Invalid category or brand!'], 422);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url'], $validated['gallery_images']);

        $validated['vendor_id'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');

        $product = Product::create($validated);
        $this->syncGalleryImages($product, $request);
        $this->syncAttributes($product, $request);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product->load(['category', 'brand', 'images', 'attributes']),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized!'], 403);
        }

        $this->validateProductMetaKeywords($request);
        $this->validateGalleryImages($request, true, $product);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|url',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_order' => 'required|integer|min:1',
            'supplier_location_id' => 'required|exists:supplier_locations,id',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive,out_of_stock',
            'is_featured' => 'boolean',
            'badge' => 'nullable|string|max:50',
            'video' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:1000',
            'meta_description' => 'nullable|string|max:500',
            'special_offer_id' => 'nullable|exists:special_offers,id',
        ]);

        if (! $this->verifyCategoryAndBrand($validated)) {
            return response()->json(['success' => false, 'message' => 'Invalid category or brand!'], 422);
        }

        if ($request->hasFile('image')) {
            if ($product->image && ! filter_var($product->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            if ($product->image && ! filter_var($product->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url'], $validated['gallery_images']);

        $validated['is_featured'] = $request->has('is_featured');
        $product->update($validated);
        $this->syncGalleryImages($product, $request);
        $this->syncAttributes($product, $request);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product->load(['category', 'brand', 'images', 'attributes']),
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized!'], 403);
        }

        if ($product->image && ! filter_var($product->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $img) {
            if (! filter_var($img->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($img->image);
            }
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
    }

    private function verifyCategoryAndBrand(array $validated): bool
    {
        $category = Category::where('id', $validated['category_id'])
            ->where(function ($q) {
                $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id());
            })
            ->first();

        if (! $category) {
            return false;
        }

        if (! empty($validated['brand_id'])) {
            $brand = Brand::where('id', $validated['brand_id'])
                ->where(function ($q) {
                    $q->whereNull('vendor_id')->orWhere('vendor_id', Auth::id());
                })
                ->first();

            if (! $brand) {
                return false;
            }
        }

        return true;
    }

    private function syncAttributes(Product $product, Request $request): void
    {
        if ($request->has('attributes')) {
            $attributesData = [];
            foreach ($request->attributes as $attributeId => $value) {
                if ($value !== null && $value !== '') {
                    $attributesData[$attributeId] = ['value' => is_array($value) ? implode(', ', $value) : $value];
                }
            }
            $product->attributes()->sync($attributesData);
        } else {
            $product->attributes()->detach();
        }
    }
}
