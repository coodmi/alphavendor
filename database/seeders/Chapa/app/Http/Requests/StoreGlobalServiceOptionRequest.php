<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGlobalServiceOptionRequest extends FormRequest
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
            'name_en' => ['required', 'string', 'max:255', 'unique:global_service_options,name_en'],
            'name_bn' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['radio', 'button_group', 'textarea', 'number', 'text', 'file', 'date', 'color'])],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'applies_to_all' => ['nullable', 'boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:service_categories,id'],
            'values' => ['nullable', 'array'],
            'values.*.label_en' => ['required_with:values', 'string', 'max:255'],
            'values.*.label_bn' => ['required_with:values', 'string', 'max:255'],
            'values.*.description' => ['nullable', 'string'],
            'values.*.price_modifier' => ['nullable', 'numeric'],
            'values.*.is_default' => ['nullable', 'boolean'],
            'values.*.is_active' => ['nullable', 'boolean'],
            'values.*.display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name_en.unique' => 'An option with this name already exists.',
            'display_order.min' => 'Display order must be a positive number.',
            'values.*.price_modifier.numeric' => 'Price modifier must be a valid number.',
        ];
    }
}
