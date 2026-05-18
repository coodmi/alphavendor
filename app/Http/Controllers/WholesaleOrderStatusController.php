<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidOrderTransitionException;
use App\Exceptions\UnauthorisedOrderTransitionException;
use App\Helpers\OrderStatus;
use App\Models\Order;
use App\Services\WholesaleOrderStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WholesaleOrderStatusController extends Controller
{
    public function __construct(private WholesaleOrderStatusService $service) {}

    /**
     * PATCH /orders/{order}/wholesale-status
     * Update the status of a wholesale or import order.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(OrderStatus::all()))],
        ]);

        // Load vendor relation if not already loaded
        $order->loadMissing('vendor');

        // Only applies to wholesale/import orders
        if (!$order->isWholesaleOrImport()) {
            return response()->json([
                'message' => 'Use the standard order status endpoint for retail orders.',
            ], 422);
        }

        try {
            $this->service->transition($order, $request->status, $request->user());
        } catch (UnauthorisedOrderTransitionException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (InvalidOrderTransitionException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Order status updated successfully.',
            'order'   => $order->fresh()->load('vendor', 'user'),
        ]);
    }
}
