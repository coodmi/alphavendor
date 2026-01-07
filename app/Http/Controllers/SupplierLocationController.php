<?php

namespace App\Http\Controllers;

use App\Models\SupplierLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierLocationController extends Controller
{
    /**
     * Display a listing of supplier locations.
     */
    public function index()
    {
        // Check if user is wholesaler
        if (Auth::user()->role !== 'wholesaler') {
            abort(403, 'Unauthorized access');
        }

        $locations = SupplierLocation::orderBy('sort_order')->orderBy('name')->get();

        return view('wholesaler.supplier-locations.index', compact('locations'));
    }

    /**
     * Store a newly created supplier location.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'wholesaler') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:supplier_locations,name',
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        SupplierLocation::create($validated);

        return redirect()->route('wholesaler.supplier-locations.index')
            ->with('success', 'Supplier location created successfully!');
    }

    /**
     * Update the specified supplier location.
     */
    public function update(Request $request, SupplierLocation $supplierLocation)
    {
        if (Auth::user()->role !== 'wholesaler') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:supplier_locations,name,' . $supplierLocation->id,
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $supplierLocation->sort_order;

        $supplierLocation->update($validated);

        return redirect()->route('wholesaler.supplier-locations.index')
            ->with('success', 'Supplier location updated successfully!');
    }

    /**
     * Remove the specified supplier location.
     */
    public function destroy(SupplierLocation $supplierLocation)
    {
        if (Auth::user()->role !== 'wholesaler') {
            abort(403, 'Unauthorized access');
        }

        // Check if location is being used by any products
        if ($supplierLocation->products()->count() > 0) {
            return redirect()->route('wholesaler.supplier-locations.index')
                ->with('error', 'Cannot delete supplier location that is being used by products!');
        }

        $supplierLocation->delete();

        return redirect()->route('wholesaler.supplier-locations.index')
            ->with('success', 'Supplier location deleted successfully!');
    }
}
