<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    /**
     * Get footer content
     */
    public function show()
    {
        $section = PageSection::where('page', 'footer')
            ->where('section_key', 'main')
            ->where('is_active', true)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Footer not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $section->content,
        ]);
    }

    /**
     * Update footer content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_info' => 'nullable|array',
            'company_info.name' => 'nullable|string|max:255',
            'company_info.description' => 'nullable|string',
            'contact' => 'nullable|array',
            'contact.phone' => 'nullable|string|max:20',
            'contact.email' => 'nullable|email',
            'contact.address' => 'nullable|string',
            'quick_links' => 'nullable|array',
            'quick_links.*.title' => 'nullable|string|max:255',
            'quick_links.*.url' => 'nullable|string|max:255',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'nullable|string|max:50',
            'social_links.*.url' => 'nullable|string|max:255',
            'copyright' => 'nullable|string',
            'logo' => 'nullable|string',
        ]);

        // Handle logo upload if file is provided
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            if ($logoFile->isValid()) {
                $uploadsPath = public_path('uploads/footer');
                if (!file_exists($uploadsPath)) {
                    mkdir($uploadsPath, 0755, true);
                }

                $filename = time() . '_logo_' . $logoFile->getClientOriginalName();
                $logoFile->move($uploadsPath, $filename);
                $validated['logo'] = '/uploads/footer/' . $filename;
            }
        }

        $section = PageSection::updateOrCreate(
            [
                'page' => 'footer',
                'section_key' => 'main',
            ],
            [
                'content' => $validated,
                'is_active' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Footer updated successfully',
            'data' => $section->content,
        ]);
    }

    /**
     * Get footer quick links
     */
    public function getQuickLinks()
    {
        $section = PageSection::where('page', 'footer')
            ->where('section_key', 'main')
            ->where('is_active', true)
            ->first();

        $quickLinks = $section?->content['quick_links'] ?? [];

        return response()->json([
            'success' => true,
            'data' => $quickLinks,
        ]);
    }

    /**
     * Get footer social links
     */
    public function getSocialLinks()
    {
        $section = PageSection::where('page', 'footer')
            ->where('section_key', 'main')
            ->where('is_active', true)
            ->first();

        $socialLinks = $section?->content['social_links'] ?? [];

        return response()->json([
            'success' => true,
            'data' => $socialLinks,
        ]);
    }

    /**
     * Get footer contact info
     */
    public function getContactInfo()
    {
        $section = PageSection::where('page', 'footer')
            ->where('section_key', 'main')
            ->where('is_active', true)
            ->first();

        $contact = $section?->content['contact'] ?? [];

        return response()->json([
            'success' => true,
            'data' => $contact,
        ]);
    }
}
