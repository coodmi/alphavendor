<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnRequest;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorReportService
{
    private const DELIVERED_STATUSES = ['delivered', 'completed'];

    private const COMPLETED_RETURN_STATUSES = ['refunded', 'completed', 'received'];

    private const RETURN_DEDUCTION_TYPES = ['return', 'refund'];

    public function build(Request $request): array
    {
        $vendorId = auth()->id();

        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $productIds = Product::where('vendor_id', $vendorId)->pluck('id');

        $ordersQuery = Order::where('vendor_id', $vendorId)
            ->whereBetween('created_at', [$start, $end]);

        $totalOrders = (clone $ordersQuery)->count();
        $todayOrders = Order::where('vendor_id', $vendorId)->whereDate('created_at', today())->count();
        $yesterdayOrders = Order::where('vendor_id', $vendorId)->whereDate('created_at', today()->subDay())->count();

        $productSalesGross = (int) DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->whereIn('order_id', function ($query) use ($vendorId, $start, $end) {
                $query->select('id')
                    ->from('orders')
                    ->where('vendor_id', $vendorId)
                    ->whereIn('status', self::DELIVERED_STATUSES)
                    ->whereBetween('created_at', [$start, $end]);
            })
            ->sum('quantity');

        $returnedQuantity = (int) $this->completedReturnsQuery($vendorId, $start, $end)->sum('quantity');
        $productSales = max(0, $productSalesGross - $returnedQuantity);

        $grossRevenue = (float) DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->whereIn('order_id', function ($query) use ($vendorId, $start, $end) {
                $query->select('id')
                    ->from('orders')
                    ->where('vendor_id', $vendorId)
                    ->whereIn('status', self::DELIVERED_STATUSES)
                    ->whereBetween('created_at', [$start, $end]);
            })
            ->sum('subtotal');

        $totalRefundAmount = (float) $this->completedReturnsQuery($vendorId, $start, $end)
            ->sum(DB::raw('COALESCE(refund_amount, amount)'));

        $netRevenue = max(0, $grossRevenue - $totalRefundAmount);

        $productWishlist = Wishlist::whereIn('product_id', $productIds)->count();
        $totalStock = (int) Product::where('vendor_id', $vendorId)->sum('stock');
        $lowStock = Product::where('vendor_id', $vendorId)->where('stock', '<=', 10)->count();

        $returnsInRange = ReturnRequest::where('vendor_id', $vendorId)
            ->whereBetween('created_at', [$start, $end]);

        $totalReturns = (clone $returnsInRange)->count();
        $todayReturns = ReturnRequest::where('vendor_id', $vendorId)->whereDate('created_at', today())->count();

        $completedReturns = (clone $returnsInRange)
            ->whereIn('status', self::COMPLETED_RETURN_STATUSES)
            ->whereIn('type', self::RETURN_DEDUCTION_TYPES)
            ->count();

        $totalCancelled = (clone $ordersQuery)->where('status', 'cancelled')->count();
        $todayCancelled = Order::where('vendor_id', $vendorId)
            ->where('status', 'cancelled')
            ->whereDate('updated_at', today())
            ->count();

        $exchangeQuery = ReturnRequest::where('vendor_id', $vendorId)->where('type', 'exchange');
        $totalExchange = (clone $exchangeQuery)->whereBetween('created_at', [$start, $end])->count();
        $todayExchange = (clone $exchangeQuery)->whereDate('created_at', today())->count();

        $ordersByDate = Order::where('vendor_id', $vendorId)
            ->whereBetween('created_at', [$start, $end])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $ordersByStatus = Order::where('vendor_id', $vendorId)
            ->whereBetween('created_at', [$start, $end])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $returnedByProduct = $this->completedReturnsQuery($vendorId, $start, $end)
            ->select('product_id', DB::raw('SUM(quantity) as returned_qty'))
            ->groupBy('product_id')
            ->pluck('returned_qty', 'product_id');

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('order_items.order_id', function ($query) use ($vendorId, $start, $end) {
                $query->select('id')
                    ->from('orders')
                    ->where('vendor_id', $vendorId)
                    ->whereIn('status', self::DELIVERED_STATUSES)
                    ->whereBetween('created_at', [$start, $end]);
            })
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as gross_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('gross_sold')
            ->limit(15)
            ->get()
            ->map(function ($product) use ($returnedByProduct) {
                $returned = (int) ($returnedByProduct[$product->id] ?? 0);
                $product->total_sold = max(0, (int) $product->gross_sold - $returned);
                $product->returned = $returned;

                return $product;
            })
            ->sortByDesc('total_sold')
            ->take(10)
            ->values();

        return compact(
            'totalOrders',
            'todayOrders',
            'yesterdayOrders',
            'productSales',
            'productSalesGross',
            'returnedQuantity',
            'grossRevenue',
            'netRevenue',
            'totalRefundAmount',
            'productWishlist',
            'totalStock',
            'lowStock',
            'totalReturns',
            'todayReturns',
            'completedReturns',
            'totalCancelled',
            'todayCancelled',
            'totalExchange',
            'todayExchange',
            'ordersByDate',
            'ordersByStatus',
            'topProducts',
            'startDate',
            'endDate'
        );
    }

    private function completedReturnsQuery(int $vendorId, Carbon $start, Carbon $end)
    {
        return ReturnRequest::where('vendor_id', $vendorId)
            ->whereIn('status', self::COMPLETED_RETURN_STATUSES)
            ->whereIn('type', self::RETURN_DEDUCTION_TYPES)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('refund_processed_at', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->whereNull('refund_processed_at')
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            });
    }
}
