<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ManualPayment;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\VendorWallet;

class ManualPaymentController extends Controller
{
    /**
     * Display pending payments for admin.
     */
    public function index()
    {
        $pendingPayments = ManualPayment::with(['order.user', 'order.vendor', 'user'])
            ->pending()
            ->latest()
            ->paginate(20);

        $verifiedPayments = ManualPayment::with(['order.user', 'order.vendor', 'user', 'verifier'])
            ->verified()
            ->latest()
            ->take(10)
            ->get();

        $rejectedPayments = ManualPayment::with(['order.user', 'order.vendor', 'user', 'verifier'])
            ->rejected()
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending_count' => ManualPayment::pending()->count(),
            'pending_amount' => ManualPayment::pending()->sum('amount'),
            'verified_today' => ManualPayment::verified()->whereDate('verified_at', today())->count(),
            'verified_today_amount' => ManualPayment::verified()->whereDate('verified_at', today())->sum('amount'),
        ];

        return view('admin.manual-payments.index', compact('pendingPayments', 'verifiedPayments', 'rejectedPayments', 'stats'));
    }

    /**
     * Show a specific payment.
     */
    public function show(ManualPayment $manualPayment)
    {
        $manualPayment->load(['order.user', 'order.vendor', 'order.items', 'user', 'verifier']);
        
        return view('admin.manual-payments.show', compact('manualPayment'));
    }

    /**
     * Verify a payment.
     */
    public function verify(Request $request, ManualPayment $manualPayment)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        // Update payment status
        $manualPayment->update([
            'status' => 'verified',
            'admin_note' => $request->admin_note,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update order payment status
        $order = $manualPayment->order;
        $order->update([
            'payment_status' => 'paid',
        ]);

        // Update transaction status
        Transaction::where('order_id', $order->id)->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Payment verified successfully! Order #' . $order->order_number . ' is now marked as paid.');
    }

    /**
     * Reject a payment.
     */
    public function reject(Request $request, ManualPayment $manualPayment)
    {
        $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        // Update payment status
        $manualPayment->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update order payment status
        $manualPayment->order->update([
            'payment_status' => 'failed',
        ]);

        return redirect()->back()->with('success', 'Payment rejected. Customer has been notified.');
    }

    /**
     * Get payment settings (bKash/Nagad numbers).
     */
    public function settings()
    {
        return view('admin.manual-payments.settings');
    }

    /**
     * Update payment settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'bkash_number' => 'required|string|max:20',
            'nagad_number' => 'required|string|max:20',
            'rocket_number' => 'nullable|string|max:20',
            'bkash_name' => 'required|string|max:100',
            'nagad_name' => 'required|string|max:100',
            'rocket_name' => 'nullable|string|max:100',
        ]);

        // Store in database or config
        foreach ($request->except('_token') as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'payment_' . $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Payment settings updated successfully!');
    }
}
