<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductDetailController extends Controller
{
    /**
     * Display product details page.
     */
    public function show(string $category, string $productSlug)
    {
        // Find the product by slug
        $product = Product::with(['category'])
            ->where('slug', $productSlug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related products from the same category
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('products.detail', compact('product', 'relatedProducts'));
    }
}
