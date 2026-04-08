<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['user', 'vendor', 'product', 'order']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->latest()->paginate(20);

        $stats = [
            'total' => ReturnRequest::count(),
            'pending' => ReturnRequest::where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('status', 'approved')->count(),
            'processing' => ReturnRequest::where('status', 'processing')->count(),
            'completed' => ReturnRequest::whereIn('status', ['refunded', 'completed'])->count(),
        ];

        return view('admin.returns.index', compact('returns', 'stats'));
    }

    public function show(ReturnRequest $return)
    {
        $return->load(['user', 'vendor', 'product', 'order', 'orderItem', 'exchangeProduct', 'approvedBy', 'rejectedBy']);

        return view('admin.returns.show', compact('return'));
    }

    public function approve(Request $request, ReturnRequest $return)
    {
        if (!$return->canBeApproved()) {
            return back()->with('error', 'Cannot approve this return request');
        }

        $return->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Return request approved successfully');
    }

    public function reject(Request $request, ReturnRequest $return)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (!$return->canBeRejected()) {
            return back()->with('error', 'Cannot reject this return request');
        }

        $return->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Return request rejected');
    }

    public function updateStatus(Request $request, ReturnRequest $return)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped_back,received,refunded,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->notes,
        ];

        // If marking as refunded, record refund details
        if ($request->status === 'refunded') {
            $updateData['refund_processed_at'] = now();
            $updateData['refund_amount'] = $return->amount;
        }

        $return->update($updateData);

        return back()->with('success', 'Return status updated successfully');
    }

    public function processRefund(Request $request, ReturnRequest $return)
    {
        $request->validate([
            'refund_method' => 'required|in:original_payment,wallet,bank_transfer',
            'refund_amount' => 'required|numeric|min:0|max:' . $return->amount,
            'refund_transaction_id' => 'nullable|string|max:255',
        ]);

        if (!$return->canBeRefunded()) {
            return back()->with('error', 'Cannot process refund for this return');
        }

        $return->update([
            'status' => 'refunded',
            'refund_method' => $request->refund_method,
            'refund_amount' => $request->refund_amount,
            'refund_transaction_id' => $request->refund_transaction_id,
            'refund_processed_at' => now(),
        ]);

        // If refund to wallet, add to user's wallet
        if ($request->refund_method === 'wallet') {
            $wallet = $return->user->wallet ?? $return->user->wallet()->create([
                'balance' => 0,
                'pending_balance' => 0,
                'total_earned' => 0,
                'total_withdrawn' => 0,
            ]);

            $wallet->increment('balance', $request->refund_amount);
        }

        return back()->with('success', 'Refund processed successfully');
    }
}
