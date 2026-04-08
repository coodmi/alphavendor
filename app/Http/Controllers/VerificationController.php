<?php

namespace App\Http\Controllers;

use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Show verification page
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect if user doesn't need verification
        if (!$user->needsVerification()) {
            return redirect()->back()->with('info', 'Your account type does not require verification.');
        }

        $documents = $user->verificationDocuments()->get()->keyBy('document_type');
        $requiredDocuments = $user->getRequiredDocumentTypes();

        return view('verification.index', compact('documents', 'requiredDocuments'));
    }

    /**
     * Upload verification document
     */
    public function upload(Request $request)
    {
        $user = auth()->user();

        if (!$user->needsVerification()) {
            return response()->json(['success' => false, 'message' => 'Verification not required'], 400);
        }

        $request->validate([
            'document_type' => 'required|in:nid_front,nid_back,trade_license,personal_photo,shop_profile',
            'document' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        // Check if document type is required for this role
        if (!in_array($request->document_type, $user->getRequiredDocumentTypes())) {
            return response()->json(['success' => false, 'message' => 'This document is not required for your account type'], 400);
        }

        try {
            $file = $request->file('document');
            $filename = time() . '_' . $user->id . '_' . $request->document_type . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('verification_documents', $filename, 'public');

            // Delete old document if exists
            $existingDoc = $user->verificationDocuments()->where('document_type', $request->document_type)->first();
            if ($existingDoc) {
                Storage::disk('public')->delete($existingDoc->file_path);
                $existingDoc->delete();
            }

            // Create new document record
            VerificationDocument::create([
                'user_id' => $user->id,
                'document_type' => $request->document_type,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Document upload failed', [
                'user_id' => $user->id,
                'document_type' => $request->document_type,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete verification document
     */
    public function delete($documentId)
    {
        $user = auth()->user();
        $document = VerificationDocument::where('user_id', $user->id)->findOrFail($documentId);

        // Don't allow deletion if verification is pending or approved
        if ($user->verification_status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete documents while verification is pending'
            ], 400);
        }

        try {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document'
            ], 500);
        }
    }

    /**
     * Submit verification for review
     */
    public function submit(Request $request)
    {
        $user = auth()->user();

        if (!$user->needsVerification()) {
            return redirect()->back()->with('error', 'Verification not required for your account type.');
        }

        // Check if all required documents are uploaded
        if (!$user->hasAllRequiredDocuments()) {
            return redirect()->back()->with('error', 'Please upload all required documents before submitting.');
        }

        // Update user verification status
        $user->update([
            'verification_status' => 'pending',
            'verification_submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Verification submitted successfully! Please wait for admin approval.');
    }
}
