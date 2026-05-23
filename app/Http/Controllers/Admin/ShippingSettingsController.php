<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliverySetting;
use App\Models\DistrictDeliveryCharge;
use Illuminate\Http\Request;

class ShippingSettingsController extends Controller
{
    public function index()
    {
        $settings = DeliverySetting::current();
        $districts = DistrictDeliveryCharge::orderBy('division')->orderBy('district')->get();

        return view('admin.shipping-settings.index', compact('settings', 'districts'));
    }

    public function updateGlobal(Request $request)
    {
        $validated = $request->validate([
            'extra_per_kg_charge' => 'required|numeric|min:0',
        ]);

        DeliverySetting::current()->update($validated);

        return redirect()->route('admin.shipping-settings.index')
            ->with('success', 'Global extra per KG charge updated.');
    }

    public function updateDistrict(Request $request, DistrictDeliveryCharge $districtDeliveryCharge)
    {
        $validated = $request->validate([
            'base_charge' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $districtDeliveryCharge->update([
            'base_charge' => $validated['base_charge'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.shipping-settings.index')
            ->with('success', "Delivery charge for {$districtDeliveryCharge->district} updated.");
    }

    public function bulkUpdateDistricts(Request $request)
    {
        $validated = $request->validate([
            'districts' => 'required|array',
            'districts.*.id' => 'required|exists:district_delivery_charges,id',
            'districts.*.base_charge' => 'required|numeric|min:0',
        ]);

        foreach ($validated['districts'] as $row) {
            DistrictDeliveryCharge::where('id', $row['id'])->update([
                'base_charge' => $row['base_charge'],
            ]);
        }

        return redirect()->route('admin.shipping-settings.index')
            ->with('success', 'District delivery charges saved.');
    }
}
