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
                'meta_title'       => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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
            $data = $request->except('logo');

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($content->logo && \Storage::disk('public')->exists($content->logo)) {
                    \Storage::disk('public')->delete($content->logo);
                }
                $data['logo'] = $request->file('logo')->store('about', 'public');
            }

            $content->update($data);

            return redirect()->route('admin.about-page.index')
                ->with('success', 'About page content updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.about-page.index')
                ->with('error', 'Error updating content: ' . $e->getMessage());
        }
    }
}
