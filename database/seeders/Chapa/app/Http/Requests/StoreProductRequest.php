<?php

namespace App\Http\Requests;

use App\Rules\NoSqlInjection;
use App\Rules\NoXss;

/**
 * Store Product Request.
 *
 * Validates data for creating a new product.
 */
class StoreProductRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Add authorization logic here
        // For now, allow all authenticated users
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255', new NoXss, new NoSqlInjection],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug', new NoSqlInjection],
            'description' => ['required', 'string', new NoXss],
            'format' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'min_quantity' => ['nullable', 'integer', 'min:1'],
            'min_pages' => ['nullable', 'integer', 'min:1'],
            'max_pages' => ['nullable', 'integer', 'min:1'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'popularity' => ['nullable', 'integer', 'min:0'],
            'stock' => ['nullable', 'boolean'],
            'badge' => ['nullable', 'string', 'max:50', new NoXss],
            'config_options' => ['nullable', 'array'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB max
            'is_active' => ['nullable', 'boolean'],
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
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'title.required' => 'Product title is required.',
            'title.max' => 'Product title cannot exceed 255 characters.',
            'slug.required' => 'Product slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'description.required' => 'Product description is required.',
            'price.required' => 'Product price is required.',
            'price.min' => 'Price must be greater than or equal to 0.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'Image size cannot exceed 2MB.',
        ];
    }
}
