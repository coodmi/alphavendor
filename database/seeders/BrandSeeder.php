<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Leading sportswear and athletic footwear brand',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Global sports apparel and equipment manufacturer',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Leading electronics and technology company',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Premium technology and consumer electronics',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Electronics, gaming and entertainment brand',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'Computer hardware and software solutions',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Computers and technology solutions provider',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'LG',
                'slug' => 'lg',
                'description' => 'Home appliances and consumer electronics',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'description' => 'Athletic and casual footwear and apparel',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Canon',
                'slug' => 'canon',
                'description' => 'Imaging and optical products manufacturer',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'description' => 'Personal computers and technology devices',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Asus',
                'slug' => 'asus',
                'description' => 'Computer hardware and electronics manufacturer',
                'is_active' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
