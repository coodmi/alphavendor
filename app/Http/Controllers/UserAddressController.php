<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        if (empty($validated['city'])) {
            $validated['city'] = $validated['district'];
        }

        $validated['user_id'] = auth()->id();

        $address = UserAddress::create($validated);

        if ($request->is_default) {
            $address->setAsDefault();
        }

        return redirect()->back()->with('success', 'Address added successfully!');
    }

    /**
     * Update an address
     */
    public function update(Request $request, UserAddress $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'district' => 'required|string|max:255',
            'zip' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        if (empty($validated['city'])) {
            $validated['city'] = $validated['district'];
        }

        $address->update($validated);

        if ($request->is_default) {
            $address->setAsDefault();
        }

        return redirect()->back()->with('success', 'Address updated successfully!');
    }

    /**
     * Delete an address
     */
    public function destroy(UserAddress $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully!');
    }

    /**
     * Set address as default
     */
    public function setDefault(UserAddress $address)
    {
        // Ensure user owns this address
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->setAsDefault();

        return redirect()->back()->with('success', 'Default address updated!');
    }
}
