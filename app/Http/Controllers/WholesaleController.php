<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\SupplierLocation;

class WholesaleController extends Controller
{
    public function index(Request $request)
    {
        // Get all wholesalers
        $wholesalers = User::where('role', 'wholesaler')
            ->where('status', 'active')
            ->get();

        // Build query for wholesale products
        $query = Product::with(['category', 'brand', 'vendor'])
            ->whereHas('vendor', function($q) {
                $q->where('role', 'wholesaler')
                  ->where('status', 'active');
            })
            ->where('status', 'active');

        // Filter by minimum order if provided
        if ($request->has('minimum_order') && $request->minimum_order) {
            $minOrder = $request->minimum_order;
            if ($minOrder === '10-50') {
                $query->whereBetween('minimum_order', [10, 50]);
            } elseif ($minOrder === '50-100') {
                $query->whereBetween('minimum_order', [50, 100]);
            } elseif ($minOrder === '100-500') {
                $query->whereBetween('minimum_order', [100, 500]);
            } elseif ($minOrder === '500+') {
                $query->where('minimum_order', '>=', 500);
            }
        }

        // Filter by supplier location if provided
        if ($request->has('supplier_location') && $request->supplier_location) {
            $query->where('supplier_location_id', $request->supplier_location);
        }

        // Filter by category if provided - include products from child categories
        if ($request->has('category') && $request->category) {
            $categoryId = $request->category;
            // Get all child category IDs for selected parent category
            $childCategoryIds = Category::where('parent_category_id', $categoryId)->pluck('id')->toArray();
            // Include parent and child category IDs
            $allCategoryIds = array_merge([$categoryId], $childCategoryIds);
            $query->whereIn('category_id', $allCategoryIds);
        }

        // Filter by brand if provided - include products from child brands
        if ($request->has('brand') && $request->brand) {
            $brandId = $request->brand;
            // Get all child brand IDs for selected parent brand
            $childBrandIds = Brand::where('parent_brand_id', $brandId)->pluck('id')->toArray();
            // Include parent and child brand IDs
            $allBrandIds = array_merge([$brandId], $childBrandIds);
            $query->whereIn('brand_id', $allBrandIds);
        }

        // Filter by price range if provided
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Get products
        $products = $query->latest()->paginate(16);

        // Get active supplier locations from database
        $supplierLocations = SupplierLocation::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get minimum order ranges
        $minOrderRanges = [
            '10-50' => '10-50 Units',
            '50-100' => '50-100 Units',
            '100-500' => '100-500 Units',
            '500+' => '500+ Units',
        ];

        // Get only parent categories (admin categories) with product counts including child products from wholesalers
        $categories = Category::where('is_active', true)
            ->whereNull('vendor_id') // Only admin categories
            ->with(['children' => function($q) {
                $q->whereHas('vendor', function($query) {
                    $query->where('role', 'wholesaler');
                });
            }])
            ->get()
            ->map(function($category) {
                // Count products from parent and all children (wholesaler products only)
                $productCount = $category->products()
                    ->where('status', 'active')
                    ->whereHas('vendor', function($query) {
                        $query->where('role', 'wholesaler');
                    })
                    ->count();
                foreach($category->children as $child) {
                    $productCount += $child->products()
                        ->where('status', 'active')
                        ->whereHas('vendor', function($query) {
                            $query->where('role', 'wholesaler');
                        })
                        ->count();
                }
                $category->products_count = $productCount;
                return $category;
            })
            ->filter(function($category) {
                return $category->products_count > 0;
            });

        // Get only parent brands (admin brands) - show all since parent-child system is new
        $brands = Brand::where('is_active', true)
            ->whereNull('vendor_id') // Only admin brands
            ->orderBy('name')
            ->get()
            ->map(function($brand) {
                // Count products from parent and all children (wholesaler products only)
                $productCount = $brand->products()
                    ->where('status', 'active')
                    ->whereHas('vendor', function($query) {
                        $query->where('role', 'wholesaler');
                    })
                    ->count();
                $children = Brand::where('parent_brand_id', $brand->id)
                    ->whereHas('vendor', function($query) {
                        $query->where('role', 'wholesaler');
                    })
                    ->get();
                foreach($children as $child) {
                    $productCount += $child->products()
                        ->where('status', 'active')
                        ->whereHas('vendor', function($query) {
                            $query->where('role', 'wholesaler');
                        })
                        ->count();
                }
                $brand->products_count = $productCount;
                return $brand;
            });

        return view('wholesale', compact(
            'products',
            'supplierLocations',
            'minOrderRanges',
            'categories',
            'brands'
        ));
    }
}
