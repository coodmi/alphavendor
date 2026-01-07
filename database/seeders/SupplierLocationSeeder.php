<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplierLocation;

class SupplierLocationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'China',
                'description' => 'Major manufacturing and export hub with competitive pricing',
                'country' => 'China',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'United States',
                'description' => 'High-quality products with fast domestic shipping',
                'country' => 'USA',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'India',
                'description' => 'Cost-effective sourcing with diverse product range',
                'country' => 'India',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Germany',
                'description' => 'Premium quality manufacturing and engineering excellence',
                'country' => 'Germany',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Vietnam',
                'description' => 'Emerging manufacturing hub with competitive rates',
                'country' => 'Vietnam',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Mexico',
                'description' => 'Strategic location for North American distribution',
                'country' => 'Mexico',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Thailand',
                'description' => 'Quality products with efficient logistics',
                'country' => 'Thailand',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Turkey',
                'description' => 'Gateway between Europe and Asia with diverse products',
                'country' => 'Turkey',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'South Korea',
                'description' => 'Advanced technology and high-quality manufacturing',
                'country' => 'South Korea',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Bangladesh',
                'description' => 'Textile and garment manufacturing specialist',
                'country' => 'Bangladesh',
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($locations as $location) {
            SupplierLocation::create($location);
        }
    }
}
