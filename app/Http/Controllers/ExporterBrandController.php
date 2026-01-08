<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExporterBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('vendor_id', Auth::id())
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        // Get all admin brands for the dropdown
        $adminBrands = Brand::whereNull('vendor_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('exporter.brands.index', compact('brands', 'adminBrands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Verify that parent_brand_id is an admin brand
        $parentBrand = Brand::find($validated['parent_brand_id']);
        if (!$parentBrand || !$parentBrand->isAdminBrand()) {
            return response()->json([
                'success' => false,
                'message' => 'Parent brand must be an admin brand!'
            ], 422);
        }

        // Handle logo upload or URL
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        } elseif ($request->filled('logo_url')) {
            $validated['logo'] = $validated['logo_url'];
        }
        unset($validated['logo_url']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['vendor_id'] = Auth::id();

        $brand = Brand::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully!',
            'brand' => $brand->load('parent')
        ]);
    }

    public function update(Request $request, Brand $brand)
    {
        // Ensure exporter can only update their own brands
        if ($brand->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this brand!'
            ], 403);
        }

        $validated = $request->validate([
            'parent_brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Verify that parent_brand_id is an admin brand
        $parentBrand = Brand::find($validated['parent_brand_id']);
        if (!$parentBrand || !$parentBrand->isAdminBrand()) {
            return response()->json([
                'success' => false,
                'message' => 'Parent brand must be an admin brand!'
            ], 422);
        }

        // Handle logo update
        if ($request->hasFile('logo')) {
            // Delete old logo if it's a stored file
            if ($brand->logo && !filter_var($brand->logo, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        } elseif ($request->filled('logo_url')) {
            // Delete old logo if it's a stored file
            if ($brand->logo && !filter_var($brand->logo, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $validated['logo_url'];
        }
        unset($validated['logo_url']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $brand->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully!',
            'brand' => $brand->load('parent')
        ]);
    }

    public function destroy(Brand $brand)
    {
        // Ensure exporter can only delete their own brands
        if ($brand->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this brand!'
            ], 403);
        }

        // Delete logo if it's a stored file
        if ($brand->logo && !filter_var($brand->logo, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully!'
        ]);
    }
}
