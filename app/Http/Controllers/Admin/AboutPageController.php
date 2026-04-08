<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPageContent;
use Illuminate\Http\Request;

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
     * Update about page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            'hero_cta_primary_text' => 'nullable|string|max:100',
            'hero_cta_primary_link' => 'nullable|string|max:255',
            'hero_cta_secondary_text' => 'nullable|string|max:100',
            'hero_cta_secondary_link' => 'nullable|string|max:255',
            
            'story_title' => 'required|string|max:255',
            'story_paragraph_1' => 'required|string',
            'story_paragraph_2' => 'required|string',
            'story_paragraph_3' => 'required|string',
            
            'stats_vendors' => 'required|string|max:50',
            'stats_products' => 'required|string|max:50',
            'stats_customers' => 'required|string|max:50',
            'stats_countries' => 'required|string|max:50',
            
            'mission_title' => 'required|string|max:255',
            'mission_content' => 'required|string',
            'vision_title' => 'required|string|max:255',
            'vision_content' => 'required|string',
            
            'cta_title' => 'required|string|max:255',
            'cta_subtitle' => 'required|string|max:500',
            'cta_primary_text' => 'nullable|string|max:100',
            'cta_primary_link' => 'nullable|string|max:255',
            'cta_secondary_text' => 'nullable|string|max:100',
            'cta_secondary_link' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            AboutPageContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.about-page')
            ->with('success', 'About page content updated successfully!');
    }
}
