<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPageContent;
use Illuminate\Http\Request;

class ContactPageContentController extends Controller
{
    public function index()
    {
        $content = ContactPageContent::getContent();
        return view('admin.contact-page.index', compact('content'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'meta_title'       => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'hero_title' => 'required|string|max:255',
                'hero_subtitle' => 'nullable|string',
                'address_title' => 'required|string|max:255',
                'address_line1' => 'nullable|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'address_line3' => 'nullable|string|max:255',
                'phone_title' => 'required|string|max:255',
                'phone_primary' => 'nullable|string|max:50',
                'phone_secondary' => 'nullable|string|max:50',
                'phone_hours' => 'nullable|string|max:255',
                'email_title' => 'required|string|max:255',
                'email_info' => 'nullable|email|max:255',
                'email_support' => 'nullable|email|max:255',
                'email_sales' => 'nullable|email|max:255',
                'hours_title' => 'required|string|max:255',
                'hours_weekdays' => 'nullable|string|max:255',
                'hours_weekdays_time' => 'nullable|string|max:255',
                'hours_weekend' => 'nullable|string|max:255',
                'form_title' => 'required|string|max:255',
                'map_title' => 'required|string|max:255',
                'map_embed_url' => 'nullable|url',
                'social_title' => 'required|string|max:255',
                'social_description' => 'nullable|string',
                'social_facebook' => 'nullable|url',
                'social_twitter' => 'nullable|url',
                'social_instagram' => 'nullable|url',
                'social_linkedin' => 'nullable|url',
                'social_youtube' => 'nullable|url',
                'faq_title' => 'required|string|max:255',
                'faq_subtitle' => 'nullable|string',
                'cta_title' => 'required|string|max:255',
                'cta_subtitle' => 'nullable|string',
                'cta_email_text' => 'nullable|string|max:100',
                'cta_email_link' => 'nullable|email|max:255',
                'cta_phone_text' => 'nullable|string|max:100',
                'cta_phone_link' => 'nullable|string|max:50',
            ]);

            $content = ContactPageContent::getContent();
            $content->update($request->all());

            return redirect()->route('admin.contact-page.index')
                ->with('success', 'Contact page content updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.contact-page.index')
                ->with('error', 'Error updating content: ' . $e->getMessage());
        }
    }
}
