<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display reviews for vendor's products
     */
    public function index(Request $request)
    {
        $vendorId = Auth::id();
        
        // Get filter parameters
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        
        // Base query - reviews for vendor's products
        $query = Review::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->with(['user', 'product', 'order']);
        
        // Apply filters
        switch ($filter) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'approved':
                $query->where('status', 'approved');
                break;
            case 'replied':
                $query->whereNotNull('vendor_reply');
                break;
            case 'unreplied':
                $query->whereNull('vendor_reply')->where('status', 'approved');
                break;
        }
        
        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $reviews = $query->latest()->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => Review::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->count(),
            'pending' => Review::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->where('status', 'pending')->count(),
            'approved' => Review::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->where('status', 'approved')->count(),
            'replied' => Review::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->whereNotNull('vendor_reply')->count(),
            'unreplied' => Review::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->whereNull('vendor_reply')->where('status', 'approved')->count(),
            'average_rating' => Review::whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->where('status', 'approved')->avg('rating') ?? 0,
        ];
        
        return view('vendor.reviews.index', compact('reviews', 'stats', 'filter', 'search'));
    }
    
    /**
     * Show single review
     */
    public function show($id)
    {
        $vendorId = Auth::id();
        
        $review = Review::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->with(['user', 'product', 'order'])->findOrFail($id);
        
        return view('vendor.reviews.show', compact('review'));
    }
    
    /**
     * Reply to a review
     */
    public function reply(Request $request, $id)
    {
        $vendorId = Auth::id();
        
        $review = Review::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->findOrFail($id);
        
        $request->validate([
            'vendor_reply' => 'required|string|max:1000',
        ]);
        
        $review->update([
            'vendor_reply' => $request->vendor_reply,
            'vendor_replied_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Reply posted successfully!');
    }
    
    /**
     * Update vendor reply
     */
    public function updateReply(Request $request, $id)
    {
        $vendorId = Auth::id();
        
        $review = Review::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->findOrFail($id);
        
        $request->validate([
            'vendor_reply' => 'required|string|max:1000',
        ]);
        
        $review->update([
            'vendor_reply' => $request->vendor_reply,
            'vendor_replied_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Reply updated successfully!');
    }
    
    /**
     * Delete vendor reply
     */
    public function deleteReply($id)
    {
        $vendorId = Auth::id();
        
        $review = Review::whereHas('product', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->findOrFail($id);
        
        $review->update([
            'vendor_reply' => null,
            'vendor_replied_at' => null,
        ]);
        
        return redirect()->back()->with('success', 'Reply deleted successfully!');
    }
}
