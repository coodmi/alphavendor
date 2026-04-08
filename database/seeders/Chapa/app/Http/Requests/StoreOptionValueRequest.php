<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionValueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'label_en' => ['required', 'string', 'max:255'],
            'label_bn' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_modifier' => ['required', 'numeric'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'price_modifier.numeric' => 'Price modifier must be a valid number.',
            'display_order.min' => 'Display order must be a positive number.',
        ];
    }
}
