<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'format' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'min_quantity' => ['nullable', 'integer', 'min:1'],
            'min_pages' => ['nullable', 'integer', 'min:1'],
            'max_pages' => ['nullable', 'integer', 'min:1'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'popularity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'stock' => ['boolean'],
            'badge' => ['nullable', 'string', 'max:255'],
            'config_options' => ['nullable', 'array'],
            'config_options.bindings' => ['nullable', 'array'],
            'config_options.sizes' => ['nullable', 'array'],
            'config_options.orientations' => ['nullable', 'array'],
            'config_options.paperTypes' => ['nullable', 'array'],
            'config_options.coverPaperTypes' => ['nullable', 'array'],
            'config_options.coatings' => ['nullable', 'array'],
            'config_options.finishes' => ['nullable', 'array'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'is_active' => 'active status',
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
            'category_id.required' => 'Please select a category for the product.',
            'category_id.exists' => 'The selected category does not exist.',
            'image.required' => 'Product image is required.',
            'image.image' => 'The file must be a valid image.',
            'image.max' => 'Image size must not exceed 5MB.',
        ];
    }
}
