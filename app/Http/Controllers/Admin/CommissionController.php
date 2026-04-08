<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommissionSetting;
use App\Models\CodCommissionSetting;
use App\Models\Category;

class CommissionController extends Controller
{
    /**
     * Display commission settings
     */
    public function index()
    {
        $categories = Category::whereNull('vendor_id')->get(); // Admin categories only
        $commissions = CommissionSetting::with('category')->get()->groupBy('category_id');
        $codCommission = CodCommissionSetting::first();

        return view('admin.commissions.index', compact('categories', 'commissions', 'codCommission'));
    }

    /**
     * Store category commission settings
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'retailer_rate' => 'required|numeric|min:0|max:100',
            'wholesaler_rate' => 'required|numeric|min:0|max:100',
            'importer_rate' => 'required|numeric|min:0|max:100',
        ]);

        $categoryId = $validated['category_id'];

        // Create or update commission for each seller type
        $sellerTypes = [
            'retailer' => $validated['retailer_rate'],
            'wholesaler' => $validated['wholesaler_rate'],
            'importer' => $validated['importer_rate'],
        ];

        foreach ($sellerTypes as $type => $rate) {
            CommissionSetting::updateOrCreate(
                [
                    'category_id' => $categoryId,
                    'seller_type' => $type
                ],
                [
                    'commission_rate' => $rate,
                    'is_active' => true
                ]
            );
        }

        return redirect()->back()->with('success', 'Commission rates updated successfully!');
    }

    /**
     * Update commission for a specific category and seller type
     */
    public function update(Request $request, $id)
    {
        $commission = CommissionSetting::findOrFail($id);

        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $commission->update($validated);

        return redirect()->back()->with('success', 'Commission rate updated successfully!');
    }

    /**
     * Delete commission setting
     */
    public function destroy($id)
    {
        $commission = CommissionSetting::findOrFail($id);
        $commission->delete();

        return redirect()->back()->with('success', 'Commission setting deleted successfully!');
    }

    /**
     * Update COD commission settings
     */
    public function updateCodCommission(Request $request)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Get or create COD commission setting
        $codCommission = CodCommissionSetting::first();

        if ($codCommission) {
            $codCommission->update($validated);
        } else {
            CodCommissionSetting::create($validated);
        }

        return redirect()->back()->with('success', 'COD commission settings updated successfully!');
    }

    /**
     * Bulk update commission rates for a category
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'rates' => 'required|array',
            'rates.*.seller_type' => 'required|in:retailer,wholesaler,importer',
            'rates.*.commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($validated['rates'] as $rate) {
            CommissionSetting::updateOrCreate(
                [
                    'category_id' => $validated['category_id'],
                    'seller_type' => $rate['seller_type']
                ],
                [
                    'commission_rate' => $rate['commission_rate'],
                    'is_active' => true
                ]
            );
        }

        return redirect()->back()->with('success', 'Commission rates updated successfully!');
    }
}
