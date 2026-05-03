<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class DemoReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a category
        $cat = Category::first();
        if (!$cat) {
            $cat = Category::create([
                'name' => 'General',
                'slug' => 'general',
                'status' => 'active',
            ]);
        }

        // Get or create a brand
        $brand = Brand::first();
        if (!$brand) {
            $brand = Brand::create([
                'name' => 'AR Brand',
                'slug' => 'ar-brand',
                'status' => 'active',
            ]);
        }

        // Get a vendor/seller user
        $vendor = User::whereIn('role', ['retailer', 'wholesaler', 'admin'])->first()
            ?? User::first();

        // Create 3 demo products if none exist
        $productData = [
            ['name' => 'Premium Cotton T-Shirt', 'price' => 450],
            ['name' => 'Leather Wallet',          'price' => 850],
            ['name' => 'Wireless Earbuds',         'price' => 1200],
        ];

        $products = [];
        foreach ($productData as $pd) {
            $slug = Str::slug($pd['name']);
            $products[] = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name'          => $pd['name'],
                    'slug'          => $slug,
                    'description'   => 'High quality product available at AR Market BD.',
                    'price'         => $pd['price'],
                    'stock'         => 50,
                    'status'        => 'active',
                    'category_id'   => $cat->id,
                    'vendor_id'     => $vendor->id,
                    'image'         => 'products/demo-product.jpg',
                    'rating'        => 0,
                    'reviews_count' => 0,
                ]
            );
        }

        // Create 3 demo reviewer users
        $reviewerData = [
            ['name' => 'Rahim Uddin',    'email' => 'rahim.demo@armarket.com'],
            ['name' => 'Fatema Begum',   'email' => 'fatema.demo@armarket.com'],
            ['name' => 'Karim Hossain',  'email' => 'karim.demo@armarket.com'],
        ];

        $users = [];
        foreach ($reviewerData as $r) {
            $users[] = User::firstOrCreate(
                ['email' => $r['email']],
                [
                    'name'     => $r['name'],
                    'password' => bcrypt('password'),
                    'role'     => 'user',
                    'status'   => 'active',
                ]
            );
        }

        // Create 3 demo reviews
        $reviews = [
            [
                'user'    => $users[0],
                'product' => $products[0],
                'rating'  => 5,
                'title'   => 'Excellent Quality!',
                'comment' => 'The cotton quality is superb. Very comfortable to wear and the stitching is perfect. Delivery was fast too. Highly recommended!',
                'days'    => 7,
            ],
            [
                'user'    => $users[1],
                'product' => $products[1],
                'rating'  => 4,
                'title'   => 'Great Leather Wallet',
                'comment' => 'Very nice wallet, genuine leather feel and good stitching. Fits all my cards perfectly. Only wish it had one more card slot.',
                'days'    => 5,
            ],
            [
                'user'    => $users[2],
                'product' => $products[2],
                'rating'  => 5,
                'title'   => 'Amazing Sound Quality',
                'comment' => 'These earbuds are fantastic! Crystal clear sound, great bass, and the battery lasts all day. Best purchase I have made this year.',
                'days'    => 3,
            ],
        ];

        foreach ($reviews as $rd) {
            Review::firstOrCreate(
                ['user_id' => $rd['user']->id, 'product_id' => $rd['product']->id],
                [
                    'rating'               => $rd['rating'],
                    'title'                => $rd['title'],
                    'comment'              => $rd['comment'],
                    'status'               => 'approved',
                    'is_verified_purchase' => true,
                    'helpful_votes'        => rand(3, 15),
                    'reported_count'       => 0,
                    'created_at'           => now()->subDays($rd['days']),
                    'updated_at'           => now()->subDays($rd['days']),
                ]
            );
        }

        // Update product ratings
        foreach ($products as $product) {
            $productReviews = Review::where('product_id', $product->id)
                ->where('status', 'approved')
                ->get();

            if ($productReviews->count() > 0) {
                $product->update([
                    'rating'        => round($productReviews->avg('rating'), 1),
                    'reviews_count' => $productReviews->count(),
                ]);
            }
        }

        $this->command->info('3 demo reviews created successfully!');
    }
}
