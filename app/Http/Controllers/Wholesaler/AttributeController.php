<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderBy('sort_order')->orderBy('name')->get();
        return view('wholesaler.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,select,color,number',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_filterable' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_required'] = $request->has('is_required');
        $validated['is_filterable'] = $request->has('is_filterable');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->input('options') && !empty($request->input('options'))) {
            $validated['options'] = json_decode($request->input('options'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $validated['options'] = null;
            }
        } else {
            $validated['options'] = null;
        }

        Attribute::create($validated);

        return redirect()->route('wholesaler.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,select,color,number',
            'options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_filterable' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_required'] = $request->has('is_required');
        $validated['is_filterable'] = $request->has('is_filterable');
        $validated['sort_order'] = $request->input('sort_order', 0);

        if ($request->input('options') && !empty($request->input('options'))) {
            $validated['options'] = json_decode($request->input('options'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $validated['options'] = null;
            }
        } else {
            $validated['options'] = null;
        }

        $attribute->update($validated);

        return redirect()->route('wholesaler.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('wholesaler.attributes.index')->with('success', 'Attribute deleted successfully.');
    }
}
