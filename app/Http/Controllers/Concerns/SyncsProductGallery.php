<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Product;
use App\Support\SeoKeywords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait SyncsProductGallery
{
    protected int $minGalleryImages = 1;

    protected int $maxGalleryImages = 4;

    protected function validateGalleryImages(Request $request, bool $isUpdate, Product $product = null): void
    {
        $min = $this->minGalleryImages;
        $max = $this->maxGalleryImages;
        $newCount = $request->hasFile('gallery_images') ? count($request->file('gallery_images')) : 0;

        if (! $isUpdate) {
            if ($newCount < $min) {
                throw ValidationException::withMessages([
                    'gallery_images' => "Please upload at least {$min} product image(s).",
                ]);
            }
            if ($newCount > $max) {
                throw ValidationException::withMessages([
                    'gallery_images' => "You can upload a maximum of {$max} product images.",
                ]);
            }

            return;
        }

        if (! $product) {
            return;
        }

        $this->ensureGalleryFromMainImage($product);

        $removeIds = array_map('intval', (array) $request->input('remove_gallery_ids', []));
        $remaining = $product->images()->whereNotIn('id', $removeIds)->count();
        $total = $remaining + $newCount;

        if ($total < $min) {
            $hasLegacyMainOnly = $remaining === 0 && $newCount === 0 && ! empty($product->image);

            if (! $hasLegacyMainOnly) {
                throw ValidationException::withMessages([
                    'gallery_images' => "Product must have at least {$min} gallery image(s).",
                ]);
            }
        }
        if ($total > $max) {
            throw ValidationException::withMessages([
                'gallery_images' => "Product can have at most {$max} gallery images.",
            ]);
        }
    }

    protected function removeMarkedGalleryImages(Product $product, Request $request): void
    {
        $ids = array_filter(array_map('intval', (array) $request->input('remove_gallery_ids', [])));

        if ($ids === []) {
            return;
        }

        foreach ($product->images()->whereIn('id', $ids)->get() as $img) {
            if (! filter_var($img->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($img->image);
            }
            $img->delete();
        }

        $product->refresh();

        if ($product->image && ! $product->images()->where('image', $product->image)->exists()) {
            $first = $product->images()->orderBy('sort_order')->first();
            $product->update(['image' => $first?->image]);
        }
    }

    protected function deleteProductGalleryFiles(Product $product): void
    {
        foreach ($product->images as $img) {
            if (! filter_var($img->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($img->image);
            }
        }
    }

    protected function syncGalleryImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $sortStart = (int) $product->images()->max('sort_order') + 1;
        $slotsLeft = max(0, $this->maxGalleryImages - $product->images()->count());

        foreach (array_slice($request->file('gallery_images'), 0, $slotsLeft) as $index => $file) {
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

    protected function ensureGalleryFromMainImage(Product $product): void
    {
        if ($product->images()->exists() || empty($product->image)) {
            return;
        }

        $product->images()->create([
            'image' => $product->image,
            'sort_order' => 0,
        ]);

        $product->refresh();
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
