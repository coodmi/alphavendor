<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Handle boolean fields
        $validated['is_required'] = $request->has('is_required');
        $validated['is_filterable'] = $request->has('is_filterable');
        $validated['sort_order'] = $request->input('sort_order', 0);

        // Handle options
        if ($request->input('options') && !empty($request->input('options'))) {
            $validated['options'] = json_decode($request->input('options'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $validated['options'] = null;
            }
        } else {
            $validated['options'] = null;
        }

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('admin.attributes.show', compact('attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('admin.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

        // Handle boolean fields
        $validated['is_required'] = $request->has('is_required');
        $validated['is_filterable'] = $request->has('is_filterable');
        $validated['sort_order'] = $request->input('sort_order', 0);

        // Handle options
        if ($request->input('options') && !empty($request->input('options'))) {
            $validated['options'] = json_decode($request->input('options'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $validated['options'] = null;
            }
        } else {
            $validated['options'] = null;
        }

        $attribute->update($validated);

        // Update slug when name changes
        if ($request->input('name') !== $attribute->getOriginal('name')) {
            $attribute->slug = \Illuminate\Support\Str::slug($request->input('name'));
            $attribute->save();
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
    }
}
