<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form.
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        // Redirect back to cart if cart is empty
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Calculate totals (no tax)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $total = $subtotal; // No tax added

        // Fetch dynamic checkout fields
        $fields = \App\Models\CheckoutField::where('is_visible', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        // Fetch active payment methods
        $paymentMethods = \App\Models\PaymentMethod::getActive();

        return view('checkout.index', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'tax' => 0, // No tax
            'total' => $total,
            'itemCount' => count($cart),
            'user' => auth()->user(),
            'fields' => $fields,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        // Get active payment methods for validation
        $activePaymentMethods = \App\Models\PaymentMethod::getActive();
        $validPaymentTypes = $activePaymentMethods->pluck('type')->unique()->toArray();
        
        // Standard required fields validation
        $rules = [
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'payment_method' => 'required|string|in:' . implode(',', $validPaymentTypes),
        ];

        // Add conditional validation for manual payments (not cash on delivery)
        if ($request->payment_method !== 'cash_on_delivery') {
            $rules['payment_phone'] = 'required|string|max:20';
            $rules['transaction_id'] = 'required|string|max:50';
            $rules['payment_screenshot'] = 'nullable|image|max:5120'; // 5MB
        }

        // Build dynamic validation for additional fields
        $fields = \App\Models\CheckoutField::where('is_visible', true)->get();

        foreach ($fields as $field) {
            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field->type == 'email') {
                $fieldRules[] = 'email';
            }

            if ($field->field_key == 'payment_method') {
                 continue; // We handle this manually above
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:500';
            }

            $rules[$field->field_key] = implode('|', $fieldRules);
        }

        $validated = $request->validate($rules);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculate totals (no tax)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $total = $subtotal; // No tax added

        // Here you would typically:
        // 1. Create an order in the database
        // 2. Process payment
        // 3. Send confirmation email
        // 4. Clear the cart

        // For now, we'll just simulate success
        $orderNumber = 'ORD-'.strtoupper(uniqid());
        
        // Handle payment screenshot upload
        $paymentScreenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $paymentScreenshotPath = $request->file('payment_screenshot')->store('payment-proofs', 'public');
        }

        // Save order to database
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'order_number' => $orderNumber,
            'shipping_name' => $validated['shipping_name'] ?? auth()->user()->name,
            'shipping_email' => $validated['shipping_email'] ?? auth()->user()->email,
            'shipping_phone' => $validated['shipping_phone'] ?? null,
            'shipping_country' => $validated['shipping_country'] ?? null,
            'shipping_address' => $validated['shipping_address'] ?? null,
            'shipping_city' => $validated['shipping_city'] ?? null,
            'shipping_state' => $validated['shipping_state'] ?? null,
            'shipping_zip' => $validated['shipping_zip'] ?? null,
            'payment_method' => $validated['payment_method'] ?? 'cash_on_delivery',
            'payment_phone' => $validated['payment_phone'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'payment_screenshot_path' => $paymentScreenshotPath,
            'is_payment_verified' => false,
            'notes' => $validated['notes'] ?? null,
            'subtotal' => $subtotal,
            'tax' => 0, // No tax
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            // Determine if this is a service product or regular product
            $productType = 'product'; // default
            if (isset($item['is_service_product']) && $item['is_service_product']) {
                $productType = 'service';
            }
            
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_title' => $item['title'],
                'product_type' => $productType,
                'product_image' => $item['image'],
                'format' => $item['format'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'configuration' => $item['configurations'] ?? null,
            ]);
        }

        // Store order details in session for confirmation page (if needed later)
        // Remove file objects before storing in session to avoid serialization errors
        $sessionData = $validated;
        unset($sessionData['payment_screenshot']);
        
        // Add file paths to session data
        $sessionData['payment_screenshot_path'] = $paymentScreenshotPath;

        session()->put('last_order', [
            'order_number' => $orderNumber,
            'items' => $cart,
            'shipping' => $sessionData,
            'subtotal' => $subtotal,
            'tax' => 0, // No tax
            'total' => $total,
            'date' => now(),
        ]);

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }

    /**
     * Show order success page.
     */
    public function success(Request $request)
    {
        $order = session()->get('last_order');

        if (! $order) {
            return redirect()->route('shop')->with('error', 'No order found!');
        }

        return view('checkout.success', ['order' => $order]);
    }
}
