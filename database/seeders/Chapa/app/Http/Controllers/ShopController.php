<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    /**
     * Display the shop page with all products.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'format', 'min_price', 'max_price', 'sort']);

        $products = $this->shopService->getProducts($filters);
        $categories = $this->shopService->getCategories();
        $formats = $this->shopService->getFormats();
        $hero = $this->shopService->getHeroSection();

        return view('pages.shop', compact('products', 'categories', 'formats', 'hero'));
    }

    /**
     * Display a specific product detail page.
     */
    public function show($id)
    {
        // Find the product manually
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        // Load related data
        $product->load('category');
        
        // Get related products with intelligent algorithm
        $relatedProducts = collect();
        
        // First, try to get products from the same category
        $sameCategoryProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('popularity', 'desc')
            ->take(4)
            ->get();
        
        $relatedProducts = $relatedProducts->merge($sameCategoryProducts);
        
        // If we don't have enough products, get popular products from other categories
        if ($relatedProducts->count() < 4) {
            $otherProducts = Product::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->orderBy('popularity', 'desc')
                ->take(4 - $relatedProducts->count())
                ->get();
            
            $relatedProducts = $relatedProducts->merge($otherProducts);
        }
        
        // Limit to 4 products and shuffle for variety
        $relatedProducts = $relatedProducts->take(4)->shuffle();

        return view('pages.shop-product-detail', compact('product', 'relatedProducts'));
    }
}
