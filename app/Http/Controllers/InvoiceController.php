<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $isVendor = in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer']);
        $field   = $isVendor ? 'vendor_id' : 'user_id';

        $query = Order::with(['items', 'user', 'vendor'])
            ->where($field, $user->id)
            ->latest();

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $invoices = $query->paginate(20)->withQueryString();

        $stats = [
            'delivered'    => Order::where($field, $user->id)->where('status', 'delivered')->count(),
            'pending'      => Order::where($field, $user->id)->where('status', 'pending')->count(),
            'total_amount' => Order::where($field, $user->id)->whereIn('status', ['delivered', 'completed'])->sum('total'),
        ];

        return view('invoices.index', compact('invoices', 'stats', 'user'));
    }
}
