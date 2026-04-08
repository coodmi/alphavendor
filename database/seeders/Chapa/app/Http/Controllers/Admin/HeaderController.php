<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeaderController extends Controller
{
    public function index()
    {
        $headerSetting = HeaderSetting::getActive() ?? new HeaderSetting();
        return view('admin.header.index', compact('headerSetting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'search_placeholder' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'navigation_links' => 'nullable|array',
            'navigation_links.*.title' => 'required_with:navigation_links|string|max:255',
            'navigation_links.*.url' => 'required_with:navigation_links|string|max:255',
            'navigation_links.*.pattern' => 'nullable|string|max:255',
            'login_url' => 'required|string|max:255',
            'register_url' => 'required|string|max:255',
            'cart_url' => 'required|string|max:255',
            'show_search' => 'boolean',
            'show_phone' => 'boolean',
            'show_help' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('header', 'public');
            $validated['logo'] = '/storage/' . $logoPath;
        }

        // Filter out empty navigation links
        if (isset($validated['navigation_links'])) {
            $validated['navigation_links'] = array_filter($validated['navigation_links'], function($link) {
                return !empty($link['title']) && !empty($link['url']);
            });
        }

        // Set boolean values
        $validated['show_search'] = $request->has('show_search');
        $validated['show_phone'] = $request->has('show_phone');
        $validated['show_help'] = $request->has('show_help');

        // Deactivate all existing header settings
        HeaderSetting::query()->update(['is_active' => false]);

        // Create new active header setting
        $validated['is_active'] = true;
        HeaderSetting::create($validated);

        return redirect()->route('admin.header.index')
            ->with('success', 'Header settings updated successfully!');
    }

    public function update(Request $request, HeaderSetting $header)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'search_placeholder' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'navigation_links' => 'nullable|array',
            'navigation_links.*.title' => 'required_with:navigation_links|string|max:255',
            'navigation_links.*.url' => 'required_with:navigation_links|string|max:255',
            'navigation_links.*.pattern' => 'nullable|string|max:255',
            'login_url' => 'required|string|max:255',
            'register_url' => 'required|string|max:255',
            'cart_url' => 'required|string|max:255',
            'show_search' => 'boolean',
            'show_phone' => 'boolean',
            'show_help' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($header->logo && Storage::disk('public')->exists(str_replace('/storage/', '', $header->logo))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $header->logo));
            }
            
            $logoPath = $request->file('logo')->store('header', 'public');
            $validated['logo'] = '/storage/' . $logoPath;
        }

        // Filter out empty navigation links
        if (isset($validated['navigation_links'])) {
            $validated['navigation_links'] = array_filter($validated['navigation_links'], function($link) {
                return !empty($link['title']) && !empty($link['url']);
            });
        }

        // Set boolean values
        $validated['show_search'] = $request->has('show_search');
        $validated['show_phone'] = $request->has('show_phone');
        $validated['show_help'] = $request->has('show_help');

        $header->update($validated);

        return redirect()->route('admin.header.index')
            ->with('success', 'Header settings updated successfully!');
    }
}
