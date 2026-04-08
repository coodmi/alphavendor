<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::whereNull('vendor_id')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'sort_order'  => 'nullable|integer',
        ]);

        // Check uniqueness manually (case-insensitive, admin brands only)
        if (Brand::whereNull('vendor_id')->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])->exists()) {
            return back()->withErrors(['name' => 'A brand with this name already exists.'])->withInput();
        }

        // Generate unique slug
        $slug = Str::slug($validated['name']);
        $original = $slug;
        $i = 1;
        while (Brand::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create([
            'vendor_id'   => null,
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'logo'        => $validated['logo'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.brands')
            ->with('success', 'Brand "' . $validated['name'] . '" created successfully!');
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'sort_order'  => 'nullable|integer',
        ]);

        // Regenerate slug only if name changed
        if ($brand->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $original = $slug;
            $i = 1;
            while (Brand::where('slug', $slug)->where('id', '!=', $brand->id)->exists()) {
                $slug = $original . '-' . $i++;
            }
            $brand->slug = $slug;
        }

        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $brand->logo = $request->file('logo')->store('brands', 'public');
        }

        $brand->name        = $validated['name'];
        $brand->description = $validated['description'] ?? null;
        $brand->is_active   = $request->boolean('is_active', true);
        $brand->sort_order  = $validated['sort_order'] ?? 0;
        $brand->save();

        return redirect()->route('admin.brands')
            ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands')
            ->with('success', 'Brand deleted successfully!');
    }
}
