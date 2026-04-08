<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceProduct;
use Illuminate\Http\Request;

class ServiceProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceProduct::with('category')->latest();

        // Filter by category if slug is provided
        if ($request->has('category')) {
            $category = ServiceCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('service_category_id', $category->id);
            }
        }

        $products = $query->paginate(15);

        return view('admin.services.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->withCount('products')
            ->get();

        return view('admin.services.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
            'config_options' => 'nullable|array',
            'config_options.*.option_name' => 'required|string',
            'config_options.*.option_type' => 'required|in:radio,select,button,tabs,number',
            'config_options.*.option_values' => 'required|array',
            'config_options.*.option_prices' => 'nullable|array',
            'config_options.*.default_value' => 'nullable|string',
            'config_options.*.display_order' => 'nullable|integer',
            'config_options.*.is_required' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads/service-products'), $imageName);
            $validated['image'] = $imageName;
        }

        $validated['is_active'] = $request->has('is_active');

        $product = ServiceProduct::create($validated);

        // Create config options
        if ($request->has('config_options')) {
            foreach ($request->config_options as $optionData) {
                $product->configOptions()->create([
                    'option_name' => $optionData['option_name'],
                    'option_type' => $optionData['option_type'],
                    'option_values' => $optionData['option_values'],
                    'option_prices' => $optionData['option_prices'] ?? array_fill(0, count($optionData['option_values']), 0),
                    'default_value' => $optionData['default_value'] ?? null,
                    'display_order' => $optionData['display_order'] ?? 0,
                    'is_required' => isset($optionData['is_required']) && $optionData['is_required'] == '1',
                ]);
            }
        }

        return redirect()->route('admin.service-products.index')
            ->with('success', 'Service product created successfully with configuration options!');
    }

    public function edit(ServiceProduct $serviceProduct)
    {
        $serviceProduct->load('configOptions');
        $categories = ServiceCategory::where('is_active', true)
            ->withCount('products')
            ->get();

        return view('admin.services.products.edit', compact('serviceProduct', 'categories'));
    }

    public function update(Request $request, ServiceProduct $serviceProduct)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'config_options' => 'nullable|array',
            'config_options.*.option_name' => 'required|string',
            'config_options.*.option_type' => 'required|in:radio,select,button,tabs,number',
            'config_options.*.option_values' => 'required|array',
            'config_options.*.option_prices' => 'nullable|array',
            'config_options.*.default_value' => 'nullable|string',
            'config_options.*.display_order' => 'nullable|integer',
            'config_options.*.is_required' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it's a file (not a URL)
            if ($serviceProduct->image && ! filter_var($serviceProduct->image, FILTER_VALIDATE_URL)) {
                $oldImagePath = public_path('uploads/service-products/'.$serviceProduct->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads/service-products'), $imageName);
            $validated['image'] = $imageName;
        }

        $validated['is_active'] = $request->has('is_active');

        $serviceProduct->update($validated);

        // Delete existing config options and recreate
        $serviceProduct->configOptions()->delete();

        // Create config options
        if ($request->has('config_options')) {
            foreach ($request->config_options as $optionData) {
                $serviceProduct->configOptions()->create([
                    'option_name' => $optionData['option_name'],
                    'option_type' => $optionData['option_type'],
                    'option_values' => $optionData['option_values'],
                    'option_prices' => $optionData['option_prices'] ?? array_fill(0, count($optionData['option_values']), 0),
                    'default_value' => $optionData['default_value'] ?? null,
                    'display_order' => $optionData['display_order'] ?? 0,
                    'is_required' => isset($optionData['is_required']) && $optionData['is_required'] == '1',
                ]);
            }
        }
        $serviceProduct->update($validated);

        return redirect()->route('admin.service-products.index')
            ->with('success', 'Service product updated successfully!');
    }

    public function destroy(ServiceProduct $serviceProduct)
    {
        $serviceProduct->delete();

        return redirect()->route('admin.service-products.index')
            ->with('success', 'Service product deleted successfully!');
    }
}
