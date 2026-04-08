<?php

namespace App\Http\Controllers;

use App\Models\PageSection;
use App\Models\Product;

class ProductConfigController extends Controller
{
    /**
     * Product type to view mapping.
     */
    protected array $viewMapping = [
        'books' => 'products.configure.book',
        'business-cards' => 'products.configure.business-card',
        'stickers' => 'products.configure.marketing-material', // Fallback for now to marketing
        'postcards-invitations' => 'products.configure.invitation-stationery',
        'booklets' => 'products.configure.book',
        'catalogs' => 'products.configure.book',
        'magazines' => 'products.configure.book',
        'brochures' => 'products.configure.marketing-material',
        'banners' => 'products.configure.banner',
        'signage' => 'products.configure.banner', // Signage uses banner template
        'stationery' => 'products.configure.invitation-stationery',
        'promotional-items' => 'products.configure.marketing-material',
        'marketing' => 'products.configure.marketing-material',
    ];

    /**
     * Product titles mapping.
     */
    protected array $productTitles = [
        // Books
        'paperback' => 'Paperback Book',
        'hardback' => 'Hardback Book',
        'special-finish-hardback' => 'Special Finish Hardback',
        'comics' => 'Comics',
        'self-published' => 'Self-published Book',
        'recipe-book' => 'Recipe Book',
        'cookery-book' => 'Cookery Book',
        'pocket-sized' => 'Pocket-sized Book',
        'lookbook' => 'Lookbook',
        'manga' => 'Manga',

        // Business Cards
        'classic' => 'Classic Business Cards',
        'square' => 'Square Business Cards',
        'rounded-corners' => 'Rounded Corner Business Cards',
        'matte' => 'Matte Business Cards',
        'glossy' => 'Glossy Business Cards',
        'premium' => 'Premium Business Cards',

        // Stickers
        'die-cut' => 'Die Cut Stickers',
        'kiss-cut' => 'Kiss Cut Stickers',
        'vinyl' => 'Vinyl Stickers',
        'clear' => 'Clear Stickers',
        'holographic' => 'Holographic Stickers',
        'bumper' => 'Bumper Stickers',

        // Postcards
        'standard' => 'Standard Postcards',
        'oversized' => 'Oversized Postcards',
        'invitations' => 'Invitations',

        // And more...
    ];

