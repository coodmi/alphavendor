<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\VendorWallet;

class ExporterDashboardController extends Controller
{
    /**
     * Show exporter dashboard
     */
    public function index()
    {
        $vendorId = Auth::id();

        // Get or create wallet
        $wallet = VendorWallet::firstOrCreate(
            ['vendor_id' => $vendorId],
            ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Get statistics
        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $totalOrders = Order::where('vendor_id', $vendorId)->count();
        $pendingOrders = Order::where('vendor_id', $vendorId)->where('status', 'pending')->count();
        $recentOrders = Order::where('vendor_id', $vendorId)
            ->with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        // Get certifications from Certification model
        $certifications = \App\Models\Certification::where('vendor_id', $vendorId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboards.exporter', compact('wallet', 'totalProducts', 'totalOrders', 'pendingOrders', 'recentOrders', 'certifications'));
    }

    /**
     * Update exporter certifications
     */
    public function updateCertifications(Request $request)
    {
        $validated = $request->validate([
            'certifications' => 'nullable|array',
            'certifications.*' => 'string|in:iso_certified,ce_certified,fda_approved,export_license',
            'exporter_rating' => 'nullable|numeric|min:0|max:5'
        ]);

        $user = Auth::user();
        $user->certifications = $validated['certifications'] ?? [];
        $user->exporter_rating = $validated['exporter_rating'] ?? null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Certifications updated successfully!',
            'certifications' => $user->certifications,
            'exporter_rating' => $user->exporter_rating
        ]);
    }
}
