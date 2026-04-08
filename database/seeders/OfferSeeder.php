<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $offers = [
            [
                'name' => 'Flash Sale',
                'description' => 'Limited time flash sale offers',
                'badge_text' => 'FLASH SALE',
                'badge_color' => '#e74c3c',
                'icon' => 'fas fa-bolt',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Buy One Get One',
                'description' => 'Buy one get one free offers',
                'badge_text' => 'BOGO',
                'badge_color' => '#f39c12',
                'icon' => 'fas fa-gift',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Clearance Sale',
                'description' => 'Clearance and closeout items',
                'badge_text' => 'CLEARANCE',
                'badge_color' => '#9b59b6',
                'icon' => 'fas fa-tags',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'New Arrival',
                'description' => 'Newly added products',
                'badge_text' => 'NEW',
                'badge_color' => '#3498db',
                'icon' => 'fas fa-star',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Hot Deal',
                'description' => 'Hot deals and trending products',
                'badge_text' => 'HOT',
                'badge_color' => '#e67e22',
                'icon' => 'fas fa-fire',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Best Seller',
                'description' => 'Best selling products',
                'badge_text' => 'BEST SELLER',
                'badge_color' => '#27ae60',
                'icon' => 'fas fa-trophy',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
