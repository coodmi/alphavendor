<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

/**
 * Update Product Request.
 *
 * Validates data for updating an existing product.
 */
class UpdateProductRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Add authorization logic here
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'description' => ['sometimes', 'string'],
            'format' => ['nullable', 'string', 'max:50'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'min_quantity' => ['nullable', 'integer', 'min:1'],
            'min_pages' => ['nullable', 'integer', 'min:1'],
            'max_pages' => ['nullable', 'integer', 'min:1'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'popularity' => ['nullable', 'integer', 'min:0'],
            'stock' => ['nullable', 'boolean'],
            'badge' => ['nullable', 'string', 'max:50'],
            'config_options' => ['nullable', 'array'],
            'image' => ['nullable', 'image', 'max:2048'],
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
            'category_id.exists' => 'The selected category does not exist.',
            'title.max' => 'Product title cannot exceed 255 characters.',
            'slug.unique' => 'This slug is already in use.',
            'price.min' => 'Price must be greater than or equal to 0.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'Image size cannot exceed 2MB.',
        ];
    }
}
