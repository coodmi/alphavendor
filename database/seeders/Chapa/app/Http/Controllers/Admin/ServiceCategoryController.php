<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('products')->latest()->paginate(15);

        return view('admin.services.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.services.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ServiceCategory::create($validated);

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category created successfully!');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.services.categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serviceCategory->update($validated);

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category updated successfully!');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category deleted successfully!');
    }
}
