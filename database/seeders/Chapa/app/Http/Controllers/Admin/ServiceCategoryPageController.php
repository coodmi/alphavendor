<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceCategoryPageController extends Controller
{
    /**
     * Display a listing of service category pages.
     */
    public function index()
    {
        $categories = ServiceCategory::withCount('products')->get();
        
        return view('admin.service-category-pages.index', compact('categories'));
    }

    /**
     * Show the form for editing a category page.
     */
    public function edit(ServiceCategory $category)
    {
        // Load category with its metadata
        $pageData = $this->getCategoryPageData($category);
        
        return view('admin.service-category-pages.edit', compact('category', 'pageData'));
    }

    /**
     * Update the category page content.
     */
    public function update(Request $request, ServiceCategory $category)
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_description' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'hero_background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'delete_hero_image' => 'nullable|boolean',
            'delete_background_image' => 'nullable|boolean',
            'tagline_bangla' => 'nullable|string|max:500',
            'tagline_bangla_subtitle' => 'nullable|string|max:500',
            'show_tagline' => 'nullable|boolean',
            'feature_badge_1' => 'nullable|string|max:100',
            'feature_badge_2' => 'nullable|string|max:100',
            'feature_badge_3' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'slider_images' => 'nullable|array',
            'slider_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'existing_slider_images' => 'nullable|array',
            'existing_slider_images.*' => 'nullable|string',
        ]);

        // Get existing page data
        $existingPageData = $category->page_data ? json_decode($category->page_data, true) : [];
        
        // Handle hero image deletion
        if ($request->input('delete_hero_image') == '1') {
            if (isset($existingPageData['hero_image']) && !filter_var($existingPageData['hero_image'], FILTER_VALIDATE_URL)) {
                $oldPath = public_path('uploads/category-pages/' . $existingPageData['hero_image']);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $validated['hero_image'] = null;
        }
        
        // Handle background image deletion
        if ($request->input('delete_background_image') == '1') {
            if (isset($existingPageData['hero_background_image']) && !filter_var($existingPageData['hero_background_image'], FILTER_VALIDATE_URL)) {
                $oldPath = public_path('uploads/category-pages/' . $existingPageData['hero_background_image']);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $validated['hero_background_image'] = null;
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            // Delete old image if exists
            if ($category->hero_image && !filter_var($category->hero_image, FILTER_VALIDATE_URL)) {
                $oldPath = public_path('uploads/category-pages/' . $category->hero_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $image = $request->file('hero_image');
            $imageName = time() . '_hero_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/category-pages'), $imageName);
            $validated['hero_image'] = $imageName;
        }

        // Handle hero background image upload
        if ($request->hasFile('hero_background_image')) {
            // Delete old image if exists
            if ($category->hero_background_image && !filter_var($category->hero_background_image, FILTER_VALIDATE_URL)) {
                $oldPath = public_path('uploads/category-pages/' . $category->hero_background_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $image = $request->file('hero_background_image');
            $imageName = time() . '_bg_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/category-pages'), $imageName);
            $validated['hero_background_image'] = $imageName;
        }

        // Handle slider images
        $sliderImages = [];
        $existingImages = $request->input('existing_slider_images', []);
        
        // Process existing images first
        foreach ($existingImages as $index => $existingImage) {
            if (!empty($existingImage)) {
                $sliderImages[$index] = $existingImage;
            }
        }
        
        // Process new uploaded images
        if ($request->hasFile('slider_images')) {
            foreach ($request->file('slider_images') as $index => $image) {
                if ($image) {
                    $imageName = time() . '_slider_' . $index . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/category-pages/sliders'), $imageName);
                    $sliderImages[$index] = $imageName;
                }
            }
        }
        
        // Re-index array to remove gaps
        $sliderImages = array_values(array_filter($sliderImages));

        // Get existing page data
        $existingPageData = $category->page_data ? json_decode($category->page_data, true) : [];
        
        // Store page data as JSON in a new column or separate table
        $pageData = [
            'hero_title' => $validated['hero_title'] ?? $category->name,
            'hero_subtitle' => $validated['hero_subtitle'] ?? $category->name . ' Printing',
            'hero_description' => $validated['hero_description'] ?? $category->description,
            'hero_image' => $validated['hero_image'] ?? $existingPageData['hero_image'] ?? null,
            'hero_background_image' => $validated['hero_background_image'] ?? $existingPageData['hero_background_image'] ?? null,
            'tagline_bangla' => $validated['tagline_bangla'] ?? null,
            'tagline_bangla_subtitle' => $validated['tagline_bangla_subtitle'] ?? null,
            'show_tagline' => $request->has('show_tagline'),
            'feature_badge_1' => $validated['feature_badge_1'] ?? 'Lead times 48h',
            'feature_badge_2' => $validated['feature_badge_2'] ?? 'Color-managed',
            'feature_badge_3' => $validated['feature_badge_3'] ?? 'Proofing included',
            'meta_title' => $validated['meta_title'] ?? $category->name . ' | Services',
            'meta_description' => $validated['meta_description'] ?? $category->description,
            'slider_images' => !empty($sliderImages) ? $sliderImages : ($existingPageData['slider_images'] ?? []),
        ];

        // Update category with page data
        $category->update([
            'page_data' => json_encode($pageData),
        ]);

        return redirect()->route('admin.service-category-pages.index')
            ->with('success', 'Category page updated successfully!');
    }

    /**
     * Get category page data with defaults.
     */
    private function getCategoryPageData(ServiceCategory $category)
    {
        $defaults = [
            'hero_title' => $category->name,
            'hero_subtitle' => $category->name . ' Printing',
            'hero_description' => $category->description,
            'hero_image' => null,
            'hero_background_image' => null,
            'tagline_bangla' => 'ম্যাগাজিন, বই এবং ক্যাটালগ প্রিন্টিং এর জন্য আপনার বিশ্বস্ত সঙ্গী',
            'tagline_bangla_subtitle' => 'উচ্চমানের প্রিন্টিং, দ্রুত ডেলিভারি এবং সাশ্রয়ী মূল্যে আপনার প্রকাশনা স্বপ্ন বাস্তবায়ন করুন',
            'show_tagline' => false,
            'feature_badge_1' => 'Lead times 48h',
            'feature_badge_2' => 'Color-managed',
            'feature_badge_3' => 'Proofing included',
            'meta_title' => $category->name . ' | Services',
            'meta_description' => $category->description,
            'slider_images' => [],
        ];

        if ($category->page_data) {
            $stored = json_decode($category->page_data, true);
            return array_merge($defaults, $stored ?? []);
        }

        return $defaults;
    }
}
