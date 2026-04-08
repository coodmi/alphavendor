<?php

namespace App\Services;

use App\Models\CommissionSetting;
use App\Models\CodCommissionSetting;
use App\Models\User;
use App\Models\Product;

class CommissionService
{
    /**
     * Calculate commission for an order item
     */
    public function calculateItemCommission($product, $quantity, $price, $vendorId, $paymentMethod = null, $deliveryCharge = 0)
    {
        $itemTotal = $price * $quantity;
        
        // Get vendor (seller) type
        $vendor = User::find($vendorId);
        $sellerType = $this->getSellerType($vendor);
        
        // Get category-based commission
        $categoryCommissionRate = CommissionSetting::getCommissionRate($product->category_id, $sellerType);
        $categoryCommissionAmount = ($itemTotal * $categoryCommissionRate) / 100;
        
        // Calculate COD commission if applicable
        $codCommissionAmount = 0;
        $codCommissionRate = 0;
        
        if ($this->isCodPayment($paymentMethod) && CodCommissionSetting::isEnabled()) {
            $codCommissionRate = CodCommissionSetting::getActiveRate();
            // COD commission = (Item Total) × COD Rate
            // Note: Delivery charge is excluded at order level, not item level
            $codCommissionAmount = ($itemTotal * $codCommissionRate) / 100;
        }
        
        $totalCommission = $categoryCommissionAmount + $codCommissionAmount;
        $vendorEarning = $itemTotal - $totalCommission;
        
        return [
            'item_total' => $itemTotal,
            'category_commission_rate' => $categoryCommissionRate,
            'category_commission_amount' => $categoryCommissionAmount,
            'cod_commission_rate' => $codCommissionRate,
            'cod_commission_amount' => $codCommissionAmount,
            'total_commission' => $totalCommission,
            'vendor_earning' => $vendorEarning
        ];
    }

    /**
     * Calculate total commission for an order
     */
    public function calculateOrderCommission($items, $vendorId, $paymentMethod, $deliveryCharge = 0)
    {
        $subtotal = 0;
        $totalCategoryCommission = 0;
        $totalCodCommission = 0;
        $itemsWithCommission = [];
        
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            
            $commission = $this->calculateItemCommission(
                $product,
                $item['quantity'],
                $item['price'],
                $vendorId,
                $paymentMethod,
                $deliveryCharge
            );
            
            $subtotal += $commission['item_total'];
            $totalCategoryCommission += $commission['category_commission_amount'];
            $totalCodCommission += $commission['cod_commission_amount'];
            
            $itemsWithCommission[] = array_merge($item, $commission);
        }
        
        $totalCommission = $totalCategoryCommission + $totalCodCommission;
        $vendorEarning = $subtotal - $totalCommission;
        
        // Calculate average commission rate
        $avgCommissionRate = $subtotal > 0 ? ($totalCategoryCommission / $subtotal) * 100 : 0;
        $codCommissionRate = $subtotal > 0 ? ($totalCodCommission / $subtotal) * 100 : 0;
        
        return [
            'subtotal' => $subtotal,
            'category_commission_amount' => $totalCategoryCommission,
            'category_commission_rate' => $avgCommissionRate,
            'cod_commission_amount' => $totalCodCommission,
            'cod_commission_rate' => $codCommissionRate,
            'total_commission' => $totalCommission,
            'vendor_earning' => $vendorEarning,
            'items' => $itemsWithCommission
        ];
    }

    /**
     * Calculate order commission with penalties
     * This includes violations and penalties
     */
    public function calculateOrderCommissionWithPenalties($order, $seller)
    {
        // Get basic commission data from order
        $categoryCommission = $order->commission_amount ?? 0;
        $codCommission = $order->cod_commission_amount ?? 0;
        
        // Calculate penalties from violations
        $penalties = $this->calculatePenalties($seller, $order);
        
        // Calculate totals
        $totalCommission = $categoryCommission + $codCommission;
        $totalDeduction = $totalCommission + $penalties;
        $netEarning = $order->subtotal - $totalDeduction;
        
        return [
            'category_commission' => $categoryCommission,
            'category_commission_rate' => $order->commission_rate ?? 0,
            'cod_commission' => $codCommission,
            'cod_commission_rate' => $order->cod_commission_rate ?? 0,
            'total_commission' => $totalCommission,
            'penalties' => $penalties,
            'total_deduction' => $totalDeduction,
            'net_earning' => $netEarning
        ];
    }

    /**
     * Calculate total penalties for a seller on an order
     */
    protected function calculatePenalties($seller, $order)
    {
        $totalPenalty = 0;
        
        // Get pending violations for this order
        $violations = \App\Models\SellerViolation::where('seller_id', $seller->id)
            ->where('order_id', $order->id)
            ->where('status', 'pending')
            ->with('rule')
            ->get();
        
        foreach ($violations as $violation) {
            $totalPenalty += $violation->penalty_amount;
        }
        
        return $totalPenalty;
    }
    
    /**
     * Get seller type from user role
     */
    private function getSellerType($vendor)
    {
        if (!$vendor) {
            return 'retailer'; // Default
        }
        
        // Map role to seller type
        $roleMapping = [
            'retailer' => 'retailer',
            'wholesaler' => 'wholesaler',
            'importer' => 'importer',
            'exporter' => 'importer', // Treat exporter as importer for commission
        ];
        
        return $roleMapping[$vendor->role] ?? 'retailer';
    }
    
    /**
     * Check if payment method is COD
     */
    private function isCodPayment($paymentMethod)
    {
        $codMethods = ['cod', 'cash_on_delivery', 'cash'];
        return in_array(strtolower($paymentMethod ?? ''), $codMethods);
    }
}
