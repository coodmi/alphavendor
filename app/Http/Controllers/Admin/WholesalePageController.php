<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WholesaleSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WholesalePageController extends Controller
{
    public function index()
    {
        $slides = WholesaleSlide::ordered()->get();
        return view('admin.wholesale-page.index', compact('slides'));
    }

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

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('wholesale-slides', 'public');
        }

        WholesaleSlide::create($validated);

        return redirect()->route('admin.wholesale-page')
            ->with('success', 'Wholesale slide added successfully!');
    }

    public function update(Request $request, WholesaleSlide $slide)
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

        if ($request->hasFile('image')) {
            if ($slide->image && !str_starts_with($slide->image, 'http')) {
                Storage::disk('public')->delete($slide->image);
            }
            $validated['image'] = $request->file('image')->store('wholesale-slides', 'public');
        }

        $slide->update($validated);

        return redirect()->route('admin.wholesale-page')
            ->with('success', 'Wholesale slide updated successfully!');
    }

    public function destroy(WholesaleSlide $slide)
    {
        if ($slide->image && !str_starts_with($slide->image, 'http')) {
            Storage::disk('public')->delete($slide->image);
        }

        $slide->delete();

        return redirect()->route('admin.wholesale-page')
            ->with('success', 'Wholesale slide deleted successfully!');
    }

    public function toggleStatus(WholesaleSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $slide->is_active
        ]);
    }
}
