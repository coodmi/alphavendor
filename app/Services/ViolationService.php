<?php

namespace App\Services;

use App\Models\SellerViolation;
use App\Models\ViolationRule;
use App\Models\ViolationNotification;
use App\Models\User;

class ViolationService
{
    /**
     * Create a violation for a seller
     */
    public function createViolation($sellerId, $ruleCode, $orderId = null, $customPenalty = null)
    {
        $rule = ViolationRule::where('rule_code', $ruleCode)
            ->where('is_active', true)
            ->first();
        
        if (!$rule) {
            throw new \Exception("Violation rule '{$ruleCode}' not found or inactive");
        }
        
        // Calculate penalty
        $penaltyAmount = $customPenalty ?? $rule->penalty_amount;
        
        // If percentage-based, calculate from order amount
        if ($rule->penalty_type === 'percentage' && $orderId) {
            $order = \App\Models\Order::find($orderId);
            if ($order) {
                $penaltyAmount = $rule->calculatePenalty($order->subtotal);
            }
        }
        
        // Create violation
        $violation = SellerViolation::create([
            'seller_id' => $sellerId,
            'order_id' => $orderId,
            'rule_id' => $rule->id,
            'violation_date' => now(),
            'penalty_amount' => $penaltyAmount,
            'status' => 'pending'
        ]);
        
        // Notify admins and employees
        $this->notifyAdmins($violation);
        
        return $violation;
    }

    /**
     * Notify all admins and employees about violation
     */
    protected function notifyAdmins($violation)
    {
        // Get all admins and employees
        $recipients = User::whereIn('role', ['admin', 'employee', 'manager', 'supervisor'])
            ->get();
        
        foreach ($recipients as $recipient) {
            ViolationNotification::create([
                'violation_id' => $violation->id,
                'recipient_id' => $recipient->id,
                'is_read' => false
            ]);
        }
        
        // Mark violation as notified
        $violation->update(['notified_at' => now()]);
    }

    /**
     * Get pending violations count for dashboard
     */
    public function getPendingViolationsCount()
    {
        return SellerViolation::where('status', 'pending')->count();
    }

    /**
     * Get recent violations for dashboard
     */
    public function getRecentViolations($limit = 10)
    {
        return SellerViolation::with(['seller', 'rule', 'order'])
            ->where('status', 'pending')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Approve violation (apply penalty)
     */
    public function approveViolation($violationId, $adminNotes = null)
    {
        $violation = SellerViolation::findOrFail($violationId);
        
        $violation->update([
            'status' => 'reviewed',
            'admin_notes' => $adminNotes,
            'resolved_at' => now()
        ]);
        
        return $violation;
    }

    /**
     * Waive violation (cancel penalty)
     */
    public function waiveViolation($violationId, $adminNotes)
    {
        $violation = SellerViolation::findOrFail($violationId);
        $violation->markAsWaived($adminNotes);
        
        return $violation;
    }
}
