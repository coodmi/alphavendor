<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;

class VerificationManagementController extends Controller
{
    /**
     * Display verification requests
     */
    public function index(Request $request)
    {
        $query = User::with(['verificationDocuments', 'verificationReviewer'])
            ->whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer']);

        // Filter by verification status
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        // Sort by submission date
        $query->orderByRaw("
            CASE 
                WHEN verification_status = 'pending' THEN 1
                WHEN verification_status = 'unverified' THEN 2
                WHEN verification_status = 'verified' THEN 3
                WHEN verification_status = 'rejected' THEN 4
            END
        ")->orderBy('verification_submitted_at', 'desc');

        $users = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])->count(),
            'pending' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where('verification_status', 'pending')->count(),
            'verified' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where('verification_status', 'verified')->count(),
            'rejected' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where('verification_status', 'rejected')->count(),
            'unverified' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where('verification_status', 'unverified')->count(),
        ];

        return view('admin.verification.index', compact('users', 'stats'));
    }

    /**
     * Show verification details
     */
    public function show($userId)
    {
        $user = User::with(['verificationDocuments', 'verificationReviewer', 'roleApplications'])
            ->findOrFail($userId);

        if (!$user->needsVerification()) {
            return redirect()->back()->with('error', 'This user does not require verification.');
        }

        return view('admin.verification.show', compact('user'));
    }

    /**
     * Approve verification
     */
    public function approve(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        if (!$user->needsVerification()) {
            return redirect()->back()->with('error', 'This user does not require verification.');
        }

        $user->update([
            'verification_status' => 'verified',
            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
            'rejection_reason' => null,
            'status' => 'active',
        ]);

        // Update all documents to approved
        $user->verificationDocuments()->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'User verification approved successfully!');
    }

    /**
     * Reject verification
     */
    public function reject(Request $request, $userId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($userId);

        if (!$user->needsVerification()) {
            return redirect()->back()->with('error', 'This user does not require verification.');
        }

        $user->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Update all documents to rejected
        $user->verificationDocuments()->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'User verification rejected.');
    }

    /**
     * Reset verification (allow resubmission)
     */
    public function reset($userId)
    {
        $user = User::findOrFail($userId);

        if (!$user->needsVerification()) {
            return redirect()->back()->with('error', 'This user does not require verification.');
        }

        $user->update([
            'verification_status' => 'unverified',
            'verification_submitted_at' => null,
            'verification_reviewed_at' => null,
            'verification_reviewed_by' => null,
            'rejection_reason' => null,
        ]);

        // Delete all documents to allow fresh upload
        foreach ($user->verificationDocuments as $doc) {
            \Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        return redirect()->back()->with('success', 'Verification reset successfully. User can resubmit documents.');
    }
}
