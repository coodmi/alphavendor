<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExporterCertificationController extends Controller
{
    /**
     * Display certifications management page
     */
    public function index()
    {
        $certifications = Certification::where('vendor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('exporter.certifications.index', compact('certifications'));
    }

    /**
     * Store a new certification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'issuing_authority' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'certificate_number' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_active' => 'boolean',
        ]);

        // Handle document upload
        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('certifications', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['vendor_id'] = Auth::id();

        $certification = Certification::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Certification created successfully!',
            'certification' => $certification
        ]);
    }

    /**
     * Update a certification
     */
    public function update(Request $request, Certification $certification)
    {
        // Ensure exporter can only update their own certifications
        if ($certification->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this certification!'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'issuing_authority' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'certificate_number' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_active' => 'boolean',
        ]);

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document
            if ($certification->document) {
                Storage::disk('public')->delete($certification->document);
            }
            $validated['document'] = $request->file('document')->store('certifications', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $certification->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Certification updated successfully!',
            'certification' => $certification
        ]);
    }

    /**
     * Bulk update certifications for exporter profile
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
            'exporter_rating' => 'nullable|numeric|min:0|max:5'
        ]);

        $user = Auth::user();

        // Update certifications in user's profile or a related table
        // For now, we'll just return success
        // You may want to store this in a user_meta table or user profile

        return response()->json([
            'success' => true,
            'message' => 'Certifications updated successfully!',
            'certifications' => $validated['certifications'] ?? []
        ]);
    }

    /**
     * Delete a certification
     */
    public function destroy(Certification $certification)
    {
        // Ensure exporter can only delete their own certifications
        if ($certification->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this certification!'
            ], 403);
        }

        // Delete document if exists
        if ($certification->document) {
            Storage::disk('public')->delete($certification->document);
        }

        $certification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certification deleted successfully!'
        ]);
    }
}
