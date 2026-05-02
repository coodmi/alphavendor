<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvancePaymentController extends Controller
{
    public function store(Request $request)
    {
        $settings = \App\Models\AdvancePaymentSetting::getSettings();

        $validated = $request->validate([
            'product_id'          => 'required|exists:products,id',
            'quantity'            => 'required|integer|min:1',
            'advance_percentage'  => 'required|numeric|min:1|max:100',
            'payment_method'      => 'required|in:bkash,nagad,rocket,bank_transfer',
            'transaction_id'      => 'required_unless:payment_method,bank_transfer|nullable|string',
            'contact_number'      => 'required|string',
            'notes'               => 'nullable|string',
            'bank_name'           => 'required_if:payment_method,bank_transfer|nullable|string',
            'bank_account_holder' => 'required_if:payment_method,bank_transfer|nullable|string',
            'bank_account_number' => 'required_if:payment_method,bank_transfer|nullable|string',
        ]);

        // Always use the admin-configured percentage, ignore what the client sends
        $advancePct    = (float) $settings->advance_percentage;
        $product       = Product::findOrFail($validated['product_id']);
        $totalAmount   = $product->price * $validated['quantity'];
        $advanceAmount = round($totalAmount * $advancePct / 100, 2);
        $remaining     = round($totalAmount - $advanceAmount, 2);

        $advancePayment = AdvancePayment::create([
            'user_id'              => Auth::id(),
            'product_id'           => $validated['product_id'],
            'vendor_id'            => $product->vendor_id,
            'quantity'             => $validated['quantity'],
            'total_amount'         => $totalAmount,
            'advance_percentage'   => $advancePct,
            'advance_amount'       => $advanceAmount,
            'remaining_amount'     => $remaining,
            'payment_method'       => $validated['payment_method'],
            'transaction_id'       => $validated['transaction_id'] ?? null,
            'contact_number'       => $validated['contact_number'],
            'notes'                => $validated['notes'],
            'bank_name'            => $validated['bank_name'] ?? null,
            'bank_account_holder'  => $validated['bank_account_holder'] ?? null,
            'bank_account_number'  => $validated['bank_account_number'] ?? null,
            'status'               => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Advance payment request submitted! We will contact you shortly.',
            'data'    => $advancePayment,
        ]);
    }

    public function userPayments()
    {
        $payments = AdvancePayment::with(['product', 'vendor'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('advance-payments.index', compact('payments'));
    }
}
