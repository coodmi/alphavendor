<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    public function index()
    {
        $homePageSetting = HomePageSetting::getActive() ?? new HomePageSetting();
        return view('admin.home-page.index', compact('homePageSetting'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        // Handle file uploads
        $validated = $this->handleFileUploads($request, $validated);

        // Deactivate all existing home page settings
        HomePageSetting::query()->update(['is_active' => false]);

        // Create new active home page setting
        $validated['is_active'] = true;
        HomePageSetting::create($validated);

        return redirect()->route('admin.home-page.index')
            ->with('success', 'Home page settings updated successfully!');
    }

    public function update(Request $request, HomePageSetting $homePage)
    {
        $validated = $this->validateRequest($request);
        
        // Handle file uploads
        $validated = $this->handleFileUploads($request, $validated, $homePage);

        $homePage->update($validated);

        return redirect()->route('admin.home-page.index')
            ->with('success', 'Home page settings updated successfully!');
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            // Hero Slider
            'hero_slider.slides' => 'nullable|array',
            'hero_slider.slides.*.title' => 'nullable|string|max:255',
            'hero_slider.slides.*.subtitle' => 'nullable|string|max:255',
            'hero_slider.slides.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'hero_slider.slides.*.existing_image' => 'nullable|string',
            'hero_slider.slides.*.cta_text' => 'nullable|string|max:100',
            'hero_slider.slides.*.cta_url' => 'nullable|string|max:255',
            'hero_slider.stats.percentage' => 'nullable|string|max:10',
            'hero_slider.stats.label' => 'nullable|string|max:255',
            'hero_slider.stats.reviews_count' => 'nullable|string|max:50',

            // Headline
            'headline.title' => 'required|string|max:500',
            'headline.description' => 'required|string|max:2000',

            // How to Order
            'how_to_order.title' => 'required|string|max:255',
            'how_to_order.steps' => 'nullable|array',
            'how_to_order.steps.*.number' => 'nullable|string|max:10',
            'how_to_order.steps.*.title' => 'nullable|string|max:255',
            'how_to_order.steps.*.description' => 'nullable|string|max:500',
            'how_to_order.video_url' => 'nullable|string|max:500',
            'how_to_order.video_poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',

            // Best Sellers
            'best_sellers.title' => 'required|string|max:255',
            'best_sellers.products' => 'nullable|array',
            'best_sellers.products.*.title' => 'nullable|string|max:255',
            'best_sellers.products.*.url' => 'nullable|string|max:255',
            'best_sellers.products.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'best_sellers.products.*.existing_image' => 'nullable|string',

            // Testimonials
            'testimonials.title' => 'required|string|max:255',
            'testimonials.subtitle' => 'nullable|string|max:500',
            'testimonials.items' => 'nullable|array',
            'testimonials.items.*.author' => 'nullable|string|max:255',
            'testimonials.items.*.designation' => 'nullable|string|max:255',
            'testimonials.items.*.text' => 'nullable|string|max:1000',
            'testimonials.items.*.rating' => 'nullable|integer|min:1|max:5',
            'testimonials.items.*.avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',

            // Offer Banner
            'offer_banner.title' => 'nullable|string|max:255',
            'offer_banner.subtitle' => 'nullable|string|max:255',
            'offer_banner.description' => 'nullable|string|max:500',
            'offer_banner.cta_text' => 'nullable|string|max:100',
            'offer_banner.cta_url' => 'nullable|string|max:255',
            'offer_banner.background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'offer_banner.existing_background_image' => 'nullable|string',

            // Trust Section
            'trust_section.title' => 'nullable|string|max:255',
            'trust_section.subtitle' => 'nullable|string|max:500',
            'trust_section.brands' => 'nullable|array',
            'trust_section.brands.*.name' => 'nullable|string|max:255',
            'trust_section.brands.*.logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'trust_section.brands.*.existing_logo' => 'nullable|string',

            // SEO Settings
            'seo_settings.title' => 'required|string|max:255',
            'seo_settings.description' => 'required|string|max:500',
            'seo_settings.keywords' => 'nullable|string|max:500',
        ]);
    }

    private function handleFileUploads(Request $request, array $validated, HomePageSetting $homePage = null)
    {
        // Handle hero slider images
        if (isset($validated['hero_slider']['slides'])) {
            foreach ($validated['hero_slider']['slides'] as $index => $slide) {
                if ($request->hasFile("hero_slider.slides.{$index}.image")) {
                    // New image uploaded
                    $imagePath = $request->file("hero_slider.slides.{$index}.image")->store('home-page/hero-slider', 'public');
                    $validated['hero_slider']['slides'][$index]['image'] = '/storage/' . $imagePath;
                } elseif ($request->has("hero_slider.slides.{$index}.existing_image")) {
                    // Preserve existing image
                    $validated['hero_slider']['slides'][$index]['image'] = $request->input("hero_slider.slides.{$index}.existing_image");
                }
                
                // Remove the existing_image field from the final data
                unset($validated['hero_slider']['slides'][$index]['existing_image']);
            }
            
            // Re-index the array to ensure sequential keys
            $validated['hero_slider']['slides'] = array_values($validated['hero_slider']['slides']);
        }

        // Handle video poster
        if ($request->hasFile('how_to_order.video_poster')) {
            if ($homePage && isset($homePage->how_to_order['video_poster'])) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $homePage->how_to_order['video_poster']));
            }
            $posterPath = $request->file('how_to_order.video_poster')->store('home-page/video', 'public');
            $validated['how_to_order']['video_poster'] = '/storage/' . $posterPath;
        }

        // Handle best sellers product images
        if (isset($validated['best_sellers']['products'])) {
            foreach ($validated['best_sellers']['products'] as $index => $product) {
                if ($request->hasFile("best_sellers.products.{$index}.image")) {
                    // New image uploaded
                    $imagePath = $request->file("best_sellers.products.{$index}.image")->store('home-page/best-sellers', 'public');
                    $validated['best_sellers']['products'][$index]['image'] = '/storage/' . $imagePath;
                } elseif ($request->has("best_sellers.products.{$index}.existing_image")) {
                    // Preserve existing image
                    $validated['best_sellers']['products'][$index]['image'] = $request->input("best_sellers.products.{$index}.existing_image");
                }
                
                // Remove the existing_image field from final data
                unset($validated['best_sellers']['products'][$index]['existing_image']);
            }
            
            // Re-index the array
            $validated['best_sellers']['products'] = array_values($validated['best_sellers']['products']);
        }

        // Handle testimonial avatar images
        if (isset($validated['testimonials']['items'])) {
            foreach ($validated['testimonials']['items'] as $index => $testimonial) {
                if ($request->hasFile("testimonials.items.{$index}.avatar_image")) {
                    $imagePath = $request->file("testimonials.items.{$index}.avatar_image")->store('home-page/testimonials', 'public');
                    $validated['testimonials']['items'][$index]['avatar_image'] = '/storage/' . $imagePath;
                }
            }
        }

        // Handle brand logos
        if (isset($validated['trust_section']['brands'])) {
            foreach ($validated['trust_section']['brands'] as $index => $brand) {
                if ($request->hasFile("trust_section.brands.{$index}.logo")) {
                    $logoPath = $request->file("trust_section.brands.{$index}.logo")->store('home-page/brands', 'public');
                    $validated['trust_section']['brands'][$index]['logo'] = '/storage/' . $logoPath;
                } elseif ($request->has("trust_section.brands.{$index}.existing_logo")) {
                    // Preserve existing logo
                    $validated['trust_section']['brands'][$index]['logo'] = $request->input("trust_section.brands.{$index}.existing_logo");
                }
                
                // Remove the existing_logo field from final data
                unset($validated['trust_section']['brands'][$index]['existing_logo']);
            }
            
            // Re-index the array
            $validated['trust_section']['brands'] = array_values($validated['trust_section']['brands']);
        }

        // Handle offer banner background image
        if ($request->hasFile('offer_banner.background_image')) {
            if ($homePage && isset($homePage->offer_banner['background_image'])) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $homePage->offer_banner['background_image']));
            }
            $bgImagePath = $request->file('offer_banner.background_image')->store('home-page/offer-banner', 'public');
            $validated['offer_banner']['background_image'] = '/storage/' . $bgImagePath;
        } elseif ($request->has('offer_banner.existing_background_image')) {
            $validated['offer_banner']['background_image'] = $request->input('offer_banner.existing_background_image');
        }

        return $validated;
    }
}
