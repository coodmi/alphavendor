<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    /**
     * Display the home page management form
     */
    public function index()
    {
        $slides = HeroSlide::ordered()->get();
        return view('admin.home-page.index', compact('slides'));
    }

    /**
     * Store a new hero slide
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        HeroSlide::create($validated);

        return redirect()->route('admin.home-page')
            ->with('success', 'Hero slide added successfully!');
    }

    /**
     * Update an existing hero slide
     */
    public function update(Request $request, HeroSlide $slide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slide->image && !str_starts_with($slide->image, 'http')) {
                Storage::disk('public')->delete($slide->image);
            }
            $validated['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        $slide->update($validated);

        return redirect()->route('admin.home-page')
            ->with('success', 'Hero slide updated successfully!');
    }

    /**
     * Delete a hero slide
     */
    public function destroy(HeroSlide $slide)
    {
        // Delete image
        if ($slide->image && !str_starts_with($slide->image, 'http')) {
            Storage::disk('public')->delete($slide->image);
        }

        $slide->delete();

        return redirect()->route('admin.home-page')
            ->with('success', 'Hero slide deleted successfully!');
    }

    /**
     * Toggle slide active status
     */
    public function toggleStatus(HeroSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $slide->is_active
        ]);
    }
}
