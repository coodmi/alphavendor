<?php

namespace App\Services;

use App\Models\CommissionInvoice;
use App\Models\CommissionInvoiceItem;
use App\Models\SellerViolation;
use App\Models\Order;

class CommissionInvoiceService
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Generate invoice for an order
     */
    public function generateInvoice(Order $order)
    {
        $seller = $order->vendor;
        
        // Calculate commission with penalties
        $commissionData = $this->commissionService
            ->calculateOrderCommissionWithPenalties($order, $seller);
        
        // Create invoice
        $invoice = CommissionInvoice::create([
            'invoice_number' => CommissionInvoice::generateInvoiceNumber(),
            'order_id' => $order->id,
            'seller_id' => $seller->id,
            'invoice_date' => now(),
            'order_amount' => $order->subtotal,
            'delivery_charge' => $order->delivery_charge ?? 0,
            'category_commission_rate' => $commissionData['category_commission_rate'],
            'category_commission_amount' => $commissionData['category_commission'],
            'cod_commission_rate' => $commissionData['cod_commission_rate'],
            'cod_commission_amount' => $commissionData['cod_commission'],
            'total_commission' => $commissionData['total_commission'],
            'penalty_amount' => $commissionData['penalties'],
            'total_deduction' => $commissionData['total_deduction'],
            'net_vendor_earning' => $commissionData['net_earning'],
            'status' => 'finalized'
        ]);
        
        // Create invoice items
        $this->createInvoiceItems($invoice, $order, $commissionData);
        
        return $invoice;
    }

    /**
     * Create invoice items breakdown
     */
    protected function createInvoiceItems($invoice, $order, $commissionData)
    {
        // Category commission item
        if ($commissionData['category_commission'] > 0) {
            CommissionInvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'category_commission',
                'description' => 'Category Commission (' . number_format($commissionData['category_commission_rate'], 2) . '%)',
                'amount' => $commissionData['category_commission']
            ]);
        }
        
        // COD commission item
        if ($commissionData['cod_commission'] > 0) {
            CommissionInvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'cod_commission',
                'description' => 'COD Commission (' . number_format($commissionData['cod_commission_rate'], 2) . '%) - Excluding Delivery Charge',
                'amount' => $commissionData['cod_commission']
            ]);
        }
        
        // Penalty items
        if ($commissionData['penalties'] > 0) {
            $violations = SellerViolation::where('seller_id', $invoice->seller_id)
                ->where('order_id', $order->id)
                ->where('status', 'pending')
                ->with('rule')
                ->get();
            
            foreach ($violations as $violation) {
                CommissionInvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_type' => 'penalty',
                    'description' => 'Penalty: ' . $violation->rule->rule_name_en,
                    'amount' => $violation->penalty_amount
                ]);
                
                // Mark violation as applied
                $violation->markAsApplied();
            }
        }
    }

    /**
     * Get invoice for an order
     */
    public function getInvoiceForOrder($orderId)
    {
        return CommissionInvoice::where('order_id', $orderId)
            ->with(['seller', 'order', 'items'])
            ->first();
    }

    /**
     * Get all invoices for a seller
     */
    public function getInvoicesForSeller($sellerId, $status = null)
    {
        $query = CommissionInvoice::where('seller_id', $sellerId)
            ->with(['order', 'items']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->latest()->get();
    }
}
