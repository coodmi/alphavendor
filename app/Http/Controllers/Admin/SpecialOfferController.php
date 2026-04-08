<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpecialOfferController extends Controller
{
    public function index()
    {
        $offers = SpecialOffer::ordered()->get();
        return view('admin.special-offers.index', compact('offers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('special-offers', 'public');
        }

        SpecialOffer::create($validated);

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Special offer created successfully!');
    }

    public function update(Request $request, SpecialOffer $specialOffer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($specialOffer->image && !str_starts_with($specialOffer->image, 'http')) {
                Storage::disk('public')->delete($specialOffer->image);
            }
            $validated['image'] = $request->file('image')->store('special-offers', 'public');
        }

        $specialOffer->update($validated);

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Special offer updated successfully!');
    }

    public function destroy(SpecialOffer $specialOffer)
    {
        // Delete image if exists
        if ($specialOffer->image && !str_starts_with($specialOffer->image, 'http')) {
            Storage::disk('public')->delete($specialOffer->image);
        }

        $specialOffer->delete();

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Special offer deleted successfully!');
    }

    /**
     * Show products for a specific special offer (Public page)
     */
    public function show($slug)
    {
        $offer = SpecialOffer::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get products assigned to this offer
        $products = \App\Models\Product::where('special_offer_id', $offer->id)
            ->where('status', 'active')
            ->with(['category', 'vendor', 'brand'])
            ->paginate(12);

        return view('special-offers.show', compact('offer', 'products'));
    }
}
