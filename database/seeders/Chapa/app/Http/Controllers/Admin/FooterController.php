<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FooterController extends Controller
{
    public function index()
    {
        $footerSetting = FooterSetting::getActive() ?? new FooterSetting();
        return view('admin.footer.index', compact('footerSetting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_links' => 'nullable|array',
            'service_links.*.title' => 'required_with:service_links|string|max:255',
            'service_links.*.url' => 'required_with:service_links|string|max:255',
            'quick_links' => 'nullable|array',
            'quick_links.*.title' => 'required_with:quick_links|string|max:255',
            'quick_links.*.url' => 'required_with:quick_links|string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links|string|max:50',
            'social_links.*.url' => 'required_with:social_links|url|max:255',
            'newsletter_title' => 'required|string|max:255',
            'newsletter_description' => 'required|string',
            'copyright_text' => 'nullable|string',
            'developer_name' => 'required|string|max:255',
            'developer_url' => 'required|url|max:255',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('footer', 'public');
            $validated['logo'] = '/storage/' . $logoPath;
        }

        // Filter out empty links
        if (isset($validated['service_links'])) {
            $validated['service_links'] = array_filter($validated['service_links'], function($link) {
                return !empty($link['title']) && !empty($link['url']);
            });
        }

        if (isset($validated['quick_links'])) {
            $validated['quick_links'] = array_filter($validated['quick_links'], function($link) {
                return !empty($link['title']) && !empty($link['url']);
            });
        }

        if (isset($validated['social_links'])) {
            $validated['social_links'] = array_filter($validated['social_links'], function($link) {
                return !empty($link['platform']) && !empty($link['url']);
            });
        }

        // Deactivate all existing footer settings
        FooterSetting::query()->update(['is_active' => false]);

        // Create new active footer setting
        $validated['is_active'] = true;
        FooterSetting::create($validated);

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer settings updated successfully!');
    }

    public function update(Request $request, FooterSetting $footer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'service_links' => 'nullable|array',
            'service_links.*.title' => 'required_with:service_links|string|max:255',
            'service_links.*.url' => 'required_with:service_links|string|max:255',
            'quick_links' => 'nullable|array',
            'quick_links.*.title' => 'required_with:quick_links|string|max:255',
            'quick_links.*.url' => 'required_with:quick_links|string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links|string|max:50',
            'social_links.*.url' => 'required_with:social_links|url|max:255',
            'newsletter_title' => 'required|string|max:255',
            'newsletter_description' => 'required|string',
            'copyright_text' => 'nullable|string',
            'developer_name' => 'required|string|max:255',
            'developer_url' => 'required|url|max:255',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($footer->logo && Storage::disk('public')->exists(str_replace('/storage/', '', $footer->logo))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $footer->logo));
            }
            
            $logoPath = $request->file('logo')->store('footer', 'public');
            $validated['logo'] = '/storage/' . $logoPath;
        }

        // Filter out empty links
        if (isset($validated['service_links'])) {
            $validated['service_links'] = array_filter($validated['service_links'], function($link) {
                return !empty($link['title']) && !empty($link['url']);
            });
        }

        if (isset($validated['quick_links'])) {
            $validated['quick_links'] = array_filter($validated['quick_links'], function($link) {
                return !empty($link['title']) && !empty($link['url']);
            });
        }

        if (isset($validated['social_links'])) {
            $validated['social_links'] = array_filter($validated['social_links'], function($link) {
                return !empty($link['platform']) && !empty($link['url']);
            });
        }

        $footer->update($validated);

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer settings updated successfully!');
    }
}
