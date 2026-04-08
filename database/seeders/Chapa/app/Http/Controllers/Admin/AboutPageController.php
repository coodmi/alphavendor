<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPageSetting;
use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function index()
    {
        $aboutPageSetting = AboutPageSetting::first() ?? new AboutPageSetting();
        
        return view('admin.about-page.index', compact('aboutPageSetting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_badge' => 'required|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            'hero_description' => 'nullable|string',
            'story_badge' => 'required|string|max:255',
            'story_title' => 'required|string|max:500',
            'story_content' => 'nullable|string',
            'story_image' => 'nullable|string',
            'mission_text' => 'nullable|string',
            'vision_text' => 'nullable|string',
            'cta_title' => 'required|string|max:255',
            'cta_description' => 'nullable|string',
        ]);

        // Handle hero stats
        if ($request->has('hero_stats')) {
            $validated['hero_stats'] = array_values(array_filter($request->hero_stats, function($stat) {
                return !empty($stat['value']) && !empty($stat['label']);
            }));
        }

        // Handle why choose us
        if ($request->has('why_choose_us')) {
            $validated['why_choose_us'] = array_values(array_filter($request->why_choose_us, function($reason) {
                return !empty($reason['title']) && !empty($reason['description']);
            }));
        }

        $aboutPageSetting = AboutPageSetting::first();
        
        if ($aboutPageSetting) {
            $aboutPageSetting->update($validated);
            $message = 'About page settings updated successfully!';
        } else {
            AboutPageSetting::create($validated);
            $message = 'About page settings created successfully!';
        }

        return redirect()->route('admin.about-page.index')->with('success', $message);
    }
}
