<?php

namespace App\Services;

use App\Models\OptionValue;
use App\Models\ServiceProduct;
use Illuminate\Support\Collection;

class PriceCalculatorService
{
    /**
     * Calculate total price including option modifiers.
     */
    public function calculateTotalPrice(ServiceProduct $product, array $selectedOptions): array
    {
        $basePrice = (float) $product->price;
        $options = [];
        $total = $basePrice;

        foreach ($selectedOptions as $key => $value) {
            if (strpos($key, 'option_') === 0 && !empty($value)) {
                // For radio/button_group options, value is the option_value_id
                if (is_numeric($value)) {
                    $optionValue = OptionValue::find($value);
                    
                    if ($optionValue) {
                        $modifier = (float) $optionValue->price_modifier;
                        $total += $modifier;
                        
                        $options[] = [
                            'name' => $optionValue->option->name,
                            'value' => $optionValue->label,
                            'modifier' => $modifier,
                            'modifier_formatted' => $this->formatPrice($modifier),
                        ];
                    }
                }
            }
        }

        return [
            'base_price' => $basePrice,
            'base_price_formatted' => '৳' . number_format($basePrice, 2),
            'options' => $options,
            'total' => $total,
            'total_formatted' => '৳' . number_format($total, 2),
        ];
    }

    /**
     * Get price breakdown for display.
     */
    public function getPriceBreakdown(float $basePrice, Collection $selectedOptions): array
    {
        $breakdown = [
            'base_price' => $basePrice,
            'base_price_formatted' => '৳' . number_format($basePrice, 2),
            'options' => [],
            'total' => $basePrice,
        ];

        foreach ($selectedOptions as $option) {
            $modifier = (float) $option->price_modifier;
            $breakdown['total'] += $modifier;
            
            $breakdown['options'][] = [
                'name' => $option->option_name ?? $option->name,
                'value' => $option->selected_value_label ?? $option->label,
                'modifier' => $modifier,
                'modifier_formatted' => $this->formatPrice($modifier),
            ];
        }

        $breakdown['total_formatted'] = '৳' . number_format($breakdown['total'], 2);

        return $breakdown;
    }

    /**
     * Format price with Bangla currency symbol.
     */
    protected function formatPrice(float $amount): string
    {
        if ($amount == 0) {
            return 'Free';
        }

        if ($amount > 0) {
            return '+৳' . number_format($amount, 2);
        }

        return '-৳' . number_format(abs($amount), 2);
    }
}
