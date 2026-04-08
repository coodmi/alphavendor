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
            'requested_role' => 'required|in:retailer,wholesaler,exporter,employee',
            'reason' => 'required|string|min:20|max:1000',

            // Business Information
            'business_name' => 'required|string|max:255',
            'business_registration_number' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'business_type' => 'required|in:sole_proprietorship,partnership,llc,corporation,other',
            'years_in_business' => 'required|integer|min:0|max:100',

            // Contact Information
            'business_phone' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',

            // Address Information
            'business_address' => 'required|string|max:1000',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',

            // Business Details
            'business_description' => 'required|string|min:50|max:2000',
            'product_categories' => 'nullable|string|max:500',
            'estimated_monthly_sales' => 'nullable|numeric|min:0',
        ]);

        RoleApplication::create([
            'user_id' => $user->id,
            'requested_role' => $validated['requested_role'],
            'reason' => $validated['reason'],
            'business_name' => $validated['business_name'],
            'business_registration_number' => $validated['business_registration_number'] ?? null,
            'tax_id' => $validated['tax_id'] ?? null,
            'business_type' => $validated['business_type'],
            'years_in_business' => $validated['years_in_business'],
            'business_phone' => $validated['business_phone'],
            'business_email' => $validated['business_email'],
            'website' => $validated['website'] ?? null,
            'business_address' => $validated['business_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'],
            'business_description' => $validated['business_description'],
            'product_categories' => $validated['product_categories'] ?? null,
            'estimated_monthly_sales' => $validated['estimated_monthly_sales'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Your vendor application has been submitted successfully! We will review it shortly.');
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
