<?php

namespace App\Http\Controllers;

use App\Models\PageSection;

class CategoryPageController extends Controller
{
    /**
     * Display a category page.
     */
    public function show($slug)
    {
        // Get content from database or use defaults
        $section = PageSection::where('page', 'category')
            ->where('section_key', $slug)
            ->first();

        // Get defaults first
        $defaults = PageSection::getDefaults($slug);

        // Merge database content with defaults (database content takes precedence, but use defaults for missing fields)
        $content = $section ? array_replace_recursive($defaults, $section->content) : $defaults;

        // If products array is empty in database, use defaults
        if ($section && empty($section->content['products'] ?? null)) {
            $content['products'] = $defaults['products'] ?? [];
        }

        // Fetch products from database
        $categoryModel = \App\Models\Category::where('slug', $slug)->with([
            'products' => function ($q) {
                $q->where('is_active', true);
            },
        ])->first();

        $dbProducts = [];
        if ($categoryModel) {
            foreach ($categoryModel->products as $product) {
                $dbProducts[] = [
                    'title' => $product->title,
                    'img' => $product->image,
                    'price' => $product->price, // Assuming simple price for now
                    'url' => url("/{$slug}/{$product->slug}"),
                    'badge' => $product->badge,
                ];
            }
        }

        // Prepare data for the view
        $data = [
            'categoryTitle' => $content['title'] ?? ucfirst($slug),
            'categoryDescription' => $content['description'] ?? '',
            'headline' => $content['headline'] ?? '',
            'shortDescription' => $content['short_description'] ?? '',
            'gridTitle' => $content['grid_title'] ?? 'Select a product',
            'gridSubtitle' => $content['grid_subtitle'] ?? '',
            'heroSlides' => $content['hero_slides'] ?? [],
            'products' => count($dbProducts) > 0 ? $dbProducts : ($content['products'] ?? []),
            'offerTitle' => $content['offer']['title'] ?? '',
            'offerText' => $content['offer']['text'] ?? '',
            'offerDetails' => $content['offer']['details'] ?? '',
        ];

        return view('pages.category', $data);
    }
}
