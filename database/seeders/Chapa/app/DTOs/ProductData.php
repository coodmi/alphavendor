<?php

namespace App\DTOs;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;

readonly class ProductData
{
    public function __construct(
        public string $name,
        public ?string $slug,
        public ?string $description,
        public float $price,
        public ?float $salePrice,
        public ?int $categoryId,
        public ?int $formatId,
        public bool $isActive,
        public bool $stock,
        public ?string $image,
    ) {}

    /**
     * Create ProductData from a store request.
     */
    public static function fromStoreRequest(StoreProductRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            slug: $request->validated('slug'),
            description: $request->validated('description'),
            price: $request->validated('price'),
            salePrice: $request->validated('sale_price'),
            categoryId: $request->validated('category_id'),
            formatId: $request->validated('format_id'),
            isActive: $request->validated('is_active', true),
            stock: $request->validated('stock', true),
            image: null, // Handle separately
        );
    }

    /**
     * Create ProductData from an update request.
     */
    public static function fromUpdateRequest(UpdateProductRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            slug: $request->validated('slug'),
            description: $request->validated('description'),
            price: $request->validated('price'),
            salePrice: $request->validated('sale_price'),
            categoryId: $request->validated('category_id'),
            formatId: $request->validated('format_id'),
            isActive: $request->validated('is_active', true),
            stock: $request->validated('stock', true),
            image: null, // Handle separately
        );
    }

    /**
     * Convert to array for database storage.
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->salePrice,
            'category_id' => $this->categoryId,
            'format_id' => $this->formatId,
            'is_active' => $this->isActive,
            'stock' => $this->stock,
            'image' => $this->image,
        ], fn ($value) => $value !== null);
    }
}
