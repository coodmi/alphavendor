<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::ordered()->get();
        $zones = ShippingMethod::getZones();
        
        return view('admin.shipping-methods.index', compact('shippingMethods', 'zones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'zone' => 'required|string',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'estimated_days_min' => 'required|integer|min:1',
            'estimated_days_max' => 'required|integer|min:1',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ShippingMethod::create($validated);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method created successfully!');
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'zone' => 'required|string',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'estimated_days_min' => 'required|integer|min:1',
            'estimated_days_max' => 'required|integer|min:1',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $shippingMethod->update($validated);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method updated successfully!');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method deleted successfully!');
    }
}
