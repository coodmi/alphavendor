<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoBannerController extends Controller
{
    /**
     * Display a listing of promo banners.
     */
    public function index()
    {
        $promoBanners = PromoBanner::ordered()->get();
        return view('admin.promo-banners.index', compact('promoBanners'));
    }

    /**
     * Store a newly created promo banner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'button_text', 'button_link', 'background_color', 'text_color', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['background_color'] = $data['background_color'] ?? '#FFA500';
        $data['text_color'] = $data['text_color'] ?? '#FFFFFF';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promo-banners', 'public');
        }

        PromoBanner::create($data);

        return redirect()->route('admin.promo-banners')->with('success', 'Promo banner created successfully!');
    }

    /**
     * Update the specified promo banner.
     */
    public function update(Request $request, PromoBanner $promoBanner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'subtitle', 'description', 'button_text', 'button_link', 'background_color', 'text_color', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            // Delete old image if exists and is not a URL
            if ($promoBanner->image && !str_starts_with($promoBanner->image, 'http') && Storage::disk('public')->exists($promoBanner->image)) {
                Storage::disk('public')->delete($promoBanner->image);
            }
            $data['image'] = $request->file('image')->store('promo-banners', 'public');
        }

        $promoBanner->update($data);

        return redirect()->route('admin.promo-banners')->with('success', 'Promo banner updated successfully!');
    }

    /**
     * Remove the specified promo banner.
     */
    public function destroy(PromoBanner $promoBanner)
    {
        // Delete image if exists and is not a URL
        if ($promoBanner->image && !str_starts_with($promoBanner->image, 'http') && Storage::disk('public')->exists($promoBanner->image)) {
            Storage::disk('public')->delete($promoBanner->image);
        }

        $promoBanner->delete();

        return redirect()->route('admin.promo-banners')->with('success', 'Promo banner deleted successfully!');
    }
}
