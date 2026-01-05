<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleApplication;
use Illuminate\Support\Facades\Auth;

class RoleApplicationController extends Controller
{
    /**
     * Show role application form
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user already has a pending application
        if ($user->pendingRoleApplication) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You already have a pending role application.');
        }

        // Check if user already has a vendor role
        if (in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'admin'])) {
            return redirect()->route('dashboard')
                ->with('error', 'You already have a vendor role.');
        }

        return view('role-applications.create');
    }

    /**
     * Store a new role application
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user already has a pending application
        if ($user->pendingRoleApplication) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You already have a pending role application.');
        }

        $validated = $request->validate([
            'requested_role' => 'required|in:retailer,wholesaler,exporter',
            'reason' => 'required|string|min:20|max:1000',
        ]);

        RoleApplication::create([
            'user_id' => $user->id,
            'requested_role' => $validated['requested_role'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Your role application has been submitted successfully!');
    }

    /**
     * Show all role applications (Admin only)
     */
    public function index()
    {
        $applications = RoleApplication::with('user')->latest()->paginate(15);
        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Show specific application details (Admin only)
     */
    public function show(RoleApplication $application)
    {
        $application->load('user', 'reviewer');
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Approve role application (Admin only)
     */
    public function approve(Request $request, RoleApplication $application)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $application->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Update user role
        $application->user->update([
            'role' => $application->requested_role,
        ]);

        return redirect()->route('admin.applications')
            ->with('success', 'Application approved successfully!');
    }

    /**
     * Reject role application (Admin only)
     */
    public function reject(Request $request, RoleApplication $application)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $application->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.applications')
            ->with('success', 'Application rejected.');
    }
}
