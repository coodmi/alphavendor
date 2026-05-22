<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function index(Request $request)
    {
        // Get all exporters
        $exporterIds = User::where('role', 'exporter')->pluck('id');

        // Base query for products from exporters
        $query = Product::with(['category', 'brand', 'vendor', 'supplierLocation'])
            ->whereIn('vendor_id', $exporterIds)
            ->where('status', 'active');

        // Apply category filter - include products from child categories
        if ($request->filled('categories')) {
            $categoryIds = explode(',', $request->categories);
            // Get all child category IDs for selected parent categories
            $childCategoryIds = Category::whereIn('parent_category_id', $categoryIds)->pluck('id')->toArray();
            // Merge parent and child category IDs
            $allCategoryIds = array_merge($categoryIds, $childCategoryIds);
            $query->whereIn('category_id', $allCategoryIds);
        }

        // Apply MOQ filter
        if ($request->filled('moq')) {
            $moq = $request->moq;
            switch ($moq) {
                case '1-100':
                    $query->whereBetween('minimum_order', [1, 100]);
                    break;
                case '100-500':
                    $query->whereBetween('minimum_order', [100, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('minimum_order', [500, 1000]);
                    break;
                case '1000-5000':
                    $query->whereBetween('minimum_order', [1000, 5000]);
                    break;
                case '5000+':
                    $query->where('minimum_order', '>=', 5000);
                    break;
            }
        }

        // Apply supplier location filter
        if ($request->filled('location')) {
            $query->where('supplier_location', 'like', '%' . $request->location . '%');
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply certification filter
        if ($request->filled('certifications')) {
            $certifications = is_array($request->certifications) ? $request->certifications : explode(',', $request->certifications);
            $query->where(function ($q) use ($certifications) {
                foreach ($certifications as $cert) {
                    $q->orWhereJsonContains('certifications', $cert);
                }
            });
        }

        // Apply importer rating filter
        if ($request->filled('min_rating')) {
            $query->where('importer_rating', '>=', $request->min_rating);
        }

        // Apply sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'moq_low':
                $query->orderBy('minimum_order', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
        }

        // Paginate products
        $products = $query->paginate(16);

        // Get only parent categories (admin categories) with product counts including child products from exporters
        $categories = Category::where('is_active', true)
            ->whereNull('vendor_id') // Only admin categories
            ->with(['children' => function($q) use ($exporterIds) {
                $q->where('is_active', true)
                  ->whereIn('vendor_id', $exporterIds);
            }])
            ->get()
            ->map(function($category) use ($exporterIds) {
                // Count products from parent and all children (exporter products only)
                $productCount = $category->products()
                    ->whereIn('vendor_id', $exporterIds)
                    ->where('status', 'active')
                    ->count();
                foreach($category->children as $child) {
                    $productCount += $child->products()
                        ->whereIn('vendor_id', $exporterIds)
                        ->where('status', 'active')
                        ->count();
                }
                $category->products_count = $productCount;
                return $category;
            })
            ->filter(function($category) {
                return $category->products_count > 0;
            })
            ->values();

        // Get unique supplier locations
        $locations = Product::whereIn('vendor_id', $exporterIds)
            ->where('status', 'active')
            ->whereNotNull('supplier_location')
            ->distinct()
            ->pluck('supplier_location');

        // Get MOQ ranges with counts
        $moqRanges = [
            ['range' => '1-100', 'label' => '1 - 100 units', 'count' => Product::whereIn('vendor_id', $exporterIds)->where('status', 'active')->whereBetween('minimum_order', [1, 100])->count()],
            ['range' => '100-500', 'label' => '100 - 500 units', 'count' => Product::whereIn('vendor_id', $exporterIds)->where('status', 'active')->whereBetween('minimum_order', [100, 500])->count()],
            ['range' => '500-1000', 'label' => '500 - 1000 units', 'count' => Product::whereIn('vendor_id', $exporterIds)->where('status', 'active')->whereBetween('minimum_order', [500, 1000])->count()],
            ['range' => '1000-5000', 'label' => '1000 - 5000 units', 'count' => Product::whereIn('vendor_id', $exporterIds)->where('status', 'active')->whereBetween('minimum_order', [1000, 5000])->count()],
            ['range' => '5000+', 'label' => '5000+ units', 'count' => Product::whereIn('vendor_id', $exporterIds)->where('status', 'active')->where('minimum_order', '>=', 5000)->count()],
        ];

        // Get active certifications from exporters
        $certifications = \App\Models\Certification::whereIn('vendor_id', $exporterIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
                'categories' => $categories,
                'locations' => $locations,
                'moqRanges' => $moqRanges,
                'certifications' => $certifications,
            ]);
        }

        return view('export', compact('products', 'categories', 'locations', 'moqRanges', 'certifications'));
    }
}
