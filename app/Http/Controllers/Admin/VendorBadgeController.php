<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorBadgeController extends Controller
{
    public function index()
    {
        $badges = VendorBadge::withCount('vendors')->ordered()->get();
        return view('admin.vendor-badges.index', compact('badges'));
    }

    public function store(Request $request)
    {
        try {
            // Log incoming request data
            \Log::info('Badge creation attempt', [
                'all_data' => $request->all(),
                'has_name' => $request->has('name'),
                'name_value' => $request->input('name'),
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'color' => 'required|string|max:20',
                'bg_color' => 'required|string|max:20',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['sort_order'] = $request->input('sort_order') ?: 0;

            $badge = VendorBadge::create($validated);

            \Log::info('Badge created successfully', ['badge_id' => $badge->id]);

            return redirect()->route('admin.vendor-badges.index')
                ->with('success', 'Vendor badge created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Badge creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create badge: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, VendorBadge $vendorBadge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'required|string|max:20',
            'bg_color' => 'required|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['sort_order'] = $request->input('sort_order') ?: 0;

        $vendorBadge->update($validated);

        return redirect()->route('admin.vendor-badges.index')
            ->with('success', 'Vendor badge updated successfully!');
    }

    public function destroy(VendorBadge $vendorBadge)
    {
        $vendorBadge->delete();

        return redirect()->route('admin.vendor-badges.index')
            ->with('success', 'Vendor badge deleted successfully!');
    }
}
