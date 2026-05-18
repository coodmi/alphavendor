<?php

namespace App\Console\Commands;

use App\Models\SpecialOffer;
use Illuminate\Console\Command;

class SeedDemoSpecialOffer extends Command
{
    protected $signature = 'offers:seed-demo';

    protected $description = 'Create or refresh a demo special offer for testing product forms';

    public function handle(): int
    {
        $offer = SpecialOffer::updateOrCreate(
            ['slug' => 'demo-offer'],
            [
                'name' => 'Demo Offer — 20% Off',
                'description' => 'Demo special offer for testing retailer and wholesaler product forms.',
                'badge_text' => '20% OFF',
                'badge_color' => '#4F46E5',
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        $this->info("Demo special offer ready: [{$offer->id}] {$offer->name} (slug: {$offer->slug})");
        $this->line('Select it in Add Product → Special Offer on the retailer or wholesaler dashboard.');

        return self::SUCCESS;
    }
}
