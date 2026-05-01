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
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:text,select,color,number',
            'options'       => 'nullable|string',
            'is_required'   => 'nullable',
            'is_filterable' => 'nullable',
            'sort_order'    => 'nullable|integer',
        ]);

        $validated['is_required']   = $request->has('is_required');
        $validated['is_filterable'] = $request->has('is_filterable');
        $validated['sort_order']    = (int) $request->input('sort_order', 0);

        // Parse options JSON
        $optionsRaw = $request->input('options');
        if (!empty($optionsRaw)) {
            $decoded = json_decode($optionsRaw, true);
            $validated['options'] = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : null;
        } else {
            $validated['options'] = null;
        }

        // Generate unique slug
        $slug = \Illuminate\Support\Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Attribute::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $validated['slug'] = $slug;

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute "' . $validated['name'] . '" created successfully.');
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
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:text,select,color,number',
            'options'       => 'nullable|string',
            'is_required'   => 'nullable',
            'is_filterable' => 'nullable',
            'sort_order'    => 'nullable|integer',
        ]);

        $validated['is_required']   = $request->has('is_required');
        $validated['is_filterable'] = $request->has('is_filterable');
        $validated['sort_order']    = (int) $request->input('sort_order', 0);

        // Parse options JSON
        $optionsRaw = $request->input('options');
        if (!empty($optionsRaw)) {
            $decoded = json_decode($optionsRaw, true);
            $validated['options'] = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : null;
        } else {
            $validated['options'] = null;
        }

        // Update slug if name changed
        if ($validated['name'] !== $attribute->name) {
            $slug = \Illuminate\Support\Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            while (Attribute::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute "' . $validated['name'] . '" updated successfully.');
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
