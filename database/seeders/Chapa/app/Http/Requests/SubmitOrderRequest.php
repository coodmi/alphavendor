<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint for customers
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
            'design_files' => ['nullable', 'array'],
            'design_files.*' => ['file', 'max:10240'], // 10MB max per file
            // Dynamic option validation will be handled in the controller
            // using GlobalServiceOptionService->validateSelection()
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'Please specify the quantity.',
            'quantity.min' => 'Quantity must be at least 1.',
            'design_files.*.max' => 'Each file must not exceed 10MB.',
        ];
    }
}
