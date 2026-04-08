<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Size',
                'type' => 'select',
                'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'],
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Color',
                'type' => 'color',
                'options' => null,
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Material',
                'type' => 'select',
                'options' => ['Cotton', 'Polyester', 'Silk', 'Wool', 'Leather', 'Denim', 'Linen'],
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Weight',
                'type' => 'number',
                'options' => null,
                'is_required' => false,
                'is_filterable' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Brand',
                'type' => 'text',
                'options' => null,
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Warranty',
                'type' => 'select',
                'options' => ['No Warranty', '6 Months', '1 Year', '2 Years', '3 Years', 'Lifetime'],
                'is_required' => false,
                'is_filterable' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($attribute['name'])],
                $attribute
            );
        }
    }
}
