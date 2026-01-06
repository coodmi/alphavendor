<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

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
                'logo' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Global sports apparel and equipment manufacturer',
                'logo' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Leading electronics and technology company',
                'logo' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Premium technology and consumer electronics',
                'logo' => 'https://images.unsplash.com/photo-1611472173362-3f53dbd65d80?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Electronics, gaming and entertainment brand',
                'logo' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'Computer hardware and software solutions',
                'logo' => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Computers and technology solutions provider',
                'logo' => 'https://images.unsplash.com/photo-1593642532400-2682810df593?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'LG',
                'slug' => 'lg',
                'description' => 'Home appliances and consumer electronics',
                'logo' => 'https://images.unsplash.com/photo-1585909695284-32d2985ac9c0?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'description' => 'Athletic and casual footwear and apparel',
                'logo' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Canon',
                'slug' => 'canon',
                'description' => 'Imaging and optical products manufacturer',
                'logo' => 'https://images.unsplash.com/photo-1606952278920-8a16ed9d1eb8?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'description' => 'Personal computers and technology devices',
                'logo' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Asus',
                'slug' => 'asus',
                'description' => 'Computer hardware and electronics manufacturer',
                'logo' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=200&h=200&fit=crop',
                'is_active' => true,
                'sort_order' => 12,
            ],
        ];

        Brand::insert($brands);
        $this->command->info('Brands seeded successfully!');
    }
}
