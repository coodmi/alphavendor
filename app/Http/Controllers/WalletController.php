<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorWallet;
use App\Models\Transaction;
use App\Models\Order;

class WalletController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        // Get or create wallet
        $wallet = VendorWallet::firstOrCreate(
            ['vendor_id' => $vendorId],
            ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Get recent transactions
        $transactions = Transaction::where('vendor_id', $vendorId)
            ->with('order')
            ->latest()
            ->paginate(20);

        // Get earnings breakdown
        $thisMonthEarnings = Order::where('vendor_id', $vendorId)
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->sum('vendor_earning');

        $lastMonthEarnings = Order::where('vendor_id', $vendorId)
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('vendor_earning');

        $pendingOrders = Order::where('vendor_id', $vendorId)
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();

        return view('wallet.index', compact('wallet', 'transactions', 'thisMonthEarnings', 'lastMonthEarnings', 'pendingOrders'));
    }
}
