<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use App\Models\VendorWallet;
use App\Models\Transaction;

class WithdrawalController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        $withdrawals = Withdrawal::where('vendor_id', $vendorId)
            ->with('withdrawalMethod')
            ->latest()
            ->paginate(16);

        $wallet = VendorWallet::where('vendor_id', $vendorId)->first();
        $paymentMethods = WithdrawalMethod::where('vendor_id', $vendorId)->get();

        return view('withdrawals.index', compact('withdrawals', 'wallet', 'paymentMethods'));
    }

    public function create()
    {
        $wallet = VendorWallet::where('vendor_id', Auth::id())->first();
        $paymentMethods = WithdrawalMethod::where('vendor_id', Auth::id())->get();

        return view('withdrawals.create', compact('wallet', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'withdrawal_method_id' => 'required|exists:withdrawal_methods,id',
            'notes' => 'nullable|string'
        ]);

        $vendorId = Auth::id();
        $wallet = VendorWallet::where('vendor_id', $vendorId)->first();

        if (!$wallet || $wallet->balance < $validated['amount']) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        DB::beginTransaction();
        try {
            // Create withdrawal request
            $withdrawal = Withdrawal::create([
                'vendor_id' => $vendorId,
                'withdrawal_method_id' => $validated['withdrawal_method_id'],
                'withdrawal_number' => 'WD-' . time() . '-' . rand(1000, 9999),
                'amount' => $validated['amount'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null
            ]);

            // Deduct from available balance
            $wallet->decrement('balance', $validated['amount']);

            // Create transaction record
            Transaction::create([
                'vendor_id' => $vendorId,
                'transaction_number' => 'TXN-WD-' . time() . '-' . rand(1000, 9999),
                'type' => 'withdrawal',
                'amount' => $validated['amount'],
                'status' => 'pending',
                'description' => 'Withdrawal request #' . $withdrawal->withdrawal_number
            ]);

            DB::commit();

            return redirect()->route('withdrawals.index')->with('success', 'Withdrawal request submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process withdrawal request.');
        }
    }

    // Payment method management
    public function paymentMethods()
    {
        $methods = WithdrawalMethod::where('vendor_id', Auth::id())->get();
        return view('withdrawals.payment-methods', compact('methods'));
    }

    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,paypal,stripe,other',
            'account_name' => 'required_if:type,bank|string',
            'account_number' => 'required_if:type,bank|string',
            'bank_name' => 'nullable|string',
            'routing_number' => 'nullable|string',
            'swift_code' => 'nullable|string',
            'paypal_email' => 'required_if:type,paypal|email',
            'additional_details' => 'nullable|string',
            'is_default' => 'boolean'
        ]);

        $vendorId = Auth::id();

        // If setting as default, unset other defaults
        if ($validated['is_default'] ?? false) {
            WithdrawalMethod::where('vendor_id', $vendorId)->update(['is_default' => false]);
        }

        WithdrawalMethod::create(array_merge($validated, ['vendor_id' => $vendorId]));

        return redirect()->back()->with('success', 'Payment method added successfully!');
    }

    public function deletePaymentMethod($id)
    {
        $method = WithdrawalMethod::where('vendor_id', Auth::id())->findOrFail($id);
        $method->delete();

        return redirect()->back()->with('success', 'Payment method deleted!');
    }
}
