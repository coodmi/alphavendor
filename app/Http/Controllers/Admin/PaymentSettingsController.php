<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class PaymentSettingsController extends Controller
{
    /**
     * Show payment settings page.
     */
    public function index()
    {
        $settings = Setting::getPaymentSettings();
        
        return view('admin.payment-settings.index', compact('settings'));
    }

    /**
     * Update payment settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bkash_number' => 'nullable|string|max:20',
            'bkash_name' => 'nullable|string|max:100',
            'bkash_type' => 'nullable|in:personal,agent,merchant',
            'bkash_enabled' => 'nullable|boolean',
            'nagad_number' => 'nullable|string|max:20',
            'nagad_name' => 'nullable|string|max:100',
            'nagad_type' => 'nullable|in:personal,agent,merchant',
            'nagad_enabled' => 'nullable|boolean',
            'rocket_number' => 'nullable|string|max:20',
            'rocket_name' => 'nullable|string|max:100',
            'rocket_enabled' => 'nullable|boolean',
        ]);

        // Handle checkbox values
        $validated['bkash_enabled'] = $request->has('bkash_enabled') ? '1' : '0';
        $validated['nagad_enabled'] = $request->has('nagad_enabled') ? '1' : '0';
        $validated['rocket_enabled'] = $request->has('rocket_enabled') ? '1' : '0';

        Setting::savePaymentSettings($validated);

        return redirect()->back()->with('success', 'Payment settings updated successfully!');
    }
}
