<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPageContent;
use Illuminate\Http\Request;

class AboutPageContentController extends Controller
{
    public function index()
    {
        $content = AboutPageContent::getContent();
        return view('admin.about-page.index', compact('content'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'hero_title' => 'required|string|max:255',
                'hero_subtitle' => 'nullable|string',
                'hero_cta_primary_text' => 'nullable|string|max:100',
                'hero_cta_primary_link' => 'nullable|string|max:255',
                'hero_cta_secondary_text' => 'nullable|string|max:100',
                'hero_cta_secondary_link' => 'nullable|string|max:255',
                'story_title' => 'required|string|max:255',
                'story_paragraph_1' => 'nullable|string',
                'story_paragraph_2' => 'nullable|string',
                'story_paragraph_3' => 'nullable|string',
                'mission_title' => 'required|string|max:255',
                'mission_content' => 'nullable|string',
                'vision_title' => 'required|string|max:255',
                'vision_content' => 'nullable|string',
                'stats_vendors' => 'nullable|string|max:50',
                'stats_products' => 'nullable|string|max:50',
                'stats_customers' => 'nullable|string|max:50',
                'stats_countries' => 'nullable|string|max:50',
                'cta_title' => 'required|string|max:255',
                'cta_subtitle' => 'nullable|string',
                'cta_primary_text' => 'nullable|string|max:100',
                'cta_primary_link' => 'nullable|string|max:255',
                'cta_secondary_text' => 'nullable|string|max:100',
                'cta_secondary_link' => 'nullable|string|max:255',
            ]);

            $content = AboutPageContent::getContent();
            $content->update($request->all());

            return redirect()->route('admin.about-page.index')
                ->with('success', 'About page content updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.about-page.index')
                ->with('error', 'Error updating content: ' . $e->getMessage());
        }
    }
}
