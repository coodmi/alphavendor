<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = Product::all();
        
        // Get regular users (not vendors or admins)
        $users = User::where('role', 'user')->get();
        
        // If no regular users exist, create some
        if ($users->isEmpty()) {
            $users = collect([
                User::create([
                    'name' => 'John Smith',
                    'email' => 'john.smith@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'status' => 'active',
                ]),
                User::create([
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.johnson@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'status' => 'active',
                ]),
                User::create([
                    'name' => 'Michael Brown',
                    'email' => 'michael.brown@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'status' => 'active',
                ]),
                User::create([
                    'name' => 'Emily Davis',
                    'email' => 'emily.davis@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'status' => 'active',
                ]),
                User::create([
                    'name' => 'David Wilson',
                    'email' => 'david.wilson@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                    'status' => 'active',
                ]),
            ]);
        }

        // Sample review data
        $reviewTemplates = [
            [
                'rating' => 5,
                'title' => 'Excellent Product!',
                'comment' => 'This product exceeded my expectations. The quality is outstanding and it arrived quickly. Highly recommend to anyone looking for a reliable product.',
            ],
            [
                'rating' => 5,
                'title' => 'Best Purchase Ever',
                'comment' => 'I am extremely satisfied with this purchase. The product works perfectly and the customer service was excellent. Will definitely buy again!',
            ],
            [
                'rating' => 4,
                'title' => 'Very Good Quality',
                'comment' => 'Great product overall. The quality is very good and it does exactly what it promises. Only minor issue was the packaging could be better.',
            ],
            [
                'rating' => 5,
                'title' => 'Highly Recommended',
                'comment' => 'Fantastic product! Worth every penny. The build quality is excellent and it has all the features I needed. Customer support was also very helpful.',
            ],
            [
                'rating' => 4,
                'title' => 'Good Value for Money',
                'comment' => 'This is a solid product for the price. It works well and seems durable. Delivery was fast and the seller was responsive to my questions.',
            ],
            [
                'rating' => 5,
                'title' => 'Perfect!',
                'comment' => 'Exactly what I was looking for! The product quality is top-notch and it arrived in perfect condition. Very happy with this purchase.',
            ],
            [
                'rating' => 3,
                'title' => 'Decent Product',
                'comment' => 'The product is okay. It works as described but nothing exceptional. For the price, it is acceptable but there is room for improvement.',
            ],
            [
                'rating' => 5,
                'title' => 'Outstanding Quality',
                'comment' => 'I have been using this product for a few weeks now and it has been excellent. The quality is outstanding and it has made my life much easier.',
            ],
            [
                'rating' => 4,
                'title' => 'Great Product',
                'comment' => 'Very pleased with this purchase. The product is well-made and functions perfectly. Shipping was quick and packaging was secure.',
            ],
            [
                'rating' => 5,
                'title' => 'Love It!',
                'comment' => 'This product is amazing! It has exceeded all my expectations. The quality, functionality, and design are all perfect. Highly recommend!',
            ],
        ];

        // Add reviews to products
        foreach ($products->take(20) as $product) {
            // Add 3-7 random reviews per product
            $reviewCount = rand(3, 7);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                $template = $reviewTemplates[array_rand($reviewTemplates)];
                $user = $users->random();
                
                // Check if this user already reviewed this product
                $existingReview = Review::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();
                
                if ($existingReview) {
                    continue; // Skip if already reviewed
                }
                
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_id' => null,
                    'rating' => $template['rating'],
                    'title' => $template['title'],
                    'comment' => $template['comment'],
                    'status' => 'approved',
                    'is_verified_purchase' => rand(0, 1) == 1,
                    'helpful_votes' => rand(0, 25),
                    'reported_count' => 0,
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }

        // Update product ratings based on reviews
        foreach ($products as $product) {
            $reviews = Review::where('product_id', $product->id)
                ->where('status', 'approved')
                ->get();
            
            if ($reviews->count() > 0) {
                $averageRating = $reviews->avg('rating');
                $product->update([
                    'rating' => round($averageRating, 1),
                    'reviews_count' => $reviews->count(),
                ]);
            }
        }

        $this->command->info('Demo reviews created successfully!');
    }
}