    /**
     * Display product configuration page.
     */
    public function show(string $product, string $category)
    {
        $view = $this->viewMapping[$category] ?? 'products.configure.book';

        // Check if view exists, fallback to book template
        if (! view()->exists($view)) {
            $view = 'products.configure.book';
        }

        // Check for editable content in database
        $section = PageSection::where('page', 'category_product')
            ->where('section_key', $category.'_'.$product)
            ->first();

        // Use editable content if exists, otherwise use defaults
        if ($section && ! empty($section->content)) {
            $productTitle = $section->content['title'] ?? $this->productTitles[$product] ?? ucwords(str_replace('-', ' ', $product));
            $productSubtitle = $section->content['subtitle'] ?? '';
            $productDescription = $section->content['description'] ?? '';
            $productImage = $section->content['hero_image'] ?? $this->getProductImage($category, $product);
            $specifications = $section->content['specifications'] ?? [];
            $basePrice = $section->content['base_price'] ?? 0;
            $minQuantity = $section->content['min_quantity'] ?? 1;
            $minPages = $section->content['min_pages'] ?? 1;
            $maxPages = $section->content['max_pages'] ?? 500;
            $pricingTiers = $section->content['pricing_tiers'] ?? [];
            $configOptions = $section->content['config_options'] ?? [
                'sizes' => [],
                'paper_types' => [],
                'finishes' => [],
            ];
        } else {
            // Try to find Product in DB
            $dbProduct = \App\Models\Product::where('slug', $product)->first();

            if ($dbProduct) {
                $productTitle = $dbProduct->title;
                $productSubtitle = $dbProduct->subtitle ?? ''; // Assuming subtitle might be added later or use description excerpt
                $productDescription = $dbProduct->description;
                $productImage = $dbProduct->image;
                $basePrice = $dbProduct->base_price > 0 ? $dbProduct->base_price : $dbProduct->price; // Use base_price if set, else price
                $minQuantity = $dbProduct->min_quantity ?? 1;
                $minPages = $dbProduct->min_pages ?? 1;
                $maxPages = $dbProduct->max_pages ?? 500;
                $specifications = []; // Could map attributes here if needed
                $pricingTiers = []; // Pricing tiers not yet in Product model, maybe add later

                // Merge default empty structure with db config options to ensure keys exist
                $defaultOptions = [
                    'sizes' => [],
                    'paper_types' => [],
                    'finishes' => [],
                    'bindings' => [],
                    'orientations' => [],
                    'cover_papers' => [],
                    'coatings' => [],
                ];
                $configOptions = array_merge($defaultOptions, $dbProduct->config_options ?? []);
            } else {
                // Use hardcoded defaults as fallback
                $productTitle = $this->productTitles[$product] ?? ucwords(str_replace('-', ' ', $product));
                $productSubtitle = '';
                $productDescription = '';
                $productImage = $this->getProductImage($category, $product);
                $specifications = [];
                $basePrice = 0;
                $minQuantity = 1;
                $minPages = 1;
                $maxPages = 500;
                $pricingTiers = [];
                $configOptions = [
                    'sizes' => [
                        ['label' => 'A4', 'price' => 0],
                        ['label' => 'A5', 'price' => 0],
                        ['label' => 'Custom', 'price' => 0],
                    ],
                    'paper_types' => [
                        ['label' => '80gsm', 'value' => '80gsm', 'price' => 0],
                        ['label' => '100gsm', 'value' => '100gsm', 'price' => 0],
                        ['label' => '120gsm', 'value' => '120gsm', 'price' => 0],
                    ],
                    'finishes' => [
                        ['label' => 'Matte', 'value' => 'Matte', 'price' => 0],
                        ['label' => 'Glossy', 'value' => 'Glossy', 'price' => 0],
                    ],
                ];
            }
        }

        return view($view, [
            'productType' => $product,
            'productTitle' => $productTitle,
            'productSubtitle' => $productSubtitle,
            'productDescription' => $productDescription,
            'productImage' => $productImage,
            'category' => $category,
            'specifications' => $specifications,
            'basePrice' => $basePrice,
            'minQuantity' => $minQuantity,
            'minPages' => $minPages,
            'maxPages' => $maxPages,
            'pricingTiers' => $pricingTiers,
            'configOptions' => $configOptions,
        ]);
    }

    /**
     * Get product image from PageSection defaults.
     */
    protected function getProductImage(string $category, string $product): string
    {
        // 1. Try to find in database first
        // 1. Try to find in database first
        $dbProduct = \App\Models\Product::where('slug', $product)->first();
        if ($dbProduct && $dbProduct->image) {
            return $dbProduct->image;
        }

        // 2. Fallback to PageSection defaults
        $defaults = PageSection::getDefaults($category);
        $products = $defaults['products'] ?? [];

        foreach ($products as $p) {
            // Check if URL ends with the product slug
            $url = $p['url'] ?? '';
            // We need to be more flexible with matching, but precise enough to avoid partial matches
            // e.g. 'business-cards' shouldn't match '/business-cards/classic' just because it's a substring
            if (str_ends_with($url, '/'.$product) || $url === $product) {
                $img = $p['img'] ?? '';
                // Convert small image to larger size for hero display
                if ($img) {
                    // Replace w=300&h=300 with w=800&h=600 for better quality
                    $img = preg_replace('/w=\d+/', 'w=800', $img);
                    $img = preg_replace('/h=\d+/', 'h=600', $img);

                    return $img;
                }
            }
        }

        // 3. Default fallback images per category
        $fallbackImages = [
            'books' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&h=600&fit=crop',
            'magazines' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=800&h=600&fit=crop',
            'catalogs' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&h=600&fit=crop',
            'brochures' => 'https://images.unsplash.com/photo-1586880244406-556ebe35f282?w=800&h=600&fit=crop',
            'business-cards' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=800&h=600&fit=crop',
            'postcards-invitations' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800&h=600&fit=crop',
            'banners' => 'https://images.unsplash.com/photo-1540317580384-e5d43616528e?w=800&h=600&fit=crop',
            'stickers' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
            'stationery' => 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=800&h=600&fit=crop',
            'promotional-items' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&h=600&fit=crop',
        ];

        $fallback = $fallbackImages[$category] ?? null;
        if ($fallback) {
            return $fallback;
        }

        return 'https://placehold.co/800x600?text='.urlencode($product);
    }
}
