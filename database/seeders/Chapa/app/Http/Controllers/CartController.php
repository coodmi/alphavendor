<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        // Calculate totals (no tax)
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $total = $subtotal; // Total equals subtotal (no tax)

        return view('cart.index', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax' => 0, // No tax
            'total' => $total,
            'itemCount' => count($cart),
        ]);
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        // Check if this is a configured service product or a database product
        if ($request->has('service_product')) {
            return $this->addServiceProduct($request);
        }

        $product = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'title' => 'required|string',
            'category' => 'required|string',
            'format' => 'required|string',
            'price' => 'required|numeric',
            'rating' => 'required|numeric',
            'image' => 'required|string',
            'desc' => 'required|string',
            'stock' => 'required|in:0,1',
            'quantity' => 'nullable|integer|min:1',
        ]);

        // Convert stock to boolean
        $product['stock'] = (bool) $product['stock'];
        $quantity = $product['quantity'] ?? 1;

        $cart = session()->get('cart', []);

        // Create a unique key using product_id
        $productKey = 'product_'.$product['product_id'];

        if (isset($cart[$productKey])) {
            // Product already in cart, increase quantity
            $cart[$productKey]['quantity'] += $quantity;
        } else {
            // Add new product to cart
            $product['quantity'] = $quantity;
            $cart[$productKey] = $product;
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => count($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    /**
     * Add configured service product to cart.
     */
    protected function addServiceProduct(Request $request)
    {
        $data = $request->validate([
            'service_product' => 'required|boolean',
            'title' => 'required|string',
            'category' => 'required|string',
            'product_type' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'configurations' => 'nullable|string',
        ]);

        // Parse configurations from JSON string if provided
        if (isset($data['configurations']) && is_string($data['configurations'])) {
            $data['configurations'] = json_decode($data['configurations'], true) ?? [];
        } else {
            $data['configurations'] = [];
        }

        // Provide default image if not provided
        if (empty($data['image'])) {
            $data['image'] = 'https://placehold.co/400x400?text='.urlencode($data['title']);
        }

        $cart = session()->get('cart', []);

        // Create a unique key based on category, product type, and configurations
        $configHash = md5(json_encode($data['configurations'] ?? []));
        $productKey = 'service_'.$data['category'].'_'.$data['product_type'].'_'.$configHash;

        if (isset($cart[$productKey])) {
            // Same configuration exists, increase quantity
            $cart[$productKey]['quantity'] += $data['quantity'];
        } else {
            // Add new configured product to cart
            $cart[$productKey] = [
                'title' => $data['title'],
                'category' => $data['category'],
                'product_type' => $data['product_type'],
                'price' => $data['price'],
                'image' => $data['image'],
                'quantity' => $data['quantity'],
                'configurations' => $data['configurations'] ?? [],
                'is_service_product' => true,
                'format' => 'Custom Configuration',
                'desc' => 'Configured '.$data['title'],
                'stock' => true,
                'rating' => 5,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $data['title'].' added to cart!',
                'cart_count' => count($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('success', $data['title'].' added to cart!');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request)
    {
        $productKey = $request->input('product_key');

        $cart = session()->get('cart', []);
        unset($cart[$productKey]);

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request)
    {
        $productKey = $request->input('product_key');
        $quantity = $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$productKey])) {
            if ($quantity <= 0) {
                unset($cart[$productKey]);
            } else {
                $cart[$productKey]['quantity'] = $quantity;
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    /**
     * Clear entire cart.
     */
    public function clear(Request $request)
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    /**
     * Get cart count for header.
     */
    public function getCount(Request $request)
    {
        $cart = session()->get('cart', []);

        return response()->json(['count' => count($cart)]);
    }
}
