<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display offers management
     */
    public function index()
    {
        $offers = Offer::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Store a new offer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Offer::create($validated);

        return redirect()->back()->with('success', 'Offer created successfully!');
    }

    /**
     * Update an existing offer
     */
    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $offer->update($validated);

        return redirect()->back()->with('success', 'Offer updated successfully!');
    }

    /**
     * Delete an offer
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        
        // Check if any products are using this offer
        if ($offer->products()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete offer that is assigned to products!');
        }

        $offer->delete();

        return redirect()->back()->with('success', 'Offer deleted successfully!');
    }
}
