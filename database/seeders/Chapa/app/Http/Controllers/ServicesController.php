<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceCategory;
use App\Models\ServiceProduct;
use App\Services\GlobalServiceOptionService;
use App\Services\OrderCaptureService;
use App\Services\PriceCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected GlobalServiceOptionService $optionService;
    protected PriceCalculatorService $priceCalculator;
    protected OrderCaptureService $orderCapture;

    public function __construct(
        GlobalServiceOptionService $optionService,
        PriceCalculatorService $priceCalculator,
        OrderCaptureService $orderCapture
    ) {
        $this->optionService = $optionService;
        $this->priceCalculator = $priceCalculator;
        $this->orderCapture = $orderCapture;
    }
    /**
     * Display the main services page with all service categories.
     */
    public function index()
    {
        $serviceCategories = ServiceCategory::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->paginate(9);

        // Get dynamic page settings
        $hero = \App\Models\ServicesPageSetting::getSection('hero');
        $intro = \App\Models\ServicesPageSetting::getSection('intro');
        $features = \App\Models\ServicesPageSetting::getSection('features');
        $stats = \App\Models\ServicesPageSetting::getSection('stats');

        return view('pages.services', compact('serviceCategories', 'hero', 'intro', 'features', 'stats'));
    }

    /**
     * Display products for a specific service category.
     */
    public function category($categorySlug)
    {
        $category = ServiceCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get page data
        $pageData = $category->page_data ? json_decode($category->page_data, true) : [];
        
        // Merge with defaults
        $pageData = array_merge([
            'hero_title' => $category->name,
            'hero_subtitle' => $category->name . ' Printing',
            'hero_description' => $category->description,
            'hero_image' => null,
            'hero_background_image' => null,
            'tagline_bangla' => null,
            'tagline_bangla_subtitle' => null,
            'show_tagline' => false,
            'feature_badge_1' => 'Lead times 48h',
            'feature_badge_2' => 'Color-managed',
            'feature_badge_3' => 'Proofing included',
            'meta_title' => $category->name . ' | Services',
            'meta_description' => $category->description,
        ], $pageData);

        $products = ServiceProduct::where('service_category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(10);

        return view('pages.service-category', compact('category', 'products', 'pageData'));
    }

    /**
     * Display a specific service product configuration page.
     */
    public function product($categorySlug, $productSlug)
    {
        $category = ServiceCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $product = ServiceProduct::where('slug', $productSlug)
            ->where('service_category_id', $category->id)
            ->where('is_active', true)
            ->with('configOptions')
            ->firstOrFail();

        // Load global service options for this product
        $globalOptions = $this->optionService->getOptionsForProduct($product);

        return view('products.configure.service', compact('product', 'category', 'globalOptions'));
    }

    /**
     * Calculate price with selected options (AJAX endpoint).
     */
    public function calculatePrice(ServiceProduct $product, Request $request): JsonResponse
    {
        $selectedOptions = $request->except('_token');
        $priceBreakdown = $this->priceCalculator->calculateTotalPrice($product, $selectedOptions);

        return response()->json($priceBreakdown);
    }

    /**
     * Submit order with selected options.
     */
    public function submitOrder(ServiceProduct $product, SubmitOrderRequest $request): RedirectResponse
    {
        // Validate option selections
        $validationErrors = $this->optionService->validateSelection($product, $request->all());
        
        if (!empty($validationErrors)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validationErrors);
        }

        // Calculate final price
        $priceBreakdown = $this->priceCalculator->calculateTotalPrice($product, $request->all());

        // Create order (simplified - you may need to adjust based on your order creation logic)
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'shipping_name' => auth()->user()->name ?? 'Guest User',
            'shipping_email' => auth()->user()->email ?? 'guest@example.com',
            'payment_method' => 'pending',
            'subtotal' => $priceBreakdown['subtotal'] ?? $priceBreakdown['total'],
            'tax' => $priceBreakdown['tax'] ?? 0,
            'total' => $priceBreakdown['total'],
            'status' => 'pending',
        ]);

        // Create order item
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_type' => 'service',
            'quantity' => $request->input('quantity', 1),
            'price' => $priceBreakdown['total'],
        ]);

        // Capture selected options
        $this->orderCapture->captureOptions($orderItem, $request->all());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }
}