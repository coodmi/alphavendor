<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportPageController extends Controller
{
    /**
     * Display the import page management form
     */
    public function index()
    {
        $content = ImportPageContent::getAllContent();
        return view('admin.import-page.index', compact('content'));
    }

    /**
     * Update the import page content
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
            $oldImage = ImportPageContent::where('key', 'hero_image')->first();
            $oldImagePath = $oldImage ? $oldImage->value : null;

            $imagePath = $request->file('hero_image')->store('import-page', 'public');

            ImportPageContent::updateOrCreate(
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
                ImportPageContent::updateOrCreate(
                    ['key' => $field],
                    ['value' => $validated[$field], 'type' => 'text']
                );
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Import page content updated successfully!');
    }
}
