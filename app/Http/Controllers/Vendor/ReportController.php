<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Reports dashboard
     */
    public function index()
    {
        $vendorId = auth()->id();
        
        // Quick stats
        $stats = [
            'total_sales' => OrderItem::whereHas('order', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId)
                  ->whereIn('status', ['delivered', 'completed']);
            })->sum('subtotal'),
            
            'total_orders' => Order::where('vendor_id', $vendorId)
                ->whereIn('status', ['delivered', 'completed'])
                ->count(),
            
            'total_products' => Product::where('vendor_id', $vendorId)->count(),
            
            'total_commission' => Transaction::where('vendor_id', $vendorId)
                ->where('type', 'sale')
                ->sum('amount'),
        ];
        
        return view('vendor.reports.index', compact('stats'));
    }
    
    /**
     * Product Sales Report
     */
    public function productSales(Request $request)
    {
        $vendorId = auth()->id();
        
        $query = Product::where('vendor_id', $vendorId)
            ->withCount(['orderItems as total_sold' => function($q) {
                $q->whereHas('order', function($oq) {
                    $oq->whereIn('status', ['delivered', 'completed']);
                });
            }])
            ->withSum(['orderItems as total_revenue' => function($q) {
                $q->whereHas('order', function($oq) {
                    $oq->whereIn('status', ['delivered', 'completed']);
                });
            }], 'subtotal');
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->whereHas('orderItems.order', function($q) use ($request) {
                $q->where('created_at', '>=', $request->date_from);
            });
        }
        
        if ($request->filled('date_to')) {
            $query->whereHas('orderItems.order', function($q) use ($request) {
                $q->where('created_at', '<=', $request->date_to . ' 23:59:59');
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'total_sold');
        if ($sortBy === 'total_sold') {
            $query->orderByDesc('total_sold');
        } elseif ($sortBy === 'total_revenue') {
            $query->orderByDesc('total_revenue');
        }
        
        $products = $query->paginate(20);
        
        return view('vendor.reports.product-sales', compact('products'));
    }
    
    /**
     * Product Wishlist Report
     */
    public function productWishlist(Request $request)
    {
        $vendorId = auth()->id();
        
        $query = Product::where('vendor_id', $vendorId)
            ->withCount('wishlists')
            ->having('wishlists_count', '>', 0)
            ->orderByDesc('wishlists_count');
        
        $products = $query->paginate(20);
        
        return view('vendor.reports.product-wishlist', compact('products'));
    }
    
    /**
     * Product Stock Report
     */
    public function productStock(Request $request)
    {
        $vendorId = auth()->id();
        
        $query = Product::where('vendor_id', $vendorId);
        
        // Filter by stock status
        $filter = $request->get('filter', 'all');
        if ($filter === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($filter === 'out_of_stock') {
            $query->where('stock', 0);
        } elseif ($filter === 'in_stock') {
            $query->where('stock', '>', 10);
        }
        
        $products = $query->orderBy('stock', 'asc')->paginate(20);
        
        // Stats
        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $inStock = Product::where('vendor_id', $vendorId)->where('stock', '>', 10)->count();
        $lowStock = Product::where('vendor_id', $vendorId)->where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $outOfStock = Product::where('vendor_id', $vendorId)->where('stock', 0)->count();
        
        return view('vendor.reports.product-stock', compact('products', 'totalProducts', 'inStock', 'lowStock', 'outOfStock'));
    }
    
    /**
     * Commission History Report
     */
    public function commissionHistory(Request $request)
    {
        $vendorId = auth()->id();
        
        $query = Order::where('vendor_id', $vendorId)
            ->with(['user'])
            ->latest();
        
        // Date filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(20);
        
        // Stats
        $totalOrders = Order::where('vendor_id', $vendorId)->count();
        
        $totalSales = Order::where('vendor_id', $vendorId)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total');
        
        $totalCommission = Order::where('vendor_id', $vendorId)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('commission_amount');
        
        $totalEarnings = Order::where('vendor_id', $vendorId)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('vendor_earning');
        
        return view('vendor.reports.commission-history', compact('orders', 'totalOrders', 'totalSales', 'totalCommission', 'totalEarnings'));
    }
    
    /**
     * Export report to CSV
     */
    public function export(Request $request, $type)
    {
        $vendorId = auth()->id();
        
        switch ($type) {
            case 'sales':
                return $this->exportSales($vendorId);
            case 'wishlist':
                return $this->exportWishlist($vendorId);
            case 'stock':
                return $this->exportStock($vendorId);
            case 'commission':
                return $this->exportCommission($vendorId);
            default:
                abort(404);
        }
    }
    
    private function exportSales($vendorId)
    {
        $products = Product::where('vendor_id', $vendorId)
            ->withCount(['orderItems as total_sold'])
            ->withSum(['orderItems as total_revenue'], 'subtotal')
            ->get();
        
        $filename = 'product-sales-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Product Name', 'SKU', 'Total Sold', 'Total Revenue', 'Stock']);
        
        foreach ($products as $product) {
            fputcsv($output, [
                $product->name,
                $product->sku,
                $product->total_sold ?? 0,
                number_format($product->total_revenue ?? 0, 2),
                $product->stock,
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function exportWishlist($vendorId)
    {
        $products = Product::where('vendor_id', $vendorId)
            ->withCount('wishlists')
            ->get();
        
        $filename = 'product-wishlist-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Product Name', 'SKU', 'Wishlist Count', 'Price', 'Stock']);
        
        foreach ($products as $product) {
            fputcsv($output, [
                $product->name,
                $product->sku,
                $product->wishlists_count ?? 0,
                number_format($product->retail_price ?? 0, 2),
                $product->stock,
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function exportStock($vendorId)
    {
        $products = Product::where('vendor_id', $vendorId)->get();
        
        $filename = 'product-stock-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Product Name', 'SKU', 'Stock', 'Status', 'Price']);
        
        foreach ($products as $product) {
            $status = $product->stock == 0 ? 'Out of Stock' : ($product->stock <= 10 ? 'Low Stock' : 'In Stock');
            
            fputcsv($output, [
                $product->name,
                $product->sku,
                $product->stock,
                $status,
                number_format($product->retail_price ?? 0, 2),
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function exportCommission($vendorId)
    {
        $transactions = Transaction::where('vendor_id', $vendorId)->get();
        
        $filename = 'commission-history-' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Transaction #', 'Type', 'Amount', 'Status', 'Date', 'Description']);
        
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction->transaction_number,
                ucfirst($transaction->type),
                number_format($transaction->amount, 2),
                ucfirst($transaction->status),
                $transaction->created_at->format('Y-m-d H:i:s'),
                $transaction->description,
            ]);
        }
        
        fclose($output);
        exit;
    }
}
