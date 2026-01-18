<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'order']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('product', function($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($reviews);
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product', 'order']);

        if (request()->wantsJson()) {
            return response()->json($review);
        }

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review.
     */
    public function approve(Review $review): JsonResponse
    {
        $review->update(['status' => 'approved']);
        $review->product->updateRatingStats();

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully'
        ]);
    }

    /**
     * Reject a review.
     */
    public function reject(Request $request, Review $review): JsonResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $review->update(['status' => 'rejected']);
        $review->product->updateRatingStats();

        return response()->json([
            'success' => true,
            'message' => 'Review rejected successfully'
        ]);
    }

    /**
     * Mark review as reported.
     */
    public function report(Review $review): JsonResponse
    {
        $review->increment('reported_count');
        $review->update(['status' => 'reported']);

        return response()->json([
            'success' => true,
            'message' => 'Review reported successfully'
        ]);
    }

    /**
     * Add admin response to review.
     */
    public function respond(Request $request, Review $review): JsonResponse
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_response' => $request->admin_response,
            'admin_responded_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Response added successfully'
        ]);
    }

    /**
     * Get review statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'approved_reviews' => Review::where('status', 'approved')->count(),
            'reported_reviews' => Review::where('status', 'reported')->count(),
            'average_rating' => Review::where('status', 'approved')->avg('rating') ?? 0,
            'rating_distribution' => [
                5 => Review::where('status', 'approved')->where('rating', 5)->count(),
                4 => Review::where('status', 'approved')->where('rating', 4)->count(),
                3 => Review::where('status', 'approved')->where('rating', 3)->count(),
                2 => Review::where('status', 'approved')->where('rating', 2)->count(),
                1 => Review::where('status', 'approved')->where('rating', 1)->count(),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();
        $review->product->updateRatingStats();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }

    /**
     * Create a direct order for a product.
     */
    public function createDirectOrder(Product $product): JsonResponse
    {
        // Create a direct order for the admin user
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'DIR-' . time() . '-' . rand(100, 999),
            'status' => 'pending',
            'total_amount' => $product->price,
            'shipping_address' => 'Direct Order - Admin Created',
            'shipping_city' => 'N/A',
            'shipping_state' => 'N/A',
            'shipping_zip' => 'N/A',
            'shipping_country' => 'N/A',
            'phone' => 'N/A',
            'payment_method' => 'admin_direct',
            'payment_status' => 'paid',
            'notes' => 'Direct order created from admin panel for product: ' . $product->name,
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
            'total' => $product->price,
        ]);

        // Update product stock if needed
        if ($product->quantity !== null && $product->quantity > 0) {
            $product->decrement('quantity');
        }

        return response()->json([
            'success' => true,
            'message' => 'Direct order created successfully',
            'order_id' => $order->id,
            'order_number' => $order->order_number
        ]);
    }
}
