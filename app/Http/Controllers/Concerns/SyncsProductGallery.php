<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Product;
use App\Support\SeoKeywords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait SyncsProductGallery
{
    protected function validateGalleryImages(Request $request, bool $isUpdate, Product $product = null): void
    {
        $newCount = $request->hasFile('gallery_images') ? count($request->file('gallery_images')) : 0;

        if (! $isUpdate) {
            if ($newCount < 5) {
                throw ValidationException::withMessages([
                    'gallery_images' => 'Please upload at least 5 product images.',
                ]);
            }

            return;
        }

        if (! $product) {
            return;
        }

        $existingCount = $product->images()->count() + ($product->image ? 1 : 0);

        if ($existingCount + $newCount < 5) {
            throw ValidationException::withMessages([
                'gallery_images' => 'Product must have at least 5 images in total.',
            ]);
        }
    }

    protected function syncGalleryImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $sortStart = (int) $product->images()->max('sort_order') + 1;

        foreach ($request->file('gallery_images') as $index => $file) {
            $path = $file->store('products/gallery', 'public');
            $product->images()->create([
                'image' => $path,
                'sort_order' => $sortStart + $index,
            ]);
        }

        if (! $product->image) {
            $first = $product->images()->orderBy('sort_order')->first();
            if ($first) {
                $product->update(['image' => $first->image]);
            }
        }
    }

    protected function validateProductMetaKeywords(Request $request): void
    {
        if ($message = SeoKeywords::validate($request->input('meta_keywords'), 5, false)) {
            throw ValidationException::withMessages(['meta_keywords' => $message]);
        }
    }

    protected function validateCategoryMetaKeywords(Request $request): void
    {
        if ($message = SeoKeywords::validate($request->input('meta_keywords'), 5, true)) {
            throw ValidationException::withMessages(['meta_keywords' => $message]);
        }
    }
}
