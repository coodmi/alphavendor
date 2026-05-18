<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SyncsProductGallery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImporterCategoryController extends Controller
{
    use SyncsProductGallery;

    public function index()
    {
        $categories = Category::where('vendor_id', Auth::id())
            ->with('parent')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $adminCategories = Category::whereNull('vendor_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('importer.categories.index', compact('categories', 'adminCategories'));
    }

    public function store(Request $request)
    {
        $this->validateCategoryMetaKeywords($request);

        $validated = $request->validate([
            'parent_category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'required|string|max:1000',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['is_active'] = $request->input('is_active', '1') === '1';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['vendor_id'] = Auth::id();

        $parentCategory = Category::find($validated['parent_category_id']);
        if ($parentCategory && ! $parentCategory->isAdminCategory()) {
            return response()->json([
                'success' => false,
                'message' => 'Parent category must be an admin category!',
            ], 422);
        }

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        if ($category->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this category!',
            ], 403);
        }

        $this->validateCategoryMetaKeywords($request);

        $validated = $request->validate([
            'parent_category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'required|string|max:1000',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image && ! filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->filled('image_url')) {
            if ($category->image && ! filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $validated['image_url'];
        }
        unset($validated['image_url']);

        $validated['is_active'] = $request->input('is_active', '1') === '1';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category->load('products'),
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this category!',
            ], 403);
        }

        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated products!',
            ], 422);
        }

        if ($category->image && ! filter_var($category->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
        ]);
    }
}
