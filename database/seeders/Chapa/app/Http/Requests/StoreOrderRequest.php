<?php

namespace App\Http\Requests;

/**
 * Store Order Request.
 *
 * Validates data for creating a new order.
 */
class StoreOrderRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow both authenticated and guest users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:50'],
            'shipping_country' => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['nullable', 'string', 'max:100'],
            'shipping_zip' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string', 'in:credit_card,paypal,bank_transfer,cash_on_delivery'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.format' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'shipping_name.required' => 'Shipping name is required.',
            'shipping_email.required' => 'Shipping email is required.',
            'shipping_email.email' => 'Please provide a valid email address.',
            'shipping_phone.required' => 'Phone number is required.',
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_city.required' => 'City is required.',
            'shipping_zip.required' => 'ZIP/Postal code is required.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method selected.',
            'items.required' => 'Order must contain at least one item.',
            'items.min' => 'Order must contain at least one item.',
            'items.*.product_id.required' => 'Product ID is required for each item.',
            'items.*.product_id.exists' => 'One or more products do not exist.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
