<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPageSetting;
use Illuminate\Http\Request;

class PrivacyPageController extends Controller
{
    public function index()
    {
        $settings = PrivacyPageSetting::first() ?? new PrivacyPageSetting();
        
        return view('admin.privacy-page.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero' => 'nullable|array',
            'introduction' => 'nullable|array',
            'section_1' => 'nullable|array',
            'section_2' => 'nullable|array',
            'section_3' => 'nullable|array',
            'section_4' => 'nullable|array',
            'section_5' => 'nullable|array',
            'section_6' => 'nullable|array',
            'contact_section' => 'nullable|array',
        ]);

        // Convert textarea items to arrays
        if (isset($validated['section_1']['items']) && is_string($validated['section_1']['items'])) {
            $validated['section_1']['items'] = array_filter(explode("\n", $validated['section_1']['items']));
        }
        if (isset($validated['section_2']['items']) && is_string($validated['section_2']['items'])) {
            $validated['section_2']['items'] = array_filter(explode("\n", $validated['section_2']['items']));
        }
        if (isset($validated['section_3']['paragraphs']) && is_string($validated['section_3']['paragraphs'])) {
            $validated['section_3']['paragraphs'] = array_filter(explode("\n", $validated['section_3']['paragraphs']));
        }
        if (isset($validated['section_4']['paragraphs']) && is_string($validated['section_4']['paragraphs'])) {
            $validated['section_4']['paragraphs'] = array_filter(explode("\n", $validated['section_4']['paragraphs']));
        }
        if (isset($validated['section_5']['items']) && is_string($validated['section_5']['items'])) {
            $validated['section_5']['items'] = array_filter(explode("\n", $validated['section_5']['items']));
        }
        if (isset($validated['section_6']['paragraphs']) && is_string($validated['section_6']['paragraphs'])) {
            $validated['section_6']['paragraphs'] = array_filter(explode("\n", $validated['section_6']['paragraphs']));
        }

        $settings = PrivacyPageSetting::first();
        
        if ($settings) {
            $settings->update($validated);
        } else {
            PrivacyPageSetting::create($validated);
        }

        return redirect()->back()->with('success', 'Privacy page settings updated successfully!');
    }
}
