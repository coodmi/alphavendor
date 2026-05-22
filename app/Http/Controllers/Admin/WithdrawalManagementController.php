<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use App\Models\VendorWallet;
use App\Models\Transaction;
use App\Models\User;

class WithdrawalManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with(['vendor', 'withdrawalMethod', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by payment method type
        if ($request->filled('payment_type')) {
            $query->whereHas('withdrawalMethod', function($q) use ($request) {
                $q->where('type', $request->payment_type);
            });
        }

        // Search by withdrawal number
        if ($request->filled('search')) {
            $query->where('withdrawal_number', 'like', '%' . $request->search . '%');
        }

        $withdrawals = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'pending' => Withdrawal::where('status', 'pending')->count(),
            'approved' => Withdrawal::where('status', 'approved')->count(),
            'completed' => Withdrawal::where('status', 'completed')->count(),
            'rejected' => Withdrawal::where('status', 'rejected')->count(),
            'total_pending_amount' => Withdrawal::where('status', 'pending')->sum('amount'),
            'total_completed_amount' => Withdrawal::where('status', 'completed')->sum('amount'),
        ];

        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])->get();

        return view('admin.withdrawals.index', compact('withdrawals', 'stats', 'vendors'));
    }

    public function show($id)
    {
        $withdrawal = Withdrawal::with(['vendor', 'withdrawalMethod', 'approver'])->findOrFail($id);
        
        // Get vendor's wallet info
        $wallet = VendorWallet::where('vendor_id', $withdrawal->vendor_id)->first();
        
        // Get vendor's recent withdrawals
        $recentWithdrawals = Withdrawal::where('vendor_id', $withdrawal->vendor_id)
            ->where('id', '!=', $id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.withdrawals.show', compact('withdrawal', 'wallet', 'recentWithdrawals'));
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending withdrawals can be approved.');
        }

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'admin_notes' => $validated['admin_notes'] ?? null
            ]);

            // Update transaction status
            Transaction::where('vendor_id', $withdrawal->vendor_id)
                ->where('withdrawal_id', $withdrawal->id)
                ->update(['status' => 'approved']);

            DB::commit();

            return redirect()->back()->with('success', 'Withdrawal approved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve withdrawal.');
        }
    }

    public function complete(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'transaction_reference' => 'nullable|string|max:255'
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        if (!in_array($withdrawal->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Only pending or approved withdrawals can be completed.');
        }

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status' => 'completed',
                'completed_at' => now(),
                'approved_at' => $withdrawal->approved_at ?? now(),
                'approved_by' => $withdrawal->approved_by ?? Auth::id(),
                'admin_notes' => $validated['admin_notes'] ?? $withdrawal->admin_notes
            ]);

            // Update transaction status
            Transaction::where('vendor_id', $withdrawal->vendor_id)
                ->where('withdrawal_id', $withdrawal->id)
                ->update([
                    'status'      => 'completed',
                    'description' => 'Withdrawal completed #' . $withdrawal->withdrawal_number .
                                     (($validated['transaction_reference'] ?? '') ? ' - Ref: ' . $validated['transaction_reference'] : '')
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Withdrawal marked as completed!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to complete withdrawal.');
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending withdrawals can be rejected.');
        }

        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_notes' => $validated['admin_notes']
            ]);

            // Refund the amount back to vendor's wallet
            $wallet = VendorWallet::where('vendor_id', $withdrawal->vendor_id)->first();
            if ($wallet) {
                $wallet->increment('balance', $withdrawal->amount);
            }

            // Update transaction status
            Transaction::where('vendor_id', $withdrawal->vendor_id)
                ->where('withdrawal_id', $withdrawal->id)
                ->update(['status' => 'rejected']);

            // Create refund transaction
            Transaction::create([
                'vendor_id' => $withdrawal->vendor_id,
                'transaction_number' => 'TXN-REFUND-' . time() . '-' . rand(1000, 9999),
                'type' => 'refund',
                'amount' => $withdrawal->amount,
                'status' => 'completed',
                'description' => 'Refund for rejected withdrawal #' . $withdrawal->withdrawal_number
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Withdrawal rejected and amount refunded to vendor wallet.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reject withdrawal.');
        }
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'withdrawal_ids' => 'required|array',
            'withdrawal_ids.*' => 'exists:withdrawals,id',
            'action' => 'required|in:approve,complete,reject',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($validated['withdrawal_ids'] as $id) {
            try {
                switch ($validated['action']) {
                    case 'approve':
                        $this->approve($request, $id);
                        $successCount++;
                        break;
                    case 'complete':
                        $this->complete($request, $id);
                        $successCount++;
                        break;
                    case 'reject':
                        $this->reject($request, $id);
                        $successCount++;
                        break;
                }
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        $message = "Processed {$successCount} withdrawals successfully.";
        if ($failCount > 0) {
            $message .= " {$failCount} failed.";
        }

        return redirect()->back()->with('success', $message);
    }
}
