<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RetailPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RetailPageController extends Controller
{
    /**
     * Display the retail page management form
     */
    public function index()
    {
        $content = RetailPageContent::getAllContent();
        return view('admin.retail-page.index', compact('content'));
    }

    /**
     * Update the retail page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string|max:500',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stat1_number' => 'required|string|max:20',
            'stat1_label' => 'required|string|max:100',
            'stat2_number' => 'required|string|max:20',
            'stat2_label' => 'required|string|max:100',
            'stat3_number' => 'required|string|max:20',
            'stat3_label' => 'required|string|max:100',
        ]);

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            // Get old image path BEFORE updating
            $oldImage = RetailPageContent::where('key', 'hero_image')->first();
            $oldImagePath = $oldImage ? $oldImage->value : null;

            // Store new image first
            $imagePath = $request->file('hero_image')->store('retail-page', 'public');

            // Update database with new image
            RetailPageContent::updateOrCreate(
                ['key' => 'hero_image'],
                ['value' => $imagePath, 'type' => 'image']
            );

            // Delete old image AFTER successfully saving the new one
            if ($oldImagePath && !str_starts_with($oldImagePath, 'http')) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        // Update text content
        $contentFields = [
            'hero_title', 'hero_description',
            'stat1_number', 'stat1_label',
            'stat2_number', 'stat2_label',
            'stat3_number', 'stat3_label',
        ];

        foreach ($contentFields as $field) {
            if (isset($validated[$field])) {
                RetailPageContent::updateOrCreate(
                    ['key' => $field],
                    ['value' => $validated[$field], 'type' => 'text']
                );
            }
        }

        return redirect()->route('admin.retail-page')
            ->with('success', 'Retail page content updated successfully!');
    }
}
