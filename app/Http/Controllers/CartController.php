<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::with('vendor')->findOrFail($productId);

        $quantity = $request->input('quantity', 1);
        $appliedCoupon = $request->input('applied_coupon');
        $discountAmount = $request->input('discount_amount', 0);

        $cart = Session::get('cart', []);

        // Check if product already in cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
                'vendor_id' => $product->vendor_id,
                'vendor_name' => $product->vendor->name ?? 'Unknown',
                'category_id' => $product->category_id
            ];
        }

        // Store coupon information if applied
        if ($appliedCoupon && $discountAmount > 0) {
            $cart[$productId]['coupon_code'] = $appliedCoupon;
            $cart[$productId]['discount_amount'] = $discountAmount;
        }

        Session::put('cart', $cart);

        // Return JSON for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $quantity = $request->input('quantity', 1);

            if ($quantity > 0) {
                $cart[$productId]['quantity'] = $quantity;
            } else {
                unset($cart[$productId]);
            }

            Session::put('cart', $cart);
        }

        // Return JSON for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated!'
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        // Return JSON for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart!'
            ]);
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        Session::forget('cart');

        return redirect()->back()->with('success', 'Cart cleared!');
    }

    /**
     * Buy Now - Add to cart and redirect to checkout
     */
    public function buyNow(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::with('vendor')->findOrFail($productId);

        $quantity = $request->input('quantity', 1);
        $appliedCoupon = $request->input('applied_coupon');
        $discountAmount = $request->input('discount_amount', 0);

        // Clear existing cart for buy now
        Session::forget('cart');
        
        $cart = [];

        // Add only this product to cart
        $cart[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => $quantity,
            'vendor_id' => $product->vendor_id,
            'vendor_name' => $product->vendor->name ?? 'Unknown',
            'category_id' => $product->category_id
        ];

        // Store coupon information if applied
        if ($appliedCoupon && $discountAmount > 0) {
            $cart[$productId]['coupon_code'] = $appliedCoupon;
            $cart[$productId]['discount_amount'] = $discountAmount;
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Redirecting to checkout...'
        ]);
    }

    /**
     * Validate coupon code
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code'       => 'required|string',
            'subtotal'   => 'required|numeric|min:0',
            'product_id' => 'nullable|integer',
        ]);

        $code      = strtoupper(trim($request->code));
        $subtotal  = (float) $request->subtotal;
        $productId = $request->product_id;

        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code'], 404);
        }

        if (!$coupon->isValid()) {
            $message = 'This coupon is not valid';
            if (!$coupon->is_active)                                                       $message = 'This coupon is inactive';
            elseif ($coupon->start_date && now()->lt($coupon->start_date))                 $message = 'This coupon is not yet active';
            elseif ($coupon->end_date && now()->gt($coupon->end_date))                     $message = 'This coupon has expired';
            elseif ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)   $message = 'This coupon has reached its usage limit';
            return response()->json(['success' => false, 'message' => $message], 400);
        }

        if ($productId) {
            $product = \App\Models\Product::find($productId);
            if (!$coupon->appliesToProduct($productId, $product->category_id ?? null)) {
                return response()->json(['success' => false, 'message' => 'This coupon is not valid for this product'], 400);
            }
        }

        if (auth()->check() && !$coupon->canBeUsedBy(auth()->id())) {
            return response()->json(['success' => false, 'message' => 'You have already used this coupon the maximum number of times'], 400);
        }

        if ($coupon->min_purchase && $subtotal < $coupon->min_purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum purchase of \u09f3' . number_format($coupon->min_purchase, 2) . ' required'
            ], 400);
        }

        $discount = (float) $coupon->calculateDiscount($subtotal);

        return response()->json([
            'success'  => true,
            'message'  => 'Coupon applied! You save ৳' . number_format($discount, 2),
            'coupon'   => [
                'id'           => $coupon->id,
                'code'         => $coupon->code,
                'type'         => $coupon->type,
                'value'        => (float) $coupon->value,
                'min_purchase' => (float) $coupon->min_purchase,
                'max_discount' => (float) $coupon->max_discount,
                'product_id'   => $coupon->product_id,
                'category_id'  => $coupon->category_id,
            ],
            'discount' => $discount,
        ]);
    }
}
