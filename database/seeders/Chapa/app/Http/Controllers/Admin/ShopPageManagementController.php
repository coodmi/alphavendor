<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopPageSetting;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopPageManagementController extends Controller
{
    /**
     * Display shop page management dashboard
     */
    public function index()
    {
        $sections = [
            'hero' => ShopPageSetting::getSection('hero'),
            'filters' => ShopPageSetting::getSection('filters'),
            'products_grid' => ShopPageSetting::getSection('products_grid'),
            'featured_categories' => ShopPageSetting::getSection('featured_categories'),
        ];

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'categories' => Category::count(),
            'sections_configured' => collect($sections)->filter()->count(),
        ];

        return view('admin.shop-page.index', compact('sections', 'stats'));
    }

    /**
     * Edit hero section
     */
    public function editHero()
    {
        $hero = ShopPageSetting::getSection('hero');
        
        return view('admin.shop-page.hero', compact('hero'));
    }

    /**
     * Update hero section
     */
    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'subtitle' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'badges' => 'nullable|array',
            'badges.*' => 'nullable|string|max:100',
            'slides' => 'nullable|array',
            'slides.*.title' => 'nullable|string|max:255',
            'slides.*.description' => 'nullable|string|max:500',
            'slides.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'slides.*.existing_image' => 'nullable|string',
            'stat1_label' => 'nullable|string|max:100',
            'stat1_value' => 'nullable|string|max:50',
            'stat1_sublabel' => 'nullable|string|max:100',
            'stat2_label' => 'nullable|string|max:100',
            'stat2_value' => 'nullable|string|max:50',
            'stat2_sublabel' => 'nullable|string|max:100',
            'stat3_label' => 'nullable|string|max:100',
            'stat3_value' => 'nullable|string|max:50',
            'stat3_sublabel' => 'nullable|string|max:100',
            'stat4_label' => 'nullable|string|max:100',
            'stat4_value' => 'nullable|string|max:50',
            'stat4_sublabel' => 'nullable|string|max:100',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('shop-hero', $imageName, 'public');
            $validated['cover_image'] = '/storage/' . $path;
        }

        // Handle slider images
        if (isset($validated['slides'])) {
            foreach ($validated['slides'] as $index => $slide) {
                if ($request->hasFile("slides.{$index}.image")) {
                    // New image uploaded
                    $image = $request->file("slides.{$index}.image");
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('shop-hero/slides', $imageName, 'public');
                    $validated['slides'][$index]['image'] = '/storage/' . $path;
                } elseif ($request->has("slides.{$index}.existing_image")) {
                    // Preserve existing image
                    $validated['slides'][$index]['image'] = $request->input("slides.{$index}.existing_image");
                }
                
                // Remove the existing_image field from final data
                unset($validated['slides'][$index]['existing_image']);
            }
            
            // Re-index the array
            $validated['slides'] = array_values($validated['slides']);
        }

        // Filter out empty badges
        if (isset($validated['badges'])) {
            $validated['badges'] = array_filter($validated['badges'], function($badge) {
                return !empty(trim($badge));
            });
        }

        ShopPageSetting::updateSection('hero', $validated);

        return redirect()->route('admin.shop-page.hero')
            ->with('success', 'Hero section updated successfully!');
    }

    /**
     * Edit filters section
     */
    public function editFilters()
    {
        $filters = ShopPageSetting::getSection('filters');
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.shop-page.filters', compact('filters', 'categories'));
    }

    /**
     * Update filters section
     */
    public function updateFilters(Request $request)
    {
        $validated = $request->validate([
            'show_category_filter' => 'boolean',
            'show_price_filter' => 'boolean',
            'show_rating_filter' => 'boolean',
            'show_availability_filter' => 'boolean',
            'default_sort' => 'nullable|string|in:newest,oldest,price_low,price_high,rating,popularity',
            'products_per_page' => 'nullable|integer|min:6|max:48',
        ]);

        $validated['show_category_filter'] = $request->has('show_category_filter');
        $validated['show_price_filter'] = $request->has('show_price_filter');
        $validated['show_rating_filter'] = $request->has('show_rating_filter');
        $validated['show_availability_filter'] = $request->has('show_availability_filter');

        ShopPageSetting::updateSection('filters', $validated);

        return redirect()->route('admin.shop-page.filters')
            ->with('success', 'Filters section updated successfully!');
    }

    /**
     * Edit products grid section
     */
    public function editProductsGrid()
    {
        $productsGrid = ShopPageSetting::getSection('products_grid');
        
        return view('admin.shop-page.products-grid', compact('productsGrid'));
    }

    /**
     * Update products grid section
     */
    public function updateProductsGrid(Request $request)
    {
        $validated = $request->validate([
            'grid_columns' => 'required|integer|min:2|max:6',
            'show_product_badges' => 'boolean',
            'show_product_ratings' => 'boolean',
            'show_quick_view' => 'boolean',
            'show_add_to_cart' => 'boolean',
            'card_style' => 'required|string|in:minimal,detailed,modern',
            'hover_effect' => 'required|string|in:none,lift,zoom,fade',
        ]);

        $validated['show_product_badges'] = $request->has('show_product_badges');
        $validated['show_product_ratings'] = $request->has('show_product_ratings');
        $validated['show_quick_view'] = $request->has('show_quick_view');
        $validated['show_add_to_cart'] = $request->has('show_add_to_cart');

        ShopPageSetting::updateSection('products_grid', $validated);

        return redirect()->route('admin.shop-page.products-grid')
            ->with('success', 'Products grid updated successfully!');
    }

    /**
     * Edit featured categories section
     */
    public function editFeaturedCategories()
    {
        $featuredCategories = ShopPageSetting::getSection('featured_categories');
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.shop-page.featured-categories', compact('featuredCategories', 'categories'));
    }

    /**
     * Update featured categories section
     */
    public function updateFeaturedCategories(Request $request)
    {
        $validated = $request->validate([
            'show_section' => 'boolean',
            'section_title' => 'nullable|string|max:255',
            'section_subtitle' => 'nullable|string|max:500',
            'featured_category_ids' => 'nullable|array',
            'featured_category_ids.*' => 'exists:categories,id',
            'categories_per_row' => 'required|integer|min:2|max:6',
        ]);

        $validated['show_section'] = $request->has('show_section');

        ShopPageSetting::updateSection('featured_categories', $validated);

        return redirect()->route('admin.shop-page.featured-categories')
            ->with('success', 'Featured categories updated successfully!');
    }
}