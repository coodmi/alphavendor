<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImportSlide;

class ImportSlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title' => 'International Import Marketplace',
                'description' => 'Connect with verified international suppliers and source quality products globally with seamless logistics support.',
                'image' => 'https://images.unsplash.com/photo-1548588627-f978862b85e1?w=1600&h=600&fit=crop',
                'cta_text' => 'Start Importing',
                'cta_link' => '/shop',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Global Trade Made Easy',
                'description' => 'Import premium products from worldwide suppliers with complete documentation support',
                'image' => 'https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=1600&h=600&fit=crop',
                'cta_text' => 'View Documentation',
                'cta_link' => '/import',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Trusted International Suppliers',
                'description' => 'Access verified suppliers from multiple countries with quality assurance',
                'image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1600&h=600&fit=crop',
                'cta_text' => 'Explore Suppliers',
                'cta_link' => '/sellers',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            ImportSlide::create($slide);
        }
    }
}
