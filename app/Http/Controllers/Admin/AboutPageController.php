<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    /**
     * Display the about page management form
     */
    public function index()
    {
        $content = AboutPageContent::getAllContent();
        return view('admin.about-page.index', compact('content'));
    }

    /**
     * Update the about page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string|max:500',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mission_title' => 'required|string|max:255',
            'mission_description' => 'required|string|max:1000',
            'vision_title' => 'required|string|max:255',
            'vision_description' => 'required|string|max:1000',
            'values_title' => 'required|string|max:255',
            'values_description' => 'required|string|max:1000',
        ]);

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            // Get old image path BEFORE updating
            $oldImage = AboutPageContent::where('key', 'hero_image')->first();
            $oldImagePath = $oldImage ? $oldImage->value : null;

            // Store new image first
            $imagePath = $request->file('hero_image')->store('about-page', 'public');

            // Update database with new image
            AboutPageContent::updateOrCreate(
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
            'mission_title', 'mission_description',
            'vision_title', 'vision_description',
            'values_title', 'values_description',
        ];

        foreach ($contentFields as $field) {
            if (isset($validated[$field])) {
                AboutPageContent::updateOrCreate(
                    ['key' => $field],
                    ['value' => $validated[$field], 'type' => 'text']
                );
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'About page content updated successfully!');
    }
}
