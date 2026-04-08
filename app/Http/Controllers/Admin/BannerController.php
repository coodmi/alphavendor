<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of banners.
     */
    public function index()
    {
        $banners = Banner::with('specialOffer')->ordered()->get();
        $offers = \App\Models\SpecialOffer::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners', 'offers'));
    }

    /**
     * Store a newly created banner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'special_offer_id' => 'nullable|exists:special_offers,id',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'link', 'special_offer_id', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners')->with('success', 'Banner created successfully!');
    }

    /**
     * Update the specified banner.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'special_offer_id' => 'nullable|exists:special_offers,id',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only(['title', 'link', 'special_offer_id', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            // Delete old image if exists and is not a URL
            if ($banner->image && !str_starts_with($banner->image, 'http') && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners')->with('success', 'Banner updated successfully!');
    }

    /**
     * Remove the specified banner.
     */
    public function destroy(Banner $banner)
    {
        // Delete image if exists and is not a URL
        if ($banner->image && !str_starts_with($banner->image, 'http') && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners')->with('success', 'Banner deleted successfully!');
    }
}
