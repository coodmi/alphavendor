<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicesPageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesPageController extends Controller
{
    /**
     * Display services page management dashboard
     */
    public function index()
    {
        $hero = ServicesPageSetting::getSection('hero');
        $intro = ServicesPageSetting::getSection('intro');
        $features = ServicesPageSetting::getSection('features');
        $stats = ServicesPageSetting::getSection('stats');

        return view('admin.services-page.index', compact('hero', 'intro', 'features', 'stats'));
    }

    /**
     * Update services page settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Hero Section
            'hero_badge_text' => 'nullable|string|max:255',
            'hero_title' => 'required|string|max:500',
            'hero_subtitle' => 'nullable|string|max:1000',
            'hero_cta_text' => 'nullable|string|max:100',
            'hero_cta_url' => 'nullable|string|max:255',
            'hero_badges' => 'nullable|array',
            'hero_badges.*.icon' => 'nullable|string|max:100',
            'hero_badges.*.text' => 'nullable|string|max:100',
            
            // Intro Section
            'intro_badge_text' => 'nullable|string|max:255',
            'intro_title' => 'nullable|string|max:500',
            'intro_description' => 'nullable|string|max:1000',
            
            // Features Section
            'features_badge_text' => 'nullable|string|max:255',
            'features_title' => 'nullable|string|max:500',
            'features_subtitle' => 'nullable|string|max:500',
            'features' => 'nullable|array',
            'features.*.title' => 'nullable|string|max:255',
            'features.*.description' => 'nullable|string|max:500',
            'features.*.icon' => 'nullable|string|max:100',
            'features.*.color' => 'nullable|string|max:50',
            
            // Stats Section
            'stats' => 'nullable|array',
            'stats.*.value' => 'nullable|string|max:50',
            'stats.*.label' => 'nullable|string|max:100',
        ]);

        // Update Hero Section
        $heroData = [
            'badge_text' => $validated['hero_badge_text'] ?? 'পেশাদার প্রিন্টিং সেবা',
            'title' => $validated['hero_title'],
            'subtitle' => $validated['hero_subtitle'] ?? '',
            'cta_text' => $validated['hero_cta_text'] ?? 'সেবা দেখুন',
            'cta_url' => $validated['hero_cta_url'] ?? '#services',
            'badges' => $validated['hero_badges'] ?? [],
        ];
        ServicesPageSetting::updateSection('hero', $heroData);

        // Update Intro Section
        $introData = [
            'badge_text' => $validated['intro_badge_text'] ?? 'আমাদের সেবাসমূহ',
            'title' => $validated['intro_title'] ?? 'পেশাদার প্রিন্টিং সেবা',
            'description' => $validated['intro_description'] ?? '',
        ];
        ServicesPageSetting::updateSection('intro', $introData);

        // Update Features Section
        $featuresData = [
            'badge_text' => $validated['features_badge_text'] ?? 'কেন আমাদের বেছে নিবেন?',
            'title' => $validated['features_title'] ?? 'আমাদের সেবার বিশেষত্ব',
            'subtitle' => $validated['features_subtitle'] ?? 'পেশাদার মান এবং সেবা যার উপর আপনি আস্থা রাখতে পারেন',
            'features' => $validated['features'] ?? [],
        ];
        ServicesPageSetting::updateSection('features', $featuresData);

        // Update Stats Section
        $statsData = [
            'stats' => $validated['stats'] ?? [],
        ];
        ServicesPageSetting::updateSection('stats', $statsData);

        return redirect()->route('admin.services-page.index')
            ->with('success', 'Services page updated successfully!');
    }
}
