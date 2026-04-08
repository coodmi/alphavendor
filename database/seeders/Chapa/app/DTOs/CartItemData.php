<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class CartItemData
{
    public function __construct(
        public int $productId,
        public string $productName,
        public float $price,
        public int $quantity,
        public ?string $image,
        public array $options = [],
    ) {}

    /**
     * Create CartItemData from request.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            productId: (int) $request->input('product_id'),
            productName: $request->input('product_name'),
            price: (float) $request->input('price'),
            quantity: (int) $request->input('quantity', 1),
            image: $request->input('image'),
            options: $request->input('options', []),
        );
    }

    /**
     * Create CartItemData from product model.
     */
    public static function fromProduct($product, int $quantity = 1, array $options = []): self
    {
        return new self(
            productId: $product->id,
            productName: $product->name,
            price: $product->sale_price ?? $product->price,
            quantity: $quantity,
            image: $product->image,
            options: $options,
        );
    }

    /**
     * Get the total price for this item.
     */
    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Convert to array for session storage.
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'options' => $this->options,
        ];
    }
}
