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
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            RetailerBrandSeeder::class,
            RetailerCategorySeeder::class,
            RetailerProductsSeeder::class,
            WholesalerBrandSeeder::class,
            WholesalerCategorySeeder::class,
            WholesalerProductSeeder::class,
            ExporterBrandSeeder::class,
            ExporterCategorySeeder::class,
            ExporterProductSeeder::class,
            CertificationSeeder::class,
            BannerSeeder::class,
            SpecialOfferSeeder::class,
            TestAccountsSeeder::class,   // ← test accounts (password: password123)
        ]);
    }
}
