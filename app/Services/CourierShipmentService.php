<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Services\PaperflyService;
use Illuminate\Support\Facades\Log;

class CourierShipmentService
{
    public function __construct(
        protected PaperflyService $paperflyService
    ) {}

    public function canCreateCourierShipment(Order $order): bool
    {
        if ($order->paperfly_tracking_number) {
            return false;
        }

        $order->loadMissing('vendor', 'items');
        $productType = $this->resolveOrderProductType($order);

        if (in_array($productType, ['retail', 'wholesale'], true)) {
            return $order->status === 'order_confirmed';
        }

        if ($productType === 'import') {
            return in_array($order->status, [
                'ready_for_bangladesh_delivery',
            ], true);
        }

        return false;
    }

    public function maybeCreateShipment(Order $order): ?array
    {
        if (! $this->canCreateCourierShipment($order)) {
            return null;
        }

        return $this->createShipment($order);
    }

    public function createShipment(Order $order): array
    {
        $order->load(['user', 'vendor', 'items']);

        $orderData = [
            'order_number' => $order->order_number,
            'store_name' => $order->vendor->name ?? 'Armarketbd',
            'product_brief' => $this->getProductBrief($order),
            'package_price' => $order->total,
            'max_weight' => $this->calculatePackageWeight($order),
            'customer_name' => $order->user->name ?? 'Customer',
            'customer_address' => trim($order->shipping_address . ', ' . $order->shipping_city),
            'customer_phone' => $order->phone,
        ];

        $result = $this->paperflyService->createOrder($orderData);

        if ($result['success'] ?? false) {
            $order->update([
                'paperfly_tracking_number' => $result['tracking_number'],
                'paperfly_merchant_order_ref' => $order->order_number,
                'delivery_status' => 'pending',
            ]);
        } else {
            Log::warning('Courier shipment failed', [
                'order_id' => $order->id,
                'message' => $result['message'] ?? 'unknown',
            ]);
        }

        return $result;
    }

    protected function resolveOrderProductType(Order $order): string
    {
        $role = $order->vendor->role ?? 'retailer';

        return match ($role) {
            'wholesaler' => 'wholesale',
            'importer' => 'import',
            default => 'retail',
        };
    }

    protected function getProductBrief(Order $order): string
    {
        return $order->items->take(3)->pluck('product_name')->implode(', ');
    }

    protected function calculatePackageWeight(Order $order): float
    {
        $weight = 0.0;

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            $unit = $product ? (float) ($product->weight_kg ?? 0) : 0;
            $weight += max($unit, 0.3) * (int) $item->quantity;
        }

        return max(0.3, round($weight, 2));
    }
}
