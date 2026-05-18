<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\VendorWallet;
use App\Models\Transaction;
use App\Models\CommissionSetting;
use App\Models\ManualPayment;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Check if cart has wholesaler/importer products
        $advanceSettings = \App\Models\AdvancePaymentSetting::getSettings();
        $hasAdvanceRequired = false;
        $advanceRequiredItems = [];

        foreach ($cart as $item) {
            $vendorRole = $item['vendor_role'] ?? null;
            // If vendor_role not in cart (old session), fetch from DB
            if (!$vendorRole) {
                $vendor = \App\Models\User::find($item['vendor_id']);
                $vendorRole = $vendor->role ?? 'retailer';
            }
            if (in_array($vendorRole, ['wholesaler', 'exporter', 'importer']) && $advanceSettings->is_mandatory) {
                $hasAdvanceRequired = true;
                $advanceRequiredItems[] = $item;
            }
        }

        // Get user's saved addresses
        $addresses = auth()->check() ? auth()->user()->addresses()->orderBy('is_default', 'desc')->get() : collect();

        return view('orders.checkout', compact('cart', 'addresses', 'hasAdvanceRequired', 'advanceRequiredItems', 'advanceSettings'));
    }
    public function invoice(Order $order)
        {
            // Allow admin, or the vendor who owns the order, or the customer
            $user = Auth::user();
            $allowed = $user->role === 'admin'
                || $order->user_id === $user->id
                || $order->vendor_id === $user->id
                || in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer']);

            if (!$allowed) {
                abort(403);
            }

            $order->load(['items.product', 'user']);
            $siteSettings = \App\Models\SiteSetting::getSettings();

            return view('orders.invoice', compact('order', 'siteSettings'));
        }

    public function store(Request $request)
        {
            $validated = $request->validate([
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string',
                'shipping_state' => 'nullable|string',
                'shipping_country' => 'required|string',
                'phone' => 'required|string',
                'payment_method' => 'required|string',
                'delivery_charge' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                // Manual payment fields
                'sender_number' => 'required_if:payment_method,bkash,nagad,rocket|nullable|string',
                'transaction_id' => 'required_if:payment_method,bkash,nagad,rocket|nullable|string',
            ]);

            $cart = Session::get('cart', []);

            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
            }

            // ── Advance Payment Enforcement ──────────────────────────────────────
            // Block order if cart has wholesaler/importer products and advance is mandatory
            $advanceSettings = \App\Models\AdvancePaymentSetting::getSettings();
            if ($advanceSettings->is_mandatory) {
                foreach ($cart as $item) {
                    $vendorRole = $item['vendor_role'] ?? null;
                    if (!$vendorRole) {
                        $vendor = \App\Models\User::find($item['vendor_id']);
                        $vendorRole = $vendor->role ?? 'retailer';
                    }
                    if (in_array($vendorRole, ['wholesaler', 'exporter', 'importer'])) {
                        // Check if user has an approved/paid advance payment for this product
                        $hasAdvance = \App\Models\AdvancePayment::where('user_id', Auth::id())
                            ->where('product_id', $item['id'])
                            ->whereIn('status', ['approved', 'paid', 'completed'])
                            ->exists();

                        if (!$hasAdvance) {
                            return redirect()->back()->with(
                                'error',
                                'অর্ডার দেওয়ার আগে "' . $item['name'] . '" পণ্যের জন্য অ্যাডভান্স পেমেন্ট করতে হবে। পণ্যের পেজে গিয়ে "Pay Advance" বাটনে ক্লিক করুন।'
                            );
                        }
                        break;
                    }
                }
            }
            // ────────────────────────────────────────────────────────────────────

            $deliveryCharge = $validated['delivery_charge'] ?? 0;
            $commissionService = new \App\Services\CommissionService();

            DB::beginTransaction();

            try {
                // Group cart items by vendor
                $ordersByVendor = [];
                $createdOrders = []; // Track created orders

                foreach ($cart as $item) {
                    $vendorId = $item['vendor_id'];

                    if (!isset($ordersByVendor[$vendorId])) {
                        $ordersByVendor[$vendorId] = [];
                    }

                    $ordersByVendor[$vendorId][] = $item;
                }

                // Create separate order for each vendor
                foreach ($ordersByVendor as $vendorId => $items) {
                    // Calculate commission using the new service
                    $commissionData = $commissionService->calculateOrderCommission(
                        $items,
                        $vendorId,
                        $validated['payment_method'],
                        $deliveryCharge
                    );

                    $subtotal = $commissionData['subtotal'];
                    $categoryCommission = $commissionData['category_commission_amount'];
                    $codCommission = $commissionData['cod_commission_amount'];
                    $totalCommission = $commissionData['total_commission'];
                    $vendorEarning = $commissionData['vendor_earning'];

                    // Apply coupon discount if any item in this vendor's order has a coupon
                    $couponDiscount = 0;
                    $appliedCouponCode = null;
                    foreach ($items as $item) {
                        if (!empty($item['coupon_code']) && !empty($item['discount_amount'])) {
                            $couponDiscount += floatval($item['discount_amount']);
                            $appliedCouponCode = $item['coupon_code'];
                        }
                    }
                    // Also check session-level coupon (from checkout page)
                    if (!$couponDiscount && !empty($request->coupon_code)) {
                        $sessionCoupon = \App\Models\Coupon::where('code', strtoupper($request->coupon_code))->first();
                        if ($sessionCoupon && $sessionCoupon->isValid()) {
                            $couponDiscount = $sessionCoupon->calculateDiscount($subtotal);
                            $appliedCouponCode = $sessionCoupon->code;
                        }
                    }

                    $total = $subtotal + $deliveryCharge - $couponDiscount;

                    // Create order
                    // Determine initial status based on vendor role and advance payment settings
                    $vendor = \App\Models\User::find($vendorId);
                    $advanceSettings = \App\Models\AdvancePaymentSetting::getSettings();
                    $initialStatus = \App\Helpers\OrderStatus::initialStatus(
                        $vendor->role ?? 'retailer',
                        $advanceSettings->is_mandatory
                    );

                    $order = Order::create([
                        'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                        'user_id' => Auth::id(),
                        'vendor_id' => $vendorId,
                        'subtotal' => $subtotal,
                        'commission_amount' => $categoryCommission,
                        'commission_rate' => $commissionData['category_commission_rate'],
                        'cod_commission_amount' => $codCommission,
                        'cod_commission_rate' => $commissionData['cod_commission_rate'],
                        'vendor_earning' => $vendorEarning,
                        'total' => max(0, $total),
                        'delivery_charge' => $deliveryCharge,
                        'status' => $initialStatus,
                        'payment_status' => 'unpaid',
                        'payment_method' => $validated['payment_method'],
                        'shipping_address' => $validated['shipping_address'],
                        'shipping_city' => $validated['shipping_city'],
                        'shipping_state' => $validated['shipping_state'] ?? '',
                        'shipping_country' => $validated['shipping_country'],
                        'phone' => $validated['phone'],
                        'notes' => $validated['notes'] ?? ''
                    ]);

                    // Track coupon usage
                    if ($appliedCouponCode && $couponDiscount > 0) {
                        $coupon = \App\Models\Coupon::where('code', $appliedCouponCode)->first();
                        if ($coupon) {
                            \App\Models\CouponUsage::create([
                                'coupon_id'       => $coupon->id,
                                'user_id'         => Auth::id(),
                                'order_id'        => $order->id,
                                'discount_amount' => $couponDiscount,
                            ]);
                            $coupon->increment('used_count');
                        }
                    }

                    // Create order items with commission details
                    foreach ($commissionData['items'] as $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['id'],
                            'product_name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'commission_rate' => $item['category_commission_rate'],
                            'commission_amount' => $item['category_commission_amount'],
                            'cod_commission_amount' => $item['cod_commission_amount'],
                            'vendor_earning' => $item['vendor_earning'],
                            'subtotal' => $item['item_total']
                        ]);
                    }

                    // Update vendor wallet - add to pending balance
                    $wallet = VendorWallet::firstOrCreate(
                        ['vendor_id' => $vendorId],
                        ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
                    );

                    $wallet->increment('pending_balance', $vendorEarning);

                    // Create transaction record
                    Transaction::create([
                        'vendor_id' => $vendorId,
                        'order_id' => $order->id,
                        'transaction_number' => 'TXN-' . time() . '-' . rand(1000, 9999),
                        'type' => 'sale',
                        'amount' => $vendorEarning,
                        'status' => 'pending',
                        'description' => 'Order #' . $order->order_number
                    ]);

                    // If payment method is bKash, Nagad, or Rocket, create manual payment record
                    if (in_array($validated['payment_method'], ['bkash', 'nagad', 'rocket'])) {
                        ManualPayment::create([
                            'order_id' => $order->id,
                            'user_id' => Auth::id(),
                            'payment_method' => $validated['payment_method'],
                            'sender_number' => $validated['sender_number'],
                            'transaction_id' => $validated['transaction_id'],
                            'amount' => $order->total,
                            'status' => 'pending',
                        ]);

                        // Update order payment status to pending verification
                        $order->update(['payment_status' => 'pending_verification']);
                    }
                    
                    // Add order to created orders list
                    $createdOrders[] = $order->id;

                    // Link any pending advance payment for this user+vendor to this order
                    \App\Models\AdvancePayment::where('user_id', Auth::id())
                        ->where('vendor_id', $vendorId)
                        ->whereIn('status', ['pending', 'approved', 'paid'])
                        ->whereNull('order_id')
                        ->update(['order_id' => $order->id]);

                    // Send notifications
                    \App\Services\NotificationService::orderPlaced($order->load('user'));
                }

                // Check if any order has manual payment
                $hasManualPayment = in_array($validated['payment_method'], ['bkash', 'nagad', 'rocket']);

                // Clear cart
                Session::forget('cart');

                DB::commit();

                // Store order IDs in session for success page
                Session::put('created_order_ids', $createdOrders);

                if ($hasManualPayment) {
                    return redirect()->route('orders.success')
                        ->with('success', 'Order placed successfully!')
                        ->with('payment_pending_verification', true);
                }

                return redirect()->route('orders.success')->with('success', 'Order placed successfully!');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Failed to place order. Please try again. ' . $e->getMessage());
            }
        }

    public function success()
    {
        $orderIds = session('created_order_ids', []);
        
        if (empty($orderIds)) {
            return redirect()->route('home')->with('error', 'No order information found.');
        }
        
        $orders = Order::with(['items', 'vendor', 'user', 'manualPayment'])
            ->whereIn('id', $orderIds)
            ->get();
        
        // Clear the session data after retrieving
        Session::forget('created_order_ids');
        
        return view('orders.success', compact('orders'));
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['vendor', 'items'])
            ->latest()
            ->paginate(16);

        return view('orders.my-orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['vendor', 'items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    // Vendor methods
    public function vendorOrders()
    {
        $orders = Order::where('vendor_id', Auth::id())
            ->with(['user', 'items'])
            ->latest()
            ->paginate(16);

        return view('orders.vendor-orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('vendor_id', Auth::id())->with('vendor')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:shipped',
            // Vendors (wholesaler/importer) can ONLY mark as shipped
        ]);

        // Use the state machine for wholesale/import orders
        if ($order->isWholesaleOrImport()) {
            try {
                app(\App\Services\WholesaleOrderStatusService::class)
                    ->transition($order, 'shipped', Auth::user());
            } catch (\App\Exceptions\UnauthorisedOrderTransitionException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            } catch (\App\Exceptions\InvalidOrderTransitionException $e) {
                return redirect()->back()->with('error', 'You can only mark orders as Shipped when they are in Processing status.');
            }
            return redirect()->back()->with('success', 'Order marked as Shipped!');
        }

        // Retail vendor — state machine (only shipped allowed)
        try {
            app(\App\Services\RetailOrderStatusService::class)
                ->transition($order, 'shipped', Auth::user());
        } catch (\App\Exceptions\UnauthorisedOrderTransitionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\App\Exceptions\InvalidOrderTransitionException $e) {
            return redirect()->back()->with('error', 'You can only mark orders as Shipped when they are Order Confirmed or Processing.');
        }

        return redirect()->back()->with('success', 'Order marked as Shipped!');
    }
}
