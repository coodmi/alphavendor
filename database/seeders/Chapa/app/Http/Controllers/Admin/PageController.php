<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Upload an image and return the URL.
     * Used by AdminImageInput component for immediate file uploads.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:15360', // 15MB
            'folder' => 'nullable|string|max:50',
        ]);

        $folder = $request->input('folder', 'images');
        // Sanitize folder name
        $folder = preg_replace('/[^a-zA-Z0-9\-_]/', '', $folder);
        if (empty($folder)) {
            $folder = 'images';
        }
        
        $uploadsPath = public_path('uploads/' . $folder);
        if (!file_exists($uploadsPath)) {
            mkdir($uploadsPath, 0755, true);
        }

        $imageFile = $request->file('image');
        
        // Sanitize filename
        $originalName = preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $imageFile->getClientOriginalName());
        $filename = time() . '_' . uniqid() . '_' . $originalName;
        
        $imageFile->move($uploadsPath, $filename);

        $url = '/uploads/' . $folder . '/' . $filename;

        return response()->json([
            'success' => true,
            'url' => $url,
        ]);
    }

    /**
     * Edit a standard page (About, Contact, FAQ, Terms, Privacy).
     */
    public function editStandard($slug)
    {
        $validPages = ['about', 'contact', 'faq', 'terms', 'privacy'];
        
        if (!in_array($slug, $validPages)) {
            abort(404);
        }

        $sections = PageSection::forPage($slug)->get()->map(function ($section) {
            return [
                'section_key' => $section->section_key,
                'content' => $section->content,
            ];
        });

        return Inertia::render('Admin/Pages/EditStandard', [
            'slug' => $slug,
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'sections' => $sections,
        ]);
    }

    /**
     * Update a standard page.
     */
    public function updateStandard(Request $request, $slug)
    {
        $validPages = ['about', 'contact', 'faq', 'terms', 'privacy'];
        
        if (!in_array($slug, $validPages)) {
            abort(404);
        }

        $sections = $request->input('sections', []);

        foreach ($sections as $sectionData) {
            PageSection::updateOrCreate(
                [
                    'page' => $slug,
                    'section_key' => $sectionData['section_key'],
                ],
                [
                    'content' => $sectionData['content'],
                    'is_active' => true,
                ]
            );
        }

        return back()->with('success', 'Page updated successfully');
    }

    /**
     * Display page management dashboard.
     */
    public function index()
    {
        $pages = [
            [
                'name' => 'Home Page',
                'slug' => 'home',
                'description' => 'Hero slider, headlines, testimonials, offers',
                'sections_count' => PageSection::where('page', 'home')->count(),
                'icon' => 'home',
            ],
        ];

        // Add category pages
        $categories = [
            ['slug' => 'magazines', 'name' => 'Magazines', 'icon' => 'newspaper'],
            ['slug' => 'books', 'name' => 'Books', 'icon' => 'book-open'],
            ['slug' => 'catalogs', 'name' => 'Catalog', 'icon' => 'folder'],
            ['slug' => 'brochures', 'name' => 'Marketing Material', 'icon' => 'megaphone'],
            ['slug' => 'business-cards', 'name' => 'Business Cards', 'icon' => 'credit-card'],
            ['slug' => 'postcards-invitations', 'name' => 'Invitation & Stationery', 'icon' => 'mail'],
            ['slug' => 'banners', 'name' => 'Banners', 'icon' => 'flag'],
            ['slug' => 'promotional-items', 'name' => 'Promotional Items', 'icon' => 'gift'],
            ['slug' => 'stickers', 'name' => 'Stickers', 'icon' => 'sticker'],
            ['slug' => 'booklets', 'name' => 'Booklets', 'icon' => 'book'],
            ['slug' => 'stationery', 'name' => 'Stationery', 'icon' => 'pen-tool'],
        ];

        return view('admin.pages.index', [
            'pages' => $pages,
            'categories' => $categories,
        ]);
    }

    /**
     * Edit home page sections.
     */
    public function editHome()
    {
        $sections = PageSection::where('page', 'home')->get()->keyBy('section_key');

        return Inertia::render('Admin/Pages/home', [
            'sections' => $sections,
        ]);
    }

    /**
     * Update home page sections.
     */
    public function updateHome(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.content' => 'required',
            'sections.*.title' => 'nullable|string',
        ]);

        // Process each section
        foreach ($validated['sections'] as $sectionKey => $sectionData) {
            $content = $sectionData['content'];

            // Handle image uploads in hero_slider section
            if ($sectionKey === 'hero_slider' && isset($content['slides'])) {
                foreach ($content['slides'] as $index => $slide) {
                    // Check if this slide has an image field
                    if (isset($slide['image'])) {
                        $fieldName = "sections.hero_slider.content.slides.{$index}.image";

                        // Check if a file was uploaded for this slide
                        if ($request->hasFile($fieldName)) {
                            $imageFile = $request->file($fieldName);

                            // Validate the image file
                            if ($imageFile->isValid()) {
                                // Create uploads directory if it doesn't exist
                                $uploadsPath = public_path('uploads/hero-slider');
                                if (! file_exists($uploadsPath)) {
                                    mkdir($uploadsPath, 0755, true);
                                }

                                // Generate unique filename
                                $filename = time().'_'.$index.'_'.$imageFile->getClientOriginalName();

                                // Move file to public uploads directory
                                $imageFile->move($uploadsPath, $filename);

                                // Store the public URL
                                $content['slides'][$index]['image'] = '/uploads/hero-slider/'.$filename;
                            }
                        }
                        // If image is already a URL string, keep it as is
                    }
                }
            }

            // Handle image uploads in best_sellers section
            if ($sectionKey === 'best_sellers' && isset($content['products'])) {
                foreach ($content['products'] as $index => $product) {
                    if (isset($product['image'])) {
                        $fieldName = "sections.best_sellers.content.products.{$index}.image";

                        if ($request->hasFile($fieldName)) {
                            $imageFile = $request->file($fieldName);

                            if ($imageFile->isValid()) {
                                $uploadsPath = public_path('uploads/best-sellers');
                                if (! file_exists($uploadsPath)) {
                                    mkdir($uploadsPath, 0755, true);
                                }

                                $filename = time().'_'.$index.'_'.$imageFile->getClientOriginalName();
                                $imageFile->move($uploadsPath, $filename);

                                $content['products'][$index]['image'] = '/uploads/best-sellers/'.$filename;
                            }
                        }
                    }
                }
            }

            // Handle image uploads in trust_section
            if ($sectionKey === 'trust_section' && isset($content['brands'])) {
                foreach ($content['brands'] as $index => $brand) {
                    if (isset($brand['logo'])) {
                        $fieldName = "sections.trust_section.content.brands.{$index}.logo";

                        if ($request->hasFile($fieldName)) {
                            $imageFile = $request->file($fieldName);

                            if ($imageFile->isValid()) {
                                $uploadsPath = public_path('uploads/brands');
                                if (! file_exists($uploadsPath)) {
                                    mkdir($uploadsPath, 0755, true);
                                }

                                $filename = time().'_'.$index.'_'.$imageFile->getClientOriginalName();
                                $imageFile->move($uploadsPath, $filename);

                                $content['brands'][$index]['logo'] = '/uploads/brands/'.$filename;
                            }
                        }
                    }
                }
            }

            // Handle image uploads in testimonials section
            if ($sectionKey === 'testimonials' && isset($content['items'])) {
                foreach ($content['items'] as $index => $item) {
                    if (isset($item['avatar_image'])) {
                        $fieldName = "sections.testimonials.content.items.{$index}.avatar_image";

                        if ($request->hasFile($fieldName)) {
                            $imageFile = $request->file($fieldName);

                            if ($imageFile->isValid()) {
                                $uploadsPath = public_path('uploads/testimonials');
                                if (! file_exists($uploadsPath)) {
                                    mkdir($uploadsPath, 0755, true);
                                }

                                $filename = time().'_'.$index.'_'.$imageFile->getClientOriginalName();
                                $imageFile->move($uploadsPath, $filename);

                                $content['items'][$index]['avatar_image'] = '/uploads/testimonials/'.$filename;
                            }
                        }
                    }
                }
            }

            // Handle background image upload in offer_banner section
            if ($sectionKey === 'offer_banner' && isset($content['background_image'])) {
                $fieldName = 'sections.offer_banner.content.background_image';

                if ($request->hasFile($fieldName)) {
                    $imageFile = $request->file($fieldName);

                    if ($imageFile->isValid()) {
                        $uploadsPath = public_path('uploads/banners');
                        if (! file_exists($uploadsPath)) {
                            mkdir($uploadsPath, 0755, true);
                        }

                        $filename = time().'_offer_banner_'.$imageFile->getClientOriginalName();
                        $imageFile->move($uploadsPath, $filename);

                        $content['background_image'] = '/uploads/banners/'.$filename;
                    }
                }
            }

            // Save the section with processed content
            PageSection::setSection('home', $sectionKey, $content, $sectionData['title'] ?? null);
        }

        // Fetch updated sections to return with proper URLs
        $updatedSections = PageSection::where('page', 'home')->get()->keyBy('section_key');

        return back()->with([
            'success' => 'Home page updated successfully!',
            'sections' => $updatedSections,
        ]);
    }

    /**
     * Edit header sections.
     */
    public function editHeader()
    {
        $section = PageSection::where('page', 'header')->where('section_key', 'main')->first();

        return Inertia::render('Admin/Pages/header', [
            'section' => $section,
        ]);
    }

    /**
     * Update header sections.
     */
    public function updateHeader(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|array',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:15360', // 15MB
        ]);

        $content = $validated['content'];

        // Get existing section to preserve logo if not uploading new one
        $existingSection = PageSection::where('page', 'header')
            ->where('section_key', 'main')
            ->first();

        // Handle logo file upload
        if ($request->hasFile('logo')) {
            // Create storage directory if it doesn't exist
            $publicPath = storage_path('app/public');
            if (! file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            $logosPath = storage_path('app/public/logos');
            if (! file_exists($logosPath)) {
                mkdir($logosPath, 0755, true);
            }

            $logoFile = $request->file('logo');
            $logoPath = $logoFile->store('logos', 'public');
            $content['logo'] = '/storage/'.$logoPath;
        } elseif (! isset($content['logo']) || empty($content['logo'])) {
            // If no logo in content and no file uploaded, use existing or default
            if ($existingSection && isset($existingSection->content['logo'])) {
                $content['logo'] = $existingSection->content['logo'];
            } else {
                $content['logo'] = '/logo.png';
            }
        }
        // else: keep the logo from content (it's a URL or existing path)

        $section = PageSection::setSection('header', 'main', $content, 'Header');
        
        // Force update timestamp to trigger cache bust in frontend
        if ($section) {
            $section->touch();
        }

        return back()->with([
            'success' => 'Header updated successfully!',
            'section' => $section,
        ]);
    }

    /**
     * Edit footer sections.
     */
    public function editFooter()
    {
        $section = PageSection::where('page', 'footer')->where('section_key', 'main')->first();

        return view('admin.pages.footer', [
            'section' => $section,
        ]);
    }

    /**
     * Update footer sections.
     */
    public function updateFooter(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|array',
        ]);

        $content = $validated['content'];

        // Handle logo image upload
        if (isset($content['logo'])) {
            $fieldName = 'content.logo';

            if ($request->hasFile($fieldName)) {
                $imageFile = $request->file($fieldName);

                if ($imageFile->isValid()) {
                    // Create uploads directory if it doesn't exist
                    $uploadsPath = public_path('uploads/footer');
                    if (! file_exists($uploadsPath)) {
                        mkdir($uploadsPath, 0755, true);
                    }

                    // Generate unique filename
                    $filename = time().'_logo_'.$imageFile->getClientOriginalName();

                    // Move file to public uploads directory
                    $imageFile->move($uploadsPath, $filename);

                    // Store the public URL
                    $content['logo'] = '/uploads/footer/'.$filename;
                }
            }
            // If logo is already a URL string, keep it as is
        }

        $section = PageSection::setSection('footer', 'main', $content, 'Footer');

        return back()->with([
            'success' => 'Footer updated successfully!',
            'section' => $section,
        ]);
    }

    /**
     * Edit About Us page.
     */
    public function editAbout()
    {
        $sections = PageSection::forPage('about')->get()->keyBy('section_key');

        return Inertia::render('Admin/Pages/About', [
            'sections' => $sections,
        ]);
    }

    /**
     * Update About Us page.
     */
    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
        ]);

        foreach ($validated['sections'] as $sectionKey => $sectionData) {
            PageSection::setSection('about', $sectionKey, $sectionData);
        }

        return back()->with('success', 'About Us page updated successfully!');
    }

    /**
     * Edit Contact Us page.
     */
    public function editContact()
    {
        $sections = PageSection::forPage('contact')->get()->keyBy('section_key');

        return Inertia::render('Admin/Pages/Contact', [
            'sections' => $sections,
        ]);
    }

    /**
     * Update Contact Us page.
     */
    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
        ]);

        foreach ($validated['sections'] as $sectionKey => $sectionData) {
            PageSection::setSection('contact', $sectionKey, $sectionData);
        }

        return back()->with('success', 'Contact Us page updated successfully!');
    }

    /**
     * Edit FAQ page.
     */
    public function editFAQ()
    {
        $sections = PageSection::forPage('faq')->get()->keyBy('section_key');

        return Inertia::render('Admin/Pages/FAQ', [
            'sections' => $sections,
        ]);
    }

    /**
     * Update FAQ page.
     */
    public function updateFAQ(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
        ]);

        foreach ($validated['sections'] as $sectionKey => $sectionData) {
            PageSection::setSection('faq', $sectionKey, $sectionData);
        }

        return back()->with('success', 'FAQ page updated successfully!');
    }

    /**
     * Edit Terms & Conditions page.
     */
    public function editTerms()
    {
        $sections = PageSection::forPage('terms')->get()->keyBy('section_key');

        return Inertia::render('Admin/Pages/Terms', [
            'sections' => $sections,
        ]);
    }

    /**
     * Update Terms & Conditions page.
     */
    public function updateTerms(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
        ]);

        foreach ($validated['sections'] as $sectionKey => $sectionData) {
            PageSection::setSection('terms', $sectionKey, $sectionData);
        }

        return back()->with('success', 'Terms & Conditions updated successfully!');
    }

    /**
     * Edit Privacy Policy page.
     */
    public function editPrivacy()
    {
        $sections = PageSection::forPage('privacy')->get()->keyBy('section_key');

        return Inertia::render('Admin/Pages/Privacy', [
            'sections' => $sections,
        ]);
    }

    /**
     * Update Privacy Policy page.
     */
    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
        ]);

        foreach ($validated['sections'] as $sectionKey => $sectionData) {
            PageSection::setSection('privacy', $sectionKey, $sectionData);
        }

        return back()->with('success', 'Privacy Policy updated successfully!');
    }

    /**
     * Edit category page.
     */
    public function editCategory($slug)
    {
        $section = PageSection::where('page', 'category')->where('section_key', $slug)->first();

        $categoryNames = [
            'books' => 'Books',
            'magazines' => 'Magazines',
            'catalogs' => 'Catalogs',
            'brochures' => 'Marketing Material',
            'business-cards' => 'Business Cards',
            'postcards-invitations' => 'Invitation & Stationery',
            'banners' => 'Banners',
            'promotional-items' => 'Promotional Items',
            'stickers' => 'Stickers',
            'booklets' => 'Booklets',
            'stationery' => 'Stationery',
        ];

        // Get defaults
        $defaults = PageSection::getDefaults($slug);

        // Merge database content with defaults if section exists
        if ($section) {
            $content = array_replace_recursive($defaults, $section->content);

            // If products array is empty in database, use defaults
            if (empty($section->content['products'] ?? null)) {
                $content['products'] = $defaults['products'] ?? [];
            } else {
                $content['products'] = $section->content['products'];
            }

            $sectionData = [
                'content' => $content,
                'title' => $section->title ?? $categoryNames[$slug] ?? ucfirst($slug),
            ];
        } else {
            $sectionData = [
                'content' => $defaults,
                'title' => $categoryNames[$slug] ?? ucfirst($slug),
            ];
        }

        return Inertia::render('Admin/Pages/category', [
            'section' => $sectionData,
            'slug' => $slug,
            'categoryName' => $categoryNames[$slug] ?? ucfirst($slug),
        ]);
    }

    /**
     * Update category page.
     */
    public function updateCategory(Request $request, $slug)
    {
        $validated = $request->validate([
            'content' => 'required|array',
            'title' => 'nullable|string',
        ]);

        PageSection::setSection('category', $slug, $validated['content'], $validated['title'] ?? null);

        return back()->with('success', 'Category page updated successfully!');
    }

    /**
     * Edit category product detail page.
     */
    public function editCategoryProduct($categorySlug, $productSlug)
    {
        $section = PageSection::where('page', 'category_product')
            ->where('section_key', $categorySlug.'_'.$productSlug)
            ->first();

        $categoryNames = [
            'books' => 'Books',
            'magazines' => 'Magazines',
            'catalogs' => 'Catalogs',
            'brochures' => 'Marketing Material',
            'business-cards' => 'Business Cards',
            'postcards-invitations' => 'Invitation & Stationery',
            'banners' => 'Banners',
            'promotional-items' => 'Promotional Items',
            'stickers' => 'Stickers',
            'booklets' => 'Booklets',
            'stationery' => 'Stationery',
        ];

        // Get product image and details from category page defaults
        $categorySection = PageSection::where('page', 'category')
            ->where('section_key', $categorySlug)
            ->first();

        $defaultImage = '';
        $defaultTitle = ucwords(str_replace('-', ' ', $productSlug));
        $defaultPrice = '';

        // First try to get from database category section
        $categoryProducts = [];
        if ($categorySection && ! empty($categorySection->content['products'])) {
            $categoryProducts = $categorySection->content['products'];
        }

        // If no products in database, get from defaults
        if (empty($categoryProducts)) {
            $defaults = PageSection::getDefaults($categorySlug);
            $categoryProducts = $defaults['products'] ?? [];
        }

        // Find this product in the category products
        foreach ($categoryProducts as $product) {
            $url = $product['url'] ?? '';
            // Match if URL ends with product slug, is exactly product slug, or contains slug as last segment
            // e.g. /books/paperback, paperback, or http://.../paperback
            $segments = explode('/', trim($url, '/'));
            $lastSegment = end($segments);

            if ($lastSegment === $productSlug) {
                $defaultImage = $product['img'] ?? '';
                $defaultTitle = $product['title'] ?? $defaultTitle;
                $defaultPrice = $product['price'] ?? '';
                break;
            }
        }

        // Default content structure for product detail pages
        $defaultContent = [
            'title' => $defaultTitle,
            'subtitle' => '',
            'description' => '',
            'hero_image' => $defaultImage,
            'base_price' => 0,
            'min_quantity' => 1,
            'min_pages' => 1,
            'max_pages' => 500,
            'specifications' => [
                ['label' => 'Binding', 'value' => 'Perfect Bound'],
                ['label' => 'Paper Quality', 'value' => '80gsm'],
            ],
            'pricing_tiers' => [
                ['min_qty' => 1, 'max_qty' => 50, 'price' => 0],
                ['min_qty' => 51, 'max_qty' => 100, 'price' => 0],
            ],
            'config_options' => [
                'sizes' => [
                    ['label' => 'A4', 'price' => 0],
                    ['label' => 'A5', 'price' => 0],
                    ['label' => 'Custom', 'price' => 0],
                ],
                'paper_types' => [
                    ['label' => '80gsm', 'price' => 0],
                    ['label' => '100gsm', 'price' => 2],
                    ['label' => '120gsm', 'price' => 4],
                ],
                'finishes' => [
                    ['label' => 'Matte', 'price' => 0],
                    ['label' => 'Glossy', 'price' => 5],
                ],
                'bindings' => [
                    ['label' => 'Perfect Bound', 'price' => 10],
                    ['label' => 'Saddle Stitch', 'price' => 5],
                ],
                'orientations' => [
                    ['label' => 'Portrait', 'price' => 0],
                    ['label' => 'Landscape', 'price' => 0],
                ],
                'cover_papers' => [],
                'coatings' => [],
            ],
        ];

        // Merge section content with defaults, preserving defaults for empty values
        if ($section) {
            $content = array_replace_recursive($defaultContent, $section->content);
            // Ensure hero_image uses default if section has empty value
            if (empty($content['hero_image']) && ! empty($defaultImage)) {
                $content['hero_image'] = $defaultImage;
            }
        } else {
            $content = $defaultContent;
        }

        return Inertia::render('Admin/Pages/CategoryProductEdit', [
            'section' => [
                'content' => $content,
                'title' => $section->title ?? $defaultTitle,
            ],
            'categorySlug' => $categorySlug,
            'productSlug' => $productSlug,
            'categoryName' => $categoryNames[$categorySlug] ?? ucfirst($categorySlug),
            'productName' => $defaultTitle,
            'defaultImage' => $defaultImage, // Pass default image separately for AdminImageInput
        ]);
    }

    /**
     * Update category product detail page.
     */
    public function updateCategoryProduct(Request $request, $categorySlug, $productSlug)
    {
        $validated = $request->validate([
            'content' => 'required|array',
            'title' => 'nullable|string',
        ]);

        $content = $validated['content'];

        // Handle hero_image upload if it's a file
        if ($request->hasFile('content.hero_image')) {
            $image = $request->file('content.hero_image');
            $path = $image->store('products', 'public');
            $content['hero_image'] = '/storage/'.$path;
        }

        PageSection::setSection(
            'category_product',
            $categorySlug.'_'.$productSlug,
            $content,
            $validated['title'] ?? null
        );

        return back()->with('success', 'Product detail page updated successfully!');
    }
}
