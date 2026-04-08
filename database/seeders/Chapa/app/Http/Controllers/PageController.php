<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use App\Services\CategoryDataService;

class PageController extends Controller
{
    protected CategoryDataService $categoryService;

    public function __construct(CategoryDataService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display the home page.
     *
     * Note: homeContent is provided by the view composer in AppServiceProvider
     * which properly handles defaults and is_active filtering.
     */
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about', ['content' => PageSection::getPageContent('about')]);
    }

    public function contact()
    {
        $contactPageSetting = \App\Models\ContactPageSetting::getActive();
        $contactContent = $contactPageSetting ? $contactPageSetting->getFormattedData() : [];
        
        return view('pages.contact', ['contactContent' => $contactContent]);
    }

    public function faq()
    {
        return view('pages.faq', ['content' => PageSection::getPageContent('faq')]);
    }

    public function terms()
    {
        return view('pages.terms', ['content' => PageSection::getPageContent('terms')]);
    }

    public function privacy()
    {
        $privacyPageSetting = \App\Models\PrivacyPageSetting::first();
        $privacyContent = $privacyPageSetting ? $privacyPageSetting->toArray() : [];
        
        return view('pages.privacy', ['privacyContent' => $privacyContent]);
    }

    /**
     * Display a category page.
     */
    public function category(string $category)
    {
        $data = $this->categoryService->getCategoryBySlug($category);

        if (! $data) {
            abort(404, 'Category not found');
        }

        return view('pages.category', $data);
    }

    /**
     * Display a product configuration page.
     */
    public function productConfig(string $category, string $product)
    {
        $productData = $this->categoryService->getProductType($category, $product);

        if (! $productData) {
            abort(404, 'Product not found');
        }

        $view = 'products.configure.'.($productData['view'] ?? 'book');

        return view($view, [
            'productType' => $productData['productType'],
            'productTitle' => $productData['productTitle'],
        ]);
    }
}
