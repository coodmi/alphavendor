<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CategoryDataService
{
    protected string $dataPath;
    protected int $cacheMinutes = 60;

    public function __construct()
    {
        $this->dataPath = database_path('data/categories.json');
    }

    /**
     * Get all categories data from JSON file with caching.
     */
    public function getAllCategories(): array
    {
        return Cache::remember('category_data', $this->cacheMinutes * 60, function () {
            if (! File::exists($this->dataPath)) {
                return [];
            }

            $content = File::get($this->dataPath);

            return json_decode($content, true) ?? [];
        });
    }

    /**
     * Get category data by slug.
     */
    public function getCategoryBySlug(string $slug): ?array
    {
        $categories = $this->getAllCategories();

        return $categories[$slug] ?? null;
    }

    /**
     * Get product type data within a category.
     */
    public function getProductType(string $categorySlug, string $productSlug): ?array
    {
        $category = $this->getCategoryBySlug($categorySlug);

        if (! $category || ! isset($category['productTypes'][$productSlug])) {
            return null;
        }

        return $category['productTypes'][$productSlug];
    }

    /**
     * Clear the category data cache.
     */
    public function clearCache(): void
    {
        Cache::forget('category_data');
    }
}
