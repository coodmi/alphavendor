<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommissionSetting;
use App\Models\Category;
use App\Models\Product;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = CommissionSetting::with(['category', 'product'])->get();
        $categories = Category::all();
        $products = Product::all();

        return view('admin.commissions.index', compact('commissions', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:global,category,product',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        // If global, deactivate other global commissions
        if ($validated['type'] === 'global') {
            CommissionSetting::where('type', 'global')->update(['is_active' => false]);
        }

        CommissionSetting::create($validated);

        return redirect()->back()->with('success', 'Commission setting created successfully!');
    }

    public function update(Request $request, $id)
    {
        $commission = CommissionSetting::findOrFail($id);

        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        // If activating global, deactivate other global commissions
        if ($commission->type === 'global' && ($validated['is_active'] ?? false)) {
            CommissionSetting::where('type', 'global')
                ->where('id', '!=', $id)
                ->update(['is_active' => false]);
        }

        $commission->update($validated);

        return redirect()->back()->with('success', 'Commission setting updated successfully!');
    }

    public function destroy($id)
    {
        $commission = CommissionSetting::findOrFail($id);

        if ($commission->type === 'global' && CommissionSetting::where('type', 'global')->count() === 1) {
            return redirect()->back()->with('error', 'Cannot delete the only global commission setting!');
        }

        $commission->delete();

        return redirect()->back()->with('success', 'Commission setting deleted successfully!');
    }
}
