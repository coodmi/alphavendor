<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['vendor', 'order'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(25);

        $stats = [
            'total_revenue' => Transaction::where('status', 'completed')->where('type', 'sale')->sum('amount'),
            'completed'     => Transaction::where('status', 'completed')->count(),
            'pending'       => Transaction::where('status', 'pending')->count(),
            'cancelled'     => Transaction::where('status', 'cancelled')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }
}
