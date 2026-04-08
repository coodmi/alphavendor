<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class OrderData
{
    public function __construct(
        public string $customerName,
        public string $customerEmail,
        public string $customerPhone,
        public ?string $shippingAddress,
        public ?string $notes,
        public array $items,
        public float $subtotal,
        public float $tax,
        public float $total,
        public string $status = 'pending',
    ) {}

    /**
     * Create OrderData from a request.
     */
    public static function fromRequest(Request $request): self
    {
        $items = session('cart', []);
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $taxRate = config('shop.tax_rate', 0);
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        return new self(
            customerName: $request->input('name'),
            customerEmail: $request->input('email'),
            customerPhone: $request->input('phone'),
            shippingAddress: $request->input('address'),
            notes: $request->input('notes'),
            items: $items,
            subtotal: $subtotal,
            tax: $tax,
            total: $total,
            status: 'pending',
        );
    }

    /**
     * Convert to array for database storage.
     */
    public function toArray(): array
    {
        return [
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'shipping_address' => $this->shippingAddress,
            'notes' => $this->notes,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'status' => $this->status,
        ];
    }
}
