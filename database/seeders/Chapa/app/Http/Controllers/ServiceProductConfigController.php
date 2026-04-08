<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Models\ServiceProduct;

class ServiceProductConfigController extends Controller
{
    public function show($product, $categorySlug = null)
    {
        // The product is the route parameter {product}, categorySlug comes from defaults()
        $productSlug = $product;

        // Try to find the category with exact slug match
        $category = ServiceCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->first();

        // If still no category, redirect back with error
        if (! $category) {
            return redirect()->route('home')
                ->with('error', 'Category not found. Please browse our available products.');
        }

        // Get the product with its configuration options
        $product = ServiceProduct::where('slug', $productSlug)
            ->where('service_category_id', $category->id)
            ->where('is_active', true)
            ->with('configOptions')
            ->first();

        // If product not found, try to find it in any category
        if (! $product) {
            $product = ServiceProduct::where('slug', $productSlug)
                ->where('is_active', true)
                ->with(['configOptions', 'category'])
                ->first();

            if ($product && $product->category) {
                $category = $product->category;
            }
        }

        // If still no product found, show error page
        if (! $product) {
            return redirect("/{$categorySlug}")
                ->with('error', 'Product not found. Please select a product from our catalog.');
        }

        return view('products.configure.dynamic', compact('product', 'category'));
    }
}
