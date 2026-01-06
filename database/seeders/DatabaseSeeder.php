<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            VendorSeeder::class,
            CategorySeeder::class,      // Admin categories
            BrandSeeder::class,          // Admin brands
            ProductSeeder::class,        // Admin products
            RetailerBrandSeeder::class,      // Retailer brands
            RetailerCategorySeeder::class,   // Retailer categories
            RetailerProductsSeeder::class,   // Retailer products
            WholesalerBrandSeeder::class,    // Wholesaler brands
            WholesalerCategorySeeder::class, // Wholesaler categories
            WholesalerProductSeeder::class,  // Wholesaler products
            ExporterBrandSeeder::class,      // Exporter brands
            ExporterCategorySeeder::class,   // Exporter categories
            ExporterProductSeeder::class,    // Exporter products
            BannerSeeder::class,             // Homepage banners
        ]);
    }
}
