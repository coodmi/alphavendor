<?php

namespace App\Services;

use App\Models\GlobalServiceOption;
use App\Models\OptionValue;
use App\Models\OrderItem;
use App\Models\OrderItemOption;
use Illuminate\Support\Collection;

class OrderCaptureService
{
    /**
     * Capture selected options with order item.
     */
    public function captureOptions(OrderItem $orderItem, array $selectedOptions): void
    {
        foreach ($selectedOptions as $key => $value) {
            if (strpos($key, 'option_') === 0 && !empty($value)) {
                $optionId = str_replace('option_', '', $key);
                $option = GlobalServiceOption::find($optionId);

                if (!$option) {
                    continue;
                }

                $optionData = [
                    'order_item_id' => $orderItem->id,
                    'option_name' => $option->name_en,
                    'option_type' => $option->type,
                    'price_modifier' => 0,
                ];

                // Handle different option types
                if (in_array($option->type, ['radio', 'button_group'])) {
                    // Value is the option_value_id
                    $optionValue = OptionValue::find($value);
                    
                    if ($optionValue) {
                        $optionData['selected_value_label'] = $optionValue->label_en;
                        $optionData['price_modifier'] = $optionValue->price_modifier;
                    }
                } elseif (in_array($option->type, ['textarea', 'text', 'number'])) {
                    // Value is custom input
                    $optionData['custom_input'] = $value;
                }

                OrderItemOption::create($optionData);
            }
        }
    }

    /**
     * Get captured options for an order item.
     */
    public function getOrderItemOptions(OrderItem $orderItem): Collection
    {
        return $orderItem->options()
            ->get()
            ->map(function ($option) {
                return [
                    'name' => $option->option_name,
                    'type' => $option->option_type,
                    'value' => $option->selected_value_label ?? $option->custom_input,
                    'price_modifier' => $option->price_modifier,
                    'formatted_price' => $option->formatted_price,
                ];
            });
    }
}
