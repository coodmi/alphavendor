<?php

namespace App\Services;

use App\Models\GlobalServiceOption;
use App\Models\OptionCategoryAssignment;
use App\Models\OptionValue;
use App\Models\OrderItemOption;
use App\Models\ServiceProduct;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GlobalServiceOptionService
{
    /**
     * Get all options applicable to a service product.
     */
    public function getOptionsForProduct(ServiceProduct $product): Collection
    {
        $categoryId = $product->service_category_id;

        return GlobalServiceOption::active()
            ->ordered()
            ->whereHas('categoryAssignments', function ($query) use ($categoryId) {
                $query->where('applies_to_all', true)
                    ->orWhere('category_id', $categoryId);
            })
            ->with(['values' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();
    }

    /**
     * Create a new global service option.
     */
    public function createOption(array $data): GlobalServiceOption
    {
        // Validate uniqueness of name
        if (GlobalServiceOption::where('name_en', $data['name_en'])->exists()) {
            throw ValidationException::withMessages([
                'name_en' => ['The name has already been taken.'],
            ]);
        }

        return DB::transaction(function () use ($data) {
            // Create the option
            $option = GlobalServiceOption::create([
                'name_en' => $data['name_en'],
                'name_bn' => $data['name_bn'],
                'type' => $data['type'],
                'display_order' => $data['display_order'] ?? 0,
                'is_required' => $data['is_required'] ?? false,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Create values if provided
            if (isset($data['values']) && is_array($data['values'])) {
                foreach ($data['values'] as $valueData) {
                    $this->createValue($option, $valueData);
                }
            }

            // Create category assignments if provided
            if (isset($data['categories'])) {
                $this->syncCategories($option, $data['categories'], $data['applies_to_all'] ?? false);
            }

            return $option->load('values', 'categoryAssignments');
        });
    }

    /**
     * Update an existing global service option.
     */
    public function updateOption(GlobalServiceOption $option, array $data): GlobalServiceOption
    {
        // Validate uniqueness of name if changed
        if (isset($data['name_en']) && $data['name_en'] !== $option->name_en) {
            if (GlobalServiceOption::where('name_en', $data['name_en'])->where('id', '!=', $option->id)->exists()) {
                throw ValidationException::withMessages([
                    'name_en' => ['The name has already been taken.'],
                ]);
            }
        }

        return DB::transaction(function () use ($option, $data) {
            // Update option attributes
            $option->update([
                'name_en' => $data['name_en'] ?? $option->name_en,
                'name_bn' => $data['name_bn'] ?? $option->name_bn,
                'type' => $data['type'] ?? $option->type,
                'display_order' => $data['display_order'] ?? $option->display_order,
                'is_required' => $data['is_required'] ?? $option->is_required,
                'is_active' => $data['is_active'] ?? $option->is_active,
            ]);

            // Sync values if provided
            if (isset($data['values'])) {
                $this->syncValues($option, $data['values']);
            }

            // Sync category assignments if provided
            if (isset($data['categories'])) {
                $this->syncCategories($option, $data['categories'], $data['applies_to_all'] ?? false);
            }

            return $option->fresh(['values', 'categoryAssignments']);
        });
    }

    /**
     * Delete a global service option (soft delete).
     */
    public function deleteOption(GlobalServiceOption $option): bool
    {
        // Check if option is used in any orders
        $isUsedInOrders = OrderItemOption::where('option_name', $option->name_en)->exists();

        if ($isUsedInOrders) {
            // Soft delete only
            return $option->delete();
        }

        // Can hard delete if not used
        return $option->forceDelete();
    }

    /**
     * Validate option selection for order submission.
     */
    public function validateSelection(ServiceProduct $product, array $selections): array
    {
        $errors = [];
        $applicableOptions = $this->getOptionsForProduct($product);

        // Check all required options are selected
        foreach ($applicableOptions as $option) {
            if ($option->is_required) {
                $optionKey = 'option_' . $option->id;
                
                if (!isset($selections[$optionKey]) || empty($selections[$optionKey])) {
                    $errors[$optionKey] = "The {$option->name} field is required.";
                }
            }
        }

        // Validate selected values exist and are active
        foreach ($selections as $key => $value) {
            if (strpos($key, 'option_') === 0 && !empty($value)) {
                $optionId = str_replace('option_', '', $key);
                $option = $applicableOptions->firstWhere('id', $optionId);

                if ($option && in_array($option->type, ['radio', 'button_group'])) {
                    $selectedValue = OptionValue::find($value);
                    
                    if (!$selectedValue || !$selectedValue->is_active || $selectedValue->option_id != $optionId) {
                        $errors[$key] = "The selected value is invalid or no longer available.";
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Create a value for an option.
     */
    protected function createValue(GlobalServiceOption $option, array $valueData): OptionValue
    {
        // If this value is marked as default, unmark any existing default
        if ($valueData['is_default'] ?? false) {
            $option->values()->update(['is_default' => false]);
        }

        return $option->values()->create([
            'label_en' => $valueData['label_en'],
            'label_bn' => $valueData['label_bn'],
            'description' => $valueData['description'] ?? null,
            'price_modifier' => $valueData['price_modifier'] ?? 0,
            'is_default' => $valueData['is_default'] ?? false,
            'is_active' => $valueData['is_active'] ?? true,
            'display_order' => $valueData['display_order'] ?? 0,
        ]);
    }

    /**
     * Sync values for an option.
     */
    protected function syncValues(GlobalServiceOption $option, array $values): void
    {
        $existingValueIds = [];

        foreach ($values as $valueData) {
            if (isset($valueData['id'])) {
                // Update existing value
                $value = $option->values()->find($valueData['id']);
                if ($value) {
                    // If this value is marked as default, unmark others
                    if ($valueData['is_default'] ?? false) {
                        $option->values()->where('id', '!=', $value->id)->update(['is_default' => false]);
                    }

                    $value->update([
                        'label_en' => $valueData['label_en'] ?? $value->label_en,
                        'label_bn' => $valueData['label_bn'] ?? $value->label_bn,
                        'description' => $valueData['description'] ?? $value->description,
                        'price_modifier' => $valueData['price_modifier'] ?? $value->price_modifier,
                        'is_default' => $valueData['is_default'] ?? $value->is_default,
                        'is_active' => $valueData['is_active'] ?? $value->is_active,
                        'display_order' => $valueData['display_order'] ?? $value->display_order,
                    ]);
                    $existingValueIds[] = $value->id;
                }
            } else {
                // Create new value
                $newValue = $this->createValue($option, $valueData);
                $existingValueIds[] = $newValue->id;
            }
        }

        // Delete values not in the list
        $option->values()->whereNotIn('id', $existingValueIds)->delete();
    }

    /**
     * Sync category assignments for an option.
     */
    protected function syncCategories(GlobalServiceOption $option, array $categoryIds, bool $appliesToAll = false): void
    {
        // Delete existing assignments
        $option->categoryAssignments()->delete();

        if ($appliesToAll) {
            // Create a single assignment with applies_to_all = true
            OptionCategoryAssignment::create([
                'option_id' => $option->id,
                'category_id' => null,
                'applies_to_all' => true,
            ]);
        } else {
            // Create assignments for specific categories
            foreach ($categoryIds as $categoryId) {
                OptionCategoryAssignment::create([
                    'option_id' => $option->id,
                    'category_id' => $categoryId,
                    'applies_to_all' => false,
                ]);
            }
        }
    }
}
