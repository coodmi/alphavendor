<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WholesalePageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WholesalePageController extends Controller
{
    /**
     * Display the wholesale page management form
     */
    public function index()
    {
        $content = WholesalePageContent::getAllContent();
        return view('admin.wholesale-page.index', compact('content'));
    }

    /**
     * Update the wholesale page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string|max:500',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            $oldImage = WholesalePageContent::where('key', 'hero_image')->first();
            $oldImagePath = $oldImage ? $oldImage->value : null;

            $imagePath = $request->file('hero_image')->store('wholesale-page', 'public');

            WholesalePageContent::updateOrCreate(
                ['key' => 'hero_image'],
                ['value' => $imagePath, 'type' => 'image']
            );

            if ($oldImagePath && !str_starts_with($oldImagePath, 'http')) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        // Update text content
        $contentFields = ['hero_title', 'hero_description'];

        foreach ($contentFields as $field) {
            if (isset($validated[$field])) {
                WholesalePageContent::updateOrCreate(
                    ['key' => $field],
                    ['value' => $validated[$field], 'type' => 'text']
                );
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Wholesale page content updated successfully!');
    }
}
