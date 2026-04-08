<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\SellerViolation;
use App\Models\ViolationRule;
use App\Services\ViolationService;
use Carbon\Carbon;

class CheckOrderViolations extends Command
{
    protected $signature = 'violations:check-orders';
    protected $description = 'Check for order-related violations (late delivery, cancellations, etc.)';

    protected $violationService;

    public function __construct(ViolationService $violationService)
    {
        parent::__construct();
        $this->violationService = $violationService;
    }

    public function handle()
    {
        $this->info('Checking for order violations...');

        $violationsCreated = 0;

        // Check for late deliveries (3 days after confirmation)
        $violationsCreated += $this->checkLateDeliveries();

        // Check for excessive cancellations
        $violationsCreated += $this->checkExcessiveCancellations();

        // Check for low product quality (based on reviews)
        $violationsCreated += $this->checkLowQualityProducts();

        // Check for delayed order processing
        $violationsCreated += $this->checkDelayedProcessing();

        // Check for stock unavailability after order
        $violationsCreated += $this->checkStockIssues();

        $this->info("Violations check completed. Created {$violationsCreated} new violations.");

        return 0;
    }

    /**
     * Check for orders not delivered within 3 days of confirmation
     */
    protected function checkLateDeliveries()
    {
        $count = 0;
        $threeDaysAgo = Carbon::now()->subDays(3);

        // Get confirmed orders that are not delivered and older than 3 days
        $lateOrders = Order::where('status', 'confirmed')
            ->where('updated_at', '<=', $threeDaysAgo)
            ->whereDoesntHave('violations', function($query) {
                $query->where('rule_id', function($subQuery) {
                    $subQuery->select('id')
                        ->from('violation_rules')
                        ->where('rule_code', 'LATE_DELIVERY')
                        ->limit(1);
                });
            })
            ->get();

        foreach ($lateOrders as $order) {
            try {
                $this->violationService->createViolation(
                    $order->vendor_id,
                    'LATE_DELIVERY',
                    $order->id
                );
                $count++;
                $this->line("Created late delivery violation for Order #{$order->order_number}");
            } catch (\Exception $e) {
                $this->error("Error creating violation for Order #{$order->order_number}: " . $e->getMessage());
            }
        }

        return $count;
    }

    /**
     * Check for excessive order cancellations
     */
    protected function checkExcessiveCancellations()
    {
        $count = 0;
        $lastMonth = Carbon::now()->subMonth();

        // Get vendors with more than 5 cancellations in the last month
        $vendors = Order::where('status', 'cancelled')
            ->where('created_at', '>=', $lastMonth)
            ->selectRaw('vendor_id, COUNT(*) as cancellation_count')
            ->groupBy('vendor_id')
            ->having('cancellation_count', '>', 5)
            ->get();

        foreach ($vendors as $vendor) {
            // Check if violation already exists for this month
            $existingViolation = SellerViolation::where('seller_id', $vendor->vendor_id)
                ->whereHas('rule', function($query) {
                    $query->where('rule_code', 'EXCESSIVE_CANCELLATIONS');
                })
                ->where('created_at', '>=', $lastMonth)
                ->exists();

            if (!$existingViolation) {
                try {
                    $this->violationService->createViolation(
                        $vendor->vendor_id,
                        'EXCESSIVE_CANCELLATIONS'
                    );
                    $count++;
                    $this->line("Created excessive cancellation violation for Vendor ID: {$vendor->vendor_id}");
                } catch (\Exception $e) {
                    $this->error("Error creating violation: " . $e->getMessage());
                }
            }
        }

        return $count;
    }

    /**
     * Check for low product quality based on reviews
     */
    protected function checkLowQualityProducts()
    {
        $count = 0;
        $lastMonth = Carbon::now()->subMonth();

        // Get products with average rating below 2.5 and at least 5 reviews
        $lowRatedProducts = \App\Models\Review::select('product_id')
            ->where('created_at', '>=', $lastMonth)
            ->groupBy('product_id')
            ->havingRaw('AVG(rating) < 2.5')
            ->havingRaw('COUNT(*) >= 5')
            ->get();

        foreach ($lowRatedProducts as $review) {
            $product = \App\Models\Product::find($review->product_id);
            if (!$product) continue;

            // Check if violation already exists for this product this month
            $existingViolation = SellerViolation::where('seller_id', $product->vendor_id)
                ->whereHas('rule', function($query) {
                    $query->where('rule_code', 'LOW_PRODUCT_QUALITY');
                })
                ->where('created_at', '>=', $lastMonth)
                ->exists();

            if (!$existingViolation) {
                try {
                    $this->violationService->createViolation(
                        $product->vendor_id,
                        'LOW_PRODUCT_QUALITY'
                    );
                    $count++;
                    $this->line("Created low quality violation for Product ID: {$product->id}");
                } catch (\Exception $e) {
                    $this->error("Error creating violation: " . $e->getMessage());
                }
            }
        }

        return $count;
    }

    /**
     * Check for delayed order processing (not confirmed within 24 hours)
     */
    protected function checkDelayedProcessing()
    {
        $count = 0;
        $oneDayAgo = Carbon::now()->subDay();

        // Get pending orders older than 24 hours
        $delayedOrders = Order::where('status', 'pending')
            ->where('created_at', '<=', $oneDayAgo)
            ->whereDoesntHave('violations', function($query) {
                $query->where('rule_id', function($subQuery) {
                    $subQuery->select('id')
                        ->from('violation_rules')
                        ->where('rule_code', 'DELAYED_PROCESSING')
                        ->limit(1);
                });
            })
            ->get();

        foreach ($delayedOrders as $order) {
            try {
                $this->violationService->createViolation(
                    $order->vendor_id,
                    'DELAYED_PROCESSING',
                    $order->id
                );
                $count++;
                $this->line("Created delayed processing violation for Order #{$order->order_number}");
            } catch (\Exception $e) {
                $this->error("Error creating violation: " . $e->getMessage());
            }
        }

        return $count;
    }

    /**
     * Check for stock unavailability issues
     */
    protected function checkStockIssues()
    {
        $count = 0;
        $lastWeek = Carbon::now()->subWeek();

        // Get cancelled orders (assuming stock issues if cancelled quickly)
        $stockIssueOrders = Order::where('status', 'cancelled')
            ->where('created_at', '>=', $lastWeek)
            ->whereDoesntHave('violations', function($query) {
                $query->where('rule_id', function($subQuery) {
                    $subQuery->select('id')
                        ->from('violation_rules')
                        ->where('rule_code', 'STOCK_UNAVAILABLE')
                        ->limit(1);
                });
            })
            ->get();

        foreach ($stockIssueOrders as $order) {
            // Only create violation if order was cancelled within 24 hours (likely stock issue)
            $hoursSinceCreation = $order->created_at->diffInHours($order->updated_at);
            
            if ($hoursSinceCreation <= 24) {
                try {
                    $this->violationService->createViolation(
                        $order->vendor_id,
                        'STOCK_UNAVAILABLE',
                        $order->id
                    );
                    $count++;
                    $this->line("Created stock unavailable violation for Order #{$order->order_number}");
                } catch (\Exception $e) {
                    $this->error("Error creating violation for Order #{$order->order_number}: " . $e->getMessage());
                }
            }
        }

        return $count;
    }
}
