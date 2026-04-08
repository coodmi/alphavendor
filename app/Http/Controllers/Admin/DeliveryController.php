<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PaperflyService;

class DeliveryController extends Controller
{
    protected $paperflyService;

    public function __construct(PaperflyService $paperflyService)
    {
        $this->paperflyService = $paperflyService;
    }

    /**
     * Display delivery management dashboard
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'vendor', 'items']);

        // Filter by delivery status
        if ($request->has('delivery_status') && $request->delivery_status != '') {
            $query->where('delivery_status', $request->delivery_status);
        }

        // Filter by order status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('paperfly_tracking_number', 'like', "%{$search}%")
                  ->orWhere('paperfly_merchant_order_ref', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_pickup' => Order::where('delivery_status', 'pending')->count(),
            'in_transit' => Order::where('delivery_status', 'in_transit')->count(),
            'delivered' => Order::where('delivery_status', 'delivered')->count(),
            'returned' => Order::where('delivery_status', 'returned')->count(),
        ];

        return view('admin.delivery.index', compact('orders', 'stats'));
    }

    /**
     * Create Paperfly delivery order
     */
    public function createDelivery(Request $request, $orderId)
    {
        $order = Order::with(['user', 'vendor', 'items'])->findOrFail($orderId);

        // Prepare order data for Paperfly
        $orderData = [
            'order_number' => $order->order_number,
            'store_name' => $order->vendor->name ?? 'Armarketbd',
            'product_brief' => $this->getProductBrief($order),
            'package_price' => $order->total,
            'max_weight' => $this->calculateWeight($order),
            'customer_name' => $order->user->name,
            'customer_address' => $order->shipping_address . ', ' . $order->shipping_city,
            'customer_phone' => $order->phone,
        ];

        $result = $this->paperflyService->createOrder($orderData);

        if ($result['success']) {
            // Update order with tracking information
            $order->update([
                'paperfly_tracking_number' => $result['tracking_number'],
                'paperfly_merchant_order_ref' => $order->order_number,
                'delivery_status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Delivery order created successfully! Tracking: ' . $result['tracking_number']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Track order delivery status
     */
    public function trackDelivery($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order->paperfly_merchant_order_ref) {
            return redirect()->back()->with('error', 'No tracking information available for this order.');
        }

        $result = $this->paperflyService->trackOrder($order->paperfly_merchant_order_ref);

        if ($result['success']) {
            // Update order delivery status
            $order->update([
                'delivery_status' => $result['status']['status'],
            ]);

            // Update timestamps based on status
            if ($result['status']['status'] == 'delivered' && !$order->delivered_at) {
                $order->update(['delivered_at' => now()]);
            }

            return redirect()->back()->with('success', 'Tracking updated successfully!')
                ->with('tracking_data', $result['status']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Bulk track multiple orders
     */
    public function bulkTrack(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        $updated = 0;
        $failed = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);
            
            if ($order && $order->paperfly_merchant_order_ref) {
                $result = $this->paperflyService->trackOrder($order->paperfly_merchant_order_ref);
                
                if ($result['success']) {
                    $order->update([
                        'delivery_status' => $result['status']['status'],
                    ]);
                    $updated++;
                } else {
                    $failed++;
                }
            }
        }

        return redirect()->back()->with('success', "Updated {$updated} orders. Failed: {$failed}");
    }

    /**
     * Cancel delivery order
     */
    public function cancelDelivery($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order->paperfly_tracking_number) {
            return redirect()->back()->with('error', 'No delivery order to cancel.');
        }

        $result = $this->paperflyService->cancelOrder($order->paperfly_tracking_number);

        if ($result['success']) {
            $order->update([
                'delivery_status' => 'cancelled',
                'status' => 'cancelled',
            ]);

            return redirect()->back()->with('success', 'Delivery order cancelled successfully!');
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Get product brief for Paperfly
     */
    private function getProductBrief($order)
    {
        $items = $order->items->pluck('product_name')->take(3)->toArray();
        $brief = implode(', ', $items);
        
        if ($order->items->count() > 3) {
            $brief .= ' and ' . ($order->items->count() - 3) . ' more items';
        }

        return substr($brief, 0, 100);
    }

    /**
     * Calculate package weight
     */
    private function calculateWeight($order)
    {
        // Default weight calculation: 0.3kg per item
        $totalItems = $order->items->sum('quantity');
        return max(0.3, $totalItems * 0.3);
    }
}
