<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\DeliveryPenaltyRule;
use App\Models\VendorWallet;
use App\Models\Transaction;
use App\Models\AdminReminder;
use Carbon\Carbon;

class CheckOrderViolations extends Command
{
    protected $signature   = 'violations:check-orders';
    protected $description = 'Check for late delivery violations and deduct penalties from seller wallets';

    public function handle(): int
    {
        $this->info('Checking for late delivery violations...');

        $penaltyCount = $this->applyLateDeliveryPenalties();

        $this->info("Done. Applied {$penaltyCount} penalty(ies).");
        return 0;
    }

    /**
     * Find orders that are confirmed but not yet delivered/cancelled,
     * match them against active penalty rules, deduct wallet, and notify seller.
     */
    protected function applyLateDeliveryPenalties(): int
    {
        $rules = DeliveryPenaltyRule::activeRules();

        if ($rules->isEmpty()) {
            $this->warn('No active penalty rules found. Skipping.');
            return 0;
        }

        $count = 0;

        // Orders that are confirmed but NOT delivered/cancelled and penalty not yet applied
        $orders = Order::whereNotNull('confirmed_at')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->where('penalty_applied', false)
            ->with(['vendor', 'user'])
            ->get();

        foreach ($orders as $order) {
            $daysLate = (int) Carbon::parse($order->confirmed_at)->diffInDays(now());

            // Find the applicable rule for this many days late
            $rule = DeliveryPenaltyRule::findApplicable($daysLate);

            if (!$rule) {
                continue; // Not late enough yet
            }

            $penaltyAmount = $rule->penalty_amount;
            $vendorId      = $order->vendor_id;

            // ── 1. Deduct from wallet ─────────────────────────────────────────
            $wallet = VendorWallet::firstOrCreate(
                ['vendor_id' => $vendorId],
                ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
            );

            // Deduct from balance (can go negative — admin can see)
            $wallet->decrement('balance', $penaltyAmount);

            // ── 2. Record transaction ─────────────────────────────────────────
            Transaction::create([
                'vendor_id'          => $vendorId,
                'order_id'           => $order->id,
                'transaction_number' => 'PEN-' . strtoupper(uniqid()),
                'type'               => 'penalty',
                'amount'             => $penaltyAmount,
                'status'             => 'completed',
                'description'        => "Late delivery penalty for Order #{$order->order_number} ({$daysLate} days after confirmation)",
            ]);

            // ── 3. Mark order so we don't double-penalise ─────────────────────
            $order->update(['penalty_applied' => true]);

            // ── 4. Send reminder to seller's inbox ───────────────────────────
            $this->notifySeller($order, $daysLate, $penaltyAmount, $rule->description);

            $count++;
            $this->line("Penalty ৳{$penaltyAmount} applied to vendor #{$vendorId} for Order #{$order->order_number} ({$daysLate} days late)");
        }

        return $count;
    }

    /**
     * Send a reminder to the seller's inbox (AdminReminder system).
     */
    protected function notifySeller(Order $order, int $daysLate, float $penaltyAmount, ?string $ruleDesc): void
    {
        try {
            $title   = "⚠️ Late Delivery Penalty — Order #{$order->order_number}";
            $message = "আপনার Order #{$order->order_number} টি Order Confirmed হওয়ার পর {$daysLate} দিন অতিবাহিত হয়েছে কিন্তু এখনো Delivered হয়নি।\n\n"
                     . "নিয়ম: {$ruleDesc}\n"
                     . "জরিমানা: ৳" . number_format($penaltyAmount, 2) . "\n\n"
                     . "এই পরিমাণ আপনার wallet থেকে কেটে নেওয়া হয়েছে। দ্রুত delivery নিশ্চিত করুন।";

            // Create AdminReminder record (system sender — use first admin)
            $adminId = \App\Models\User::where('role', 'admin')->value('id') ?? 1;

            $reminder = AdminReminder::create([
                'sender_id'      => $adminId,
                'title'          => $title,
                'message'        => $message,
                'type'           => 'error',
                'recipient_type' => 'specific',
                'recipient_role' => null,
            ]);

            $reminder->recipients()->attach([
                $order->vendor_id => ['read_at' => null],
            ]);

            // Also push to bell notification
            \App\Models\Notification::create([
                'user_id' => $order->vendor_id,
                'type'    => 'error',
                'title'   => $title,
                'message' => "Order #{$order->order_number} — ৳" . number_format($penaltyAmount, 2) . " জরিমানা কাটা হয়েছে।",
                'data'    => ['url' => '/seller/reminders'],
            ]);
        } catch (\Throwable $e) {
            \Log::error("Penalty notification failed for order {$order->id}: " . $e->getMessage());
        }
    }
}
