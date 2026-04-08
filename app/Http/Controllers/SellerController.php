<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of sellers (retailers, wholesalers, exporters)
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('status', 'active');

        // Filter by seller type
        if ($request->has('type') && $request->type !== '') {
            $query->where('role', $request->type);
        }

        // Search by name
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('roleApplications', function($subQuery) use ($request) {
                      $subQuery->where('business_name', 'like', '%' . $request->search . '%')
                               ->where('status', 'approved');
                  });
            });
        }

        // Filter by minimum rating (for exporters)
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->whereNotNull('exporter_rating')
                  ->where('exporter_rating', '>=', $minRating);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('roleApplications', function($q) use ($request) {
                $q->where('status', 'approved')
                  ->where('country', $request->location);
            });
        }

        // Filter by minimum products - use whereHas with count
        if ($request->filled('min_products')) {
            $minProducts = (int) $request->min_products;
            $query->has('products', '>=', $minProducts);
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'products_high':
                $query->withCount('products')->orderBy('products_count', 'desc');
                break;
            case 'products_low':
                $query->withCount('products')->orderBy('products_count', 'asc');
                break;
            case 'rating':
                $query->orderBy('exporter_rating', 'desc');
                break;
            default:
                $query->latest();
        }

        // Add withCount if not already added by sorting
        if (!in_array($sortBy, ['products_high', 'products_low'])) {
            $query->withCount('products');
        }

        // Paginate results with products count and orders
        $sellers = $query->with(['roleApplications' => function($q) {
            $q->where('status', 'approved')->latest();
        }, 'vendorBadge'])
        ->withCount(['orders as total_sales'])
        ->paginate(12);

        // Get unique locations from approved role applications
        $locations = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('status', 'active')
            ->whereHas('roleApplications', function($q) {
                $q->where('status', 'approved')->whereNotNull('country');
            })
            ->with(['roleApplications' => function($q) {
                $q->where('status', 'approved')->select('user_id', 'country');
            }])
            ->get()
            ->pluck('roleApplications')
            ->flatten()
            ->pluck('country')
            ->unique()
            ->filter()
            ->sort()
            ->values();

        // Handle AJAX requests
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'html' => $this->getSellersHtml($sellers),
                'total' => $sellers->total(),
                'current_page' => $sellers->currentPage(),
                'last_page' => $sellers->lastPage()
            ]);
        }

        return view('sellers.index', compact('sellers', 'locations'));
    }

    /**
     * Get sellers data for AJAX requests
     */
    private function getSellersHtml($sellers)
    {
        if ($sellers->count() === 0) {
            return '<div style="background: white; padding: 60px 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <i class="fas fa-store-slash" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="font-size: 24px; color: #333; margin-bottom: 10px;">No Sellers Found</h3>
                <p style="color: #666; margin-bottom: 20px;">Try adjusting your filters or search criteria</p>
                <a href="' . route('sellers.index') . '"
                   style="display: inline-block; padding: 12px 24px; background: #FFA500; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    Clear All Filters
                </a>
            </div>';
        }

        $html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-bottom: 30px;">';
        
        foreach ($sellers as $seller) {
            $businessInfo = $seller->roleApplications->first();
            $roleIcon = $seller->role === 'retailer' ? 'fa-store' :
                       ($seller->role === 'wholesaler' ? 'fa-warehouse' : 'fa-globe');
            $roleColor = $seller->role === 'retailer' ? '#4CAF50' :
                        ($seller->role === 'wholesaler' ? '#2196F3' : '#9C27B0');
            $vendorName = $businessInfo->business_name ?? $seller->name;
            $firstLetter = strtoupper(substr($vendorName, 0, 1));
            $profileImage = $seller->profile_image ? asset('storage/' . $seller->profile_image) : null;
            
            $html .= '<div class="seller-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s;">
                <div style="position: relative; background: linear-gradient(135deg, ' . $roleColor . '15 0%, ' . $roleColor . '05 100%); padding: 30px 20px; text-align: center;">
                    <div style="width: 120px; height: 120px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); position: relative; background: ' . $roleColor . ';">';
            
            if ($profileImage) {
                $html .= '<img src="' . $profileImage . '" alt="' . $vendorName . '" style="width: 100%; height: 100%; object-fit: cover;">';
            } else {
                $html .= '<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, ' . $roleColor . ' 0%, ' . $roleColor . 'dd 100%); color: white; font-size: 48px; font-weight: 700;">' . $firstLetter . '</div>';
            }
            
            $html .= '</div>
                    <span style="background: ' . $roleColor . '; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block;">
                        <i class="fas ' . $roleIcon . '" style="margin-right: 5px;"></i>
                        ' . ucfirst($seller->role) . '
                    </span>';
            
            if ($seller->role === 'exporter' && $seller->exporter_rating) {
                $html .= '<div style="margin-top: 10px;">
                        <span style="background: #FFD700; color: #333; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-star"></i>
                            ' . number_format($seller->exporter_rating, 1) . '
                        </span>
                    </div>';
            }
            
            $html .= '</div>
                <div style="padding: 20px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: #333; margin-bottom: 8px; text-align: center;">' . $vendorName . '</h3>
                    <div style="display: flex; justify-content: space-around; padding: 12px 0; margin-bottom: 15px; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0;">
                        <div style="text-align: center;">
                            <div style="font-size: 20px; font-weight: 700; color: ' . $roleColor . ';">' . ($seller->products_count ?? 0) . '</div>
                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-top: 2px;">Products</div>
                        </div>
                        <div style="width: 1px; background: #e0e0e0;"></div>
                        <div style="text-align: center;">
                            <div style="font-size: 20px; font-weight: 700; color: ' . $roleColor . ';">' . ($seller->total_sales ?? 0) . '</div>
                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-top: 2px;">Sales</div>
                        </div>
                        <div style="width: 1px; background: #e0e0e0;"></div>
                        <div style="text-align: center;">
                            <div style="font-size: 20px; font-weight: 700; color: #FFB800;">' . ($seller->role === 'exporter' && $seller->exporter_rating ? number_format($seller->exporter_rating, 1) : '-') . '</div>
                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-top: 2px;">
                                <i class="fas fa-star" style="color: #FFB800; font-size: 10px;"></i> Rating
                            </div>
                        </div>
                    </div>
                    <a href="' . route('sellers.products', $seller->id) . '"
                       style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(255,165,0,0.3);"
                       onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(255,165,0,0.4)\'"
                       onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 8px rgba(255,165,0,0.3)\'">
                        <i class="fas fa-shopping-bag"></i>
                        <span>View Products</span>
                    </a>
                </div>
            </div>';
        }
        
        $html .= '</div>';
        
        // Add pagination if needed
        if ($sellers->hasPages()) {
            $html .= '<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">' . $sellers->appends(request()->query())->links()->toHtml() . '</div>';
        }
        
        return $html;
    }

    /**
     * Display seller's products
     */
    public function products(Request $request, $sellerId)
    {
        $seller = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])
            ->where('status', 'active')
            ->with('vendorBadge')
            ->findOrFail($sellerId);

        $query = \App\Models\Product::where('vendor_id', $sellerId);

        // Search filter
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price !== '') {
            if ($seller->role === 'retailer') {
                $query->where('retail_price', '>=', $request->min_price);
            } elseif ($seller->role === 'wholesaler') {
                $query->where('wholesale_price', '>=', $request->min_price);
            } elseif ($seller->role === 'exporter') {
                $query->where('export_price', '>=', $request->min_price);
            }
        }

        if ($request->has('max_price') && $request->max_price !== '') {
            if ($seller->role === 'retailer') {
                $query->where('retail_price', '<=', $request->max_price);
            } elseif ($seller->role === 'wholesaler') {
                $query->where('wholesale_price', '<=', $request->max_price);
            } elseif ($seller->role === 'exporter') {
                $query->where('export_price', '<=', $request->max_price);
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $priceColumn = $seller->role === 'retailer' ? 'retail_price' :
                              ($seller->role === 'wholesaler' ? 'wholesale_price' : 'export_price');
                $query->orderBy($priceColumn, 'asc');
                break;
            case 'price_high':
                $priceColumn = $seller->role === 'retailer' ? 'retail_price' :
                              ($seller->role === 'wholesaler' ? 'wholesale_price' : 'export_price');
                $query->orderBy($priceColumn, 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = \App\Models\Category::all();

        // Get seller's business information
        $businessInfo = $seller->roleApplications()
            ->where('status', 'approved')
            ->latest()
            ->first();

        return view('sellers.products', compact('seller', 'products', 'categories', 'businessInfo'));
    }
}
