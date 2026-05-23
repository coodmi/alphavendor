<?php

namespace App\Services;

use App\Models\DeliverySetting;
use App\Models\DistrictDeliveryCharge;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DeliveryCalculationService
{
    /**
     * Calculate delivery for one seller's cart lines (same vendor group).
     *
     * @param  array<int, array{id: int, quantity: int, vendor_id?: int, vendor_role?: string}>  $items
     */
    public function calculateForVendorItems(array $items, string $district): array
    {
        $baseCharge = DistrictDeliveryCharge::baseChargeForDistrict($district);
        $extraPerKg = (float) DeliverySetting::current()->extra_per_kg_charge;

        $totalWeight = 0.0;
        $importCostTotal = 0.0;
        $importLines = [];
        $productWeights = [];
        $productsById = [];

        foreach ($items as $item) {
            $productId = (int) $item['id'];
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            if (! isset($productsById[$productId])) {
                $productsById[$productId] = Product::with('vendor')->find($productId);
            }

            $product = $productsById[$productId];
            if (! $product) {
                continue;
            }

            $unitWeight = max(0, (float) ($product->weight_kg ?? 0));
            $lineWeight = $unitWeight * $quantity;
            $totalWeight += $lineWeight;

            if (! isset($productWeights[$productId])) {
                $productWeights[$productId] = 0;
            }
            $productWeights[$productId] += $lineWeight;

            if ($this->isImportProduct($product)) {
                $cost = (float) ($product->bangladesh_import_cost ?? 0);
                if ($cost > 0 && ! isset($importLines[$productId])) {
                    $importLines[$productId] = [
                        'product_id' => $productId,
                        'product_name' => $product->name,
                        'import_country' => $product->import_country,
                        'import_cost' => $cost,
                    ];
                    $importCostTotal += $cost;
                }
            }
        }

        $extraWeight = max(0, $totalWeight - 1);
        $weightCharge = round($extraWeight * $extraPerKg, 2);
        $baseCharge = round($baseCharge, 2);
        $importCostTotal = round($importCostTotal, 2);
        $totalDelivery = round($baseCharge + $weightCharge + $importCostTotal, 2);

        $hasImport = count($importLines) > 0;

        return [
            'district' => $district,
            'base_charge' => $baseCharge,
            'weight_charge' => $weightCharge,
            'import_cost' => $importCostTotal,
            'total_weight_kg' => round($totalWeight, 3),
            'extra_per_kg' => $extraPerKg,
            'delivery_charge' => $totalDelivery,
            'is_import_order' => $hasImport,
            'import_lines' => array_values($importLines),
            'product_weights' => $productWeights,
        ];
    }

    /**
     * Calculate delivery for full cart grouped by vendor (checkout preview / multi-order).
     *
     * @param  array<int, array>  $cartItems
     */
    public function calculateForCart(array $cartItems, string $district): array
    {
        $byVendor = [];
        foreach ($cartItems as $item) {
            $vendorId = $item['vendor_id'] ?? 0;
            $byVendor[$vendorId][] = $item;
        }

        $vendorBreakdowns = [];
        $totalDelivery = 0.0;
        $totalBase = 0.0;
        $totalWeight = 0.0;
        $totalImport = 0.0;
        $hasImport = false;
        $allImportLines = [];

        foreach ($byVendor as $vendorId => $items) {
            $result = $this->calculateForVendorItems($items, $district);
            $vendor = User::find($vendorId);
            $result['vendor_id'] = $vendorId;
            $result['vendor_name'] = $vendor->name ?? 'Seller';
            $result['vendor_role'] = $vendor->role ?? ($items[0]['vendor_role'] ?? 'retailer');

            $vendorBreakdowns[] = $result;
            $totalDelivery += $result['delivery_charge'];
            $totalBase += $result['base_charge'];
            $totalWeight += $result['weight_charge'];
            $totalImport += $result['import_cost'];

            if ($result['is_import_order']) {
                $hasImport = true;
                $allImportLines = array_merge($allImportLines, $result['import_lines']);
            }
        }

        $totalWeightKg = round(array_sum(array_map(fn ($v) => $v['total_weight_kg'] ?? 0, $vendorBreakdowns)), 3);

        return [
            'district' => $district,
            'base_charge' => round($totalBase, 2),
            'weight_charge' => round($totalWeight, 2),
            'import_cost' => round($totalImport, 2),
            'delivery_charge' => round($totalDelivery, 2),
            'total_weight_kg' => $totalWeightKg,
            'is_import_order' => $hasImport,
            'import_lines' => $allImportLines,
            'vendor_breakdowns' => $vendorBreakdowns,
        ];
    }

    public function isImportProduct(Product $product): bool
    {
        $role = $product->vendor->role ?? null;

        return $role === 'importer' || ($product->bangladesh_import_cost !== null && (float) $product->bangladesh_import_cost > 0);
    }

    public function productTypeForOrder(Order $order): string
    {
        $order->loadMissing('vendor');

        return match ($order->vendor->role ?? 'retailer') {
            'wholesaler' => 'wholesale',
            'importer' => 'import',
            default => 'retail',
        };
    }
}
