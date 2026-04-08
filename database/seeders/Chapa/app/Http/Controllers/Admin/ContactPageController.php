<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPageSetting;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function index()
    {
        $contactPageSetting = ContactPageSetting::first() ?? new ContactPageSetting();
        
        return view('admin.contact-page.index', compact('contactPageSetting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            'hero_description' => 'nullable|string',
            'info_title' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'hours' => 'nullable|string|max:255',
            'map_embed_url' => 'nullable|string',
            'form_title' => 'required|string|max:255',
            'form_description' => 'nullable|string',
            'form_submit_email' => 'nullable|email|max:255',
        ]);

        $contactPageSetting = ContactPageSetting::first();
        
        if ($contactPageSetting) {
            $contactPageSetting->update($validated);
            $message = 'Contact page settings updated successfully!';
        } else {
            ContactPageSetting::create($validated);
            $message = 'Contact page settings created successfully!';
        }

        return redirect()->route('admin.contact-page.index')->with('success', $message);
    }
}
