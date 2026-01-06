<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RetailerBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();
        return view('retailer.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Handle logo upload or URL
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        } elseif ($request->filled('logo_url')) {
            $validated['logo'] = $validated['logo_url'];
        }
        unset($validated['logo_url']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $brand = Brand::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully!',
            'brand' => $brand
        ]);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

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
            'brand' => $brand
        ]);
    }

    public function destroy(Brand $brand)
    {
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
