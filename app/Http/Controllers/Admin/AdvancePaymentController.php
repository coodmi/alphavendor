<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\Order;
use Illuminate\Http\Request;

class AdvancePaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = AdvancePayment::with(['user', 'product', 'vendor']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('contact_number', 'like', "%{$search}%")
                ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(20);

        $stats = [
            'total' => AdvancePayment::count(),
            'pending' => AdvancePayment::where('status', 'pending')->count(),
            'approved' => AdvancePayment::where('status', 'approved')->count(),
            'paid' => AdvancePayment::where('status', 'paid')->count(),
            'completed' => AdvancePayment::where('status', 'completed')->count(),
            'total_amount' => AdvancePayment::whereIn('status', ['paid', 'completed'])->sum('advance_amount'),
        ];

        return view('admin.advance-payments.index', compact('payments', 'stats'));
    }

    public function show(AdvancePayment $advancePayment)
    {
        $advancePayment->load(['user', 'product', 'vendor']);
        return view('admin.advance-payments.show', compact('advancePayment'));
    }

    public function updateStatus(Request $request, AdvancePayment $advancePayment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $data = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $advancePayment->admin_notes,
        ];

        if ($validated['status'] === 'approved' && !$advancePayment->approved_at) {
            $data['approved_at'] = now();
        }

        if ($validated['status'] === 'paid' && !$advancePayment->paid_at) {
            $data['paid_at'] = now();
        }

        if (isset($validated['transaction_id'])) {
            $data['transaction_id'] = $validated['transaction_id'];
        }

        $advancePayment->update($data);

        // ── When advance payment is approved/paid → update linked order status ──
        if (in_array($validated['status'], ['approved', 'paid', 'completed'])) {
            $order = null;

            // 1. Try direct order_id link
            if ($advancePayment->order_id) {
                $order = \App\Models\Order::with('vendor')->find($advancePayment->order_id);
            }

            // 2. Fallback: find the most recent matching order for this user + product
            if (!$order) {
                $order = \App\Models\Order::with('vendor')
                    ->where('user_id', $advancePayment->user_id)
                    ->whereHas('items', fn($q) => $q->where('product_id', $advancePayment->product_id))
                    ->where('status', 'pending_advance_payment')
                    ->latest()
                    ->first();
            }

            if ($order && $order->status === 'pending_advance_payment' && $order->isWholesaleOrImport()) {
                try {
                    app(\App\Services\WholesaleOrderStatusService::class)
                        ->transition($order, 'advance_paid', auth()->user());
                } catch (\Throwable $e) {
                    \Log::error("Auto advance_paid transition failed for order {$order->id}: " . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }

    public function destroy(AdvancePayment $advancePayment)
    {
        $advancePayment->delete();

        return redirect()->route('admin.advance-payments.index')
            ->with('success', 'Advance payment deleted successfully!');
    }
}
