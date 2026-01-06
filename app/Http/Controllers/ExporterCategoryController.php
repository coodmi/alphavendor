<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExporterCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('vendor_id', Auth::id())
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        return view('exporter.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Handle image upload or URL
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['vendor_id'] = Auth::id();

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        // Ensure exporter can only update their own categories
        if ($category->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this category!'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if it's a stored file
            if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->filled('image_url')) {
            // Delete old image if it's a stored file
            if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category->load('products')
        ]);
    }

    public function destroy(Category $category)
    {
        // Ensure exporter can only delete their own categories
        if ($category->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this category!'
            ], 403);
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated products!'
            ], 422);
        }

        // Delete image if it's a stored file
        if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }
}
