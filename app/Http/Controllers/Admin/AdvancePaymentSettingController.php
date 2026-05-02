<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancePaymentSetting;
use Illuminate\Http\Request;

class AdvancePaymentSettingController extends Controller
{
    public function index()
    {
        $settings = AdvancePaymentSetting::getSettings();
        return view('admin.advance-payments.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'advance_percentage' => 'required|numeric|min:1|max:100',
            'is_mandatory'       => 'boolean',
            'description'        => 'nullable|string|max:500',
        ]);

        $validated['is_mandatory'] = $request->boolean('is_mandatory');

        AdvancePaymentSetting::getSettings()->update($validated);

        return redirect()->route('admin.advance-payments.settings')
            ->with('success', 'Advance payment settings updated successfully!');
    }
}
