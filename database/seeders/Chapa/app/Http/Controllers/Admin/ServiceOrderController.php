<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AddOrderNoteRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\OrderNote;
use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of service orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')
            ->serviceOrders()
            ->latest();

        // Apply status filter
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Apply date range filter
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $orders = $query->paginate(20)->appends($request->all());

        $filters = [
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'search' => $request->search,
        ];

        return view('admin.service-orders.index', compact('orders', 'filters'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items', 'user', 'notes.admin']);

        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return view('admin.service-orders.show', compact('order', 'statuses'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Add a note to the order.
     */
    public function addNote(AddOrderNoteRequest $request, Order $order)
    {
        OrderNote::create([
            'order_id' => $order->id,
            'admin_id' => auth()->id(),
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Note added successfully!');
    }
}
