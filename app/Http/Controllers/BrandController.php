<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of brands
     */
    public function index()
    {
        // Admin sees only brands with no vendor_id (global brands)
        $brands = Brand::whereNull('vendor_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Brand::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Brand created successfully!');
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $brand->update($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Brand updated successfully!');
    }

    /**
     * Remove the specified brand
     */
    public function destroy(Brand $brand)
    {
        // Delete logo
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Brand deleted successfully!');
    }
}
