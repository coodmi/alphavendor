<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.vendor')
            ->latest()
            ->get();

        $wishlistCount = $wishlists->count();

        return view('wishlist.index', compact('wishlists', 'wishlistCount'));
    }

    /**
     * Add product to wishlist
     */
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ], 400);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist',
            'wishlistCount' => $wishlistCount
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public function remove($productId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in wishlist'
            ], 404);
        }

        $wishlist->delete();

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist',
            'wishlistCount' => $wishlistCount
        ]);
    }

    /**
     * Toggle product in wishlist
     */
    public function toggle($productId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Product removed from wishlist';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
            $message = 'Product added to wishlist';
            $inWishlist = true;
        }

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'inWishlist' => $inWishlist,
            'wishlistCount' => $wishlistCount
        ]);
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Check if product is in wishlist
     */
    public function check($productId)
    {
        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'inWishlist' => $inWishlist
        ]);
    }
}
