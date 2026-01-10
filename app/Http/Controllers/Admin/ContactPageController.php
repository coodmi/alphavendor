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
     * Update the contact page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string|max:500',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'business_hours' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'sales_email' => 'required|email|max:255',
        ]);

        // Update all content fields
        foreach ($validated as $field => $value) {
            ContactPageContent::updateOrCreate(
                ['key' => $field],
                ['value' => $value, 'type' => 'text']
            );
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Contact page content updated successfully!');
    }
}
