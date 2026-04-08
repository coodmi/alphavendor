<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('display_order')->get();
        
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:mobile_banking,bank_transfer,cash_on_delivery,card_payment',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/payment-methods'), $logoName);
            $validated['logo'] = $logoName;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully!');
    }

    /**
     * Show the form for editing the payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the payment method.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:mobile_banking,bank_transfer,cash_on_delivery,card_payment',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($paymentMethod->logo && file_exists(public_path('uploads/payment-methods/' . $paymentMethod->logo))) {
                unlink(public_path('uploads/payment-methods/' . $paymentMethod->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/payment-methods'), $logoName);
            $validated['logo'] = $logoName;
        }

        $validated['is_active'] = $request->has('is_active');

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Remove the payment method.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Delete logo if exists
        if ($paymentMethod->logo && file_exists(public_path('uploads/payment-methods/' . $paymentMethod->logo))) {
            unlink(public_path('uploads/payment-methods/' . $paymentMethod->logo));
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully!');
    }

    /**
     * Toggle payment method status.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active,
        ]);

        return redirect()->back()
            ->with('success', 'Payment method status updated successfully!');
    }
}
