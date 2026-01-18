<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'type' => 'select',
                'options' => ['Red', 'Blue', 'Green', 'Black', 'White'],
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Size',
                'type' => 'select',
                'options' => ['S', 'M', 'L', 'XL', 'XXL'],
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Material',
                'type' => 'text',
                'is_required' => false,
                'is_filterable' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Weight',
                'type' => 'number',
                'is_required' => false,
                'is_filterable' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
