<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a new review for a product.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', $user->id)
                                ->where('product_id', $product->id)
                                ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product.'
            ], 422);
        }

        // Check if user has purchased this product (optional verification)
        $hasPurchased = false;
        if ($request->order_id) {
            $order = Order::where('id', $request->order_id)
                         ->where('user_id', $user->id)
                         ->where('status', 'completed')
                         ->first();

            if ($order && $order->items()->where('product_id', $product->id)->exists()) {
                $hasPurchased = true;
            }
        }

        // For now, allow reviews without purchase verification
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => 'approved', // Auto-approve for testing
            'is_verified_purchase' => $hasPurchased,
        ]);

        // Update product rating stats
        $product->updateRatingStats();

        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted and is pending approval.',
            'review' => $review->load(['user', 'product'])
        ]);
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        // Ensure user owns the review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow updates for pending reviews
        if ($review->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'You can only update pending reviews.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
        ]);

        // Update product rating stats
        $review->product->updateRatingStats();

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully.',
            'review' => $review->fresh(['user', 'product'])
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review): JsonResponse
    {
        // Ensure user owns the review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Only allow deletion for pending reviews
        if ($review->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete pending reviews.'
            ], 422);
        }

        $product = $review->product;
        $review->delete();

        // Update product rating stats
        $product->updateRatingStats();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.'
        ]);
    }

    /**
     * Get reviews for a specific product.
     */
    public function productReviews(Product $product, Request $request)
    {
        $query = $product->approvedReviews()->with(['user', 'order']);

        // Apply sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'highest_rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest_rating':
                $query->orderBy('rating', 'asc');
                break;
            case 'most_helpful':
                $query->orderBy('helpful_votes', 'desc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $reviews = $query->paginate(10);

        // Check if current user has already reviewed this product
        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                               ->where('product_id', $product->id)
                               ->first();
        }

        if ($request->wantsJson() || $request->is('products/*/reviews')) {
            return response()->json([
                'reviews' => $reviews,
                'user_review' => $userReview,
                'can_review' => Auth::check() && !$userReview
            ]);
        }

        return view('reviews.product', compact('product', 'reviews', 'userReview'));
    }

    /**
     * Get user's reviews.
     */
    public function userReviews(Request $request)
    {
        $reviews = Auth::user()->reviews()
                              ->with(['product', 'order'])
                              ->latest()
                              ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($reviews);
        }

        return view('reviews.user', compact('reviews'));
    }

    /**
     * Mark review as helpful.
     */
    public function markHelpful(Review $review): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to mark reviews as helpful.'
            ], 401);
        }

        $review->increment('helpful_votes');

        return response()->json([
            'success' => true,
            'helpful_votes' => $review->helpful_votes
        ]);
    }

    /**
     * Check if user can review a product.
     */
    public function canReview(Product $product): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'can_review' => false,
                'message' => 'You must be logged in to review products.'
            ]);
        }

        $existingReview = Review::where('user_id', Auth::id())
                               ->where('product_id', $product->id)
                               ->first();

        if ($existingReview) {
            return response()->json([
                'can_review' => false,
                'message' => 'You have already reviewed this product.',
                'existing_review' => $existingReview
            ]);
        }

        // Check if user has purchased the product
        $hasPurchased = Order::where('user_id', Auth::id())
                            ->where('status', 'completed')
                            ->whereHas('items', function($query) use ($product) {
                                $query->where('product_id', $product->id);
                            })
                            ->exists();

        return response()->json([
            'can_review' => true,
            'has_purchased' => $hasPurchased
        ]);
    }
}
