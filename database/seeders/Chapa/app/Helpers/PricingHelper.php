<?php

namespace App\Helpers;

class PricingHelper
{
    /**
     * Get default pricing tiers for a product category
     *
     * @param string $category
     * @return array
     */
    public static function getPricingTiers(string $category): array
    {
        $pricingData = [
            'books' => [
                ['min' => 1, 'max' => 50, 'price' => 150, 'unit' => 'copy'],
                ['min' => 51, 'max' => 100, 'price' => 135, 'unit' => 'copy'],
                ['min' => 101, 'max' => 250, 'price' => 120, 'unit' => 'copy'],
                ['min' => 251, 'price' => 100, 'unit' => 'copy'],
            ],
            'business-cards' => [
                ['min' => 100, 'max' => 250, 'price' => 500, 'unit' => 'set'],
                ['min' => 251, 'max' => 500, 'price' => 900, 'unit' => 'set'],
                ['min' => 501, 'max' => 1000, 'price' => 1600, 'unit' => 'set'],
                ['min' => 1001, 'price' => 2800, 'unit' => 'set'],
            ],
            'brochures' => [
                ['min' => 50, 'max' => 100, 'price' => 800, 'unit' => 'set'],
                ['min' => 101, 'max' => 250, 'price' => 1500, 'unit' => 'set'],
                ['min' => 251, 'max' => 500, 'price' => 2500, 'unit' => 'set'],
                ['min' => 501, 'price' => 4000, 'unit' => 'set'],
            ],
            'banners' => [
                ['min' => 1, 'max' => 5, 'price' => 1200, 'unit' => 'piece'],
                ['min' => 6, 'max' => 10, 'price' => 1000, 'unit' => 'piece'],
                ['min' => 11, 'max' => 25, 'price' => 900, 'unit' => 'piece'],
                ['min' => 26, 'price' => 800, 'unit' => 'piece'],
            ],
            'stickers' => [
                ['min' => 100, 'max' => 500, 'price' => 600, 'unit' => 'set'],
                ['min' => 501, 'max' => 1000, 'price' => 1000, 'unit' => 'set'],
                ['min' => 1001, 'max' => 2500, 'price' => 1800, 'unit' => 'set'],
                ['min' => 2501, 'price' => 3500, 'unit' => 'set'],
            ],
            'catalogs' => [
                ['min' => 25, 'max' => 50, 'price' => 1500, 'unit' => 'set'],
                ['min' => 51, 'max' => 100, 'price' => 2500, 'unit' => 'set'],
                ['min' => 101, 'max' => 250, 'price' => 4500, 'unit' => 'set'],
                ['min' => 251, 'price' => 8000, 'unit' => 'set'],
            ],
            'magazines' => [
                ['min' => 50, 'max' => 100, 'price' => 2000, 'unit' => 'set'],
                ['min' => 101, 'max' => 250, 'price' => 3500, 'unit' => 'set'],
                ['min' => 251, 'max' => 500, 'price' => 6000, 'unit' => 'set'],
                ['min' => 501, 'price' => 10000, 'unit' => 'set'],
            ],
            'stationery' => [
                ['min' => 100, 'max' => 250, 'price' => 800, 'unit' => 'set'],
                ['min' => 251, 'max' => 500, 'price' => 1400, 'unit' => 'set'],
                ['min' => 501, 'max' => 1000, 'price' => 2400, 'unit' => 'set'],
                ['min' => 1001, 'price' => 4000, 'unit' => 'set'],
            ],
        ];

        return $pricingData[$category] ?? self::getDefaultPricing();
    }

    /**
     * Get default pricing when category not found
     *
     * @return array
     */
    private static function getDefaultPricing(): array
    {
        return [
            ['min' => 1, 'max' => 50, 'price' => 500, 'unit' => 'unit'],
            ['min' => 51, 'max' => 100, 'price' => 450, 'unit' => 'unit'],
            ['min' => 101, 'max' => 250, 'price' => 400, 'unit' => 'unit'],
            ['min' => 251, 'price' => 350, 'unit' => 'unit'],
        ];
    }

    /**
     * Calculate price based on quantity
     *
     * @param string $category
     * @param int $quantity
     * @return float
     */
    public static function calculatePrice(string $category, int $quantity): float
    {
        $tiers = self::getPricingTiers($category);

        foreach ($tiers as $tier) {
            if ($quantity >= $tier['min']) {
                if (isset($tier['max']) && $quantity <= $tier['max']) {
                    return $tier['price'];
                } elseif (!isset($tier['max'])) {
                    return $tier['price'];
                }
            }
        }

        return $tiers[0]['price'] ?? 0;
    }

    /**
     * Get pricing note for a category
     *
     * @param string $category
     * @return string
     */
    public static function getPricingNote(string $category): string
    {
        $notes = [
            'books' => 'Prices include standard binding. Premium binding options available at additional cost.',
            'business-cards' => 'Prices are for standard 350gsm cardstock. Premium materials available.',
            'brochures' => 'Prices include folding. Custom sizes and finishes available.',
            'banners' => 'Prices for standard vinyl material. Weather-resistant options available.',
            'stickers' => 'Prices for standard vinyl stickers. Die-cut and custom shapes available.',
            'catalogs' => 'Prices include saddle stitch binding. Perfect binding available for thicker catalogs.',
            'magazines' => 'Prices include glossy cover and standard paper. Premium options available.',
            'stationery' => 'Prices for standard letterhead. Envelopes and business cards can be bundled.',
        ];

        return $notes[$category] ?? 'Contact us for custom pricing and bulk discounts.';
    }
}
