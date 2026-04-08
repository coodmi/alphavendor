<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorReturnController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Build query based on user role
        if ($user->role === 'employee' || $user->role === 'admin') {
            // Employees and admins can see all returns
            $query = ReturnRequest::with(['user', 'product', 'order', 'orderItem', 'vendor']);
        } else {
            // Vendors only see their own returns
            $query = ReturnRequest::where('vendor_id', $user->id)
                ->with(['user', 'product', 'order', 'orderItem']);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->latest()->paginate(20);

        // Calculate stats based on user role
        if ($user->role === 'employee' || $user->role === 'admin') {
            $stats = [
                'total' => ReturnRequest::count(),
                'pending' => ReturnRequest::where('status', 'pending')->count(),
                'approved' => ReturnRequest::where('status', 'approved')->count(),
                'rejected' => ReturnRequest::where('status', 'rejected')->count(),
                'completed' => ReturnRequest::whereIn('status', ['refunded', 'completed'])->count(),
                'total_amount' => ReturnRequest::whereIn('status', ['approved', 'refunded', 'completed'])
                    ->sum('refund_amount'),
            ];
        } else {
            $stats = [
                'total' => ReturnRequest::where('vendor_id', $user->id)->count(),
                'pending' => ReturnRequest::where('vendor_id', $user->id)->where('status', 'pending')->count(),
                'approved' => ReturnRequest::where('vendor_id', $user->id)->where('status', 'approved')->count(),
                'rejected' => ReturnRequest::where('vendor_id', $user->id)->where('status', 'rejected')->count(),
                'completed' => ReturnRequest::where('vendor_id', $user->id)->whereIn('status', ['refunded', 'completed'])->count(),
                'total_amount' => ReturnRequest::where('vendor_id', $user->id)
                    ->whereIn('status', ['approved', 'refunded', 'completed'])
                    ->sum('refund_amount'),
            ];
        }

        return view('vendor.returns.index', compact('returns', 'stats'));
    }

    public function show(ReturnRequest $return)
    {
        $user = auth()->user();
        
        // Check authorization
        if (!in_array($user->role, ['admin', 'employee']) && $return->vendor_id !== $user->id) {
            abort(403);
        }

        $return->load(['user', 'product', 'order', 'orderItem', 'exchangeProduct', 'vendor']);

        return view('vendor.returns.show', compact('return'));
    }

    public function updateStatus(Request $request, ReturnRequest $return)
    {
        $user = auth()->user();
        
        // Check authorization
        if (!in_array($user->role, ['admin', 'employee']) && $return->vendor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,refunded,completed',
            'vendor_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $return->update([
                'status' => $request->status,
                'vendor_notes' => $request->vendor_notes ?? $return->vendor_notes,
                'processed_at' => now(),
            ]);

            // If approved and type is refund, calculate refund amount
            if ($request->status === 'approved' && $return->type === 'refund' && !$return->refund_amount) {
                $return->update([
                    'refund_amount' => $return->orderItem->subtotal ?? 0,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Return status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function addNote(Request $request, ReturnRequest $return)
    {
        $user = auth()->user();
        
        // Check authorization
        if (!in_array($user->role, ['admin', 'employee']) && $return->vendor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'vendor_notes' => 'required|string|max:500',
        ]);

        $return->update([
            'vendor_notes' => $request->vendor_notes,
        ]);

        return back()->with('success', 'Note added successfully');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'return_ids' => 'required|array',
            'action' => 'required|in:approve,reject,complete',
        ]);

        $returns = ReturnRequest::where('vendor_id', auth()->id())
            ->whereIn('id', $request->return_ids)
            ->get();

        $statusMap = [
            'approve' => 'approved',
            'reject' => 'rejected',
            'complete' => 'completed',
        ];

        foreach ($returns as $return) {
            $return->update([
                'status' => $statusMap[$request->action],
                'processed_at' => now(),
            ]);
        }

        return back()->with('success', count($returns) . ' returns updated successfully');
    }
}
