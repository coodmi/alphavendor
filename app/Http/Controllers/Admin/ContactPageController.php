<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPageContent;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    /**
     * Display the contact page management form
     */
    public function index()
    {
        $content = ContactPageContent::getAllContent();
        return view('admin.contact-page.index', compact('content'));
    }

    /**
     * Update contact page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:500',
            
            'address_title' => 'required|string|max:100',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'address_line3' => 'nullable|string|max:255',
            
            'phone_title' => 'required|string|max:100',
            'phone_primary' => 'required|string|max:50',
            'phone_secondary' => 'nullable|string|max:50',
            'phone_hours' => 'nullable|string|max:100',
            
            'email_title' => 'required|string|max:100',
            'email_info' => 'required|email|max:100',
            'email_support' => 'nullable|email|max:100',
            'email_sales' => 'nullable|email|max:100',
            
            'hours_title' => 'required|string|max:100',
            'hours_weekdays' => 'required|string|max:100',
            'hours_weekdays_time' => 'required|string|max:100',
            'hours_weekend' => 'nullable|string|max:100',
            
            'form_title' => 'required|string|max:255',
            
            'map_title' => 'required|string|max:255',
            'map_embed_url' => 'required|string',
            
            'social_title' => 'required|string|max:255',
            'social_description' => 'required|string|max:500',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            
            'faq_title' => 'required|string|max:255',
            'faq_subtitle' => 'required|string|max:500',
            
            'cta_title' => 'required|string|max:255',
            'cta_subtitle' => 'required|string|max:500',
            'cta_email_text' => 'nullable|string|max:100',
            'cta_email_link' => 'nullable|email|max:100',
            'cta_phone_text' => 'nullable|string|max:100',
            'cta_phone_link' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            ContactPageContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.contact-page')
            ->with('success', 'Contact page content updated successfully!');
    }
}
