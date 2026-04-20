{{-- 
    Example Category Page with SEO Meta Tags
    This is a reference implementation showing how to use category SEO data
--}}

@extends('layouts.app')

{{-- Page Title (appears in browser tab) --}}
@section('title', $category->meta_title)

{{-- SEO Meta Tags --}}
@section('meta')
    {{-- Basic Meta Tags --}}
    <meta name="description" content="{{ $category->meta_description }}">
    @if($category->meta_keywords)
        <meta name="keywords" content="{{ $category->meta_keywords }}">
    @endif
    
    {{-- Open Graph Tags (for Facebook, LinkedIn, etc.) --}}
    <meta property="og:title" content="{{ $category->meta_title }}">
    <meta property="og:description" content="{{ $category->meta_description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($category->image)
        <meta property="og:image" content="{{ asset('storage/' . $category->image) }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    {{-- Twitter Card Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $category->meta_title }}">
    <meta name="twitter:description" content="{{ $category->meta_description }}">
    @if($category->image)
        <meta name="twitter:image" content="{{ asset('storage/' . $category->image) }}">
    @endif
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Schema.org Structured Data (JSON-LD) --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "{{ $category->meta_title }}",
        "description": "{{ $category->meta_description }}",
        "url": "{{ url()->current() }}",
        @if($category->image)
        "image": "{{ asset('storage/' . $category->image) }}",
        @endif
        "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "{{ url('/') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "{{ $category->name }}",
                    "item": "{{ url()->current() }}"
                }
            ]
        }
    }
    </script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ url('/') }}" class="text-blue-600 hover:underline">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-100">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Category Header --}}
    <div class="mb-8">
        <div class="flex items-start gap-6">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
                     class="w-32 h-32 object-cover rounded-lg shadow-md">
            @endif
            
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-white mb-3">{{ $category->name }}</h1>
                
                @if($category->description)
                    <p class="text-lg text-gray-100 mb-4">{{ $category->description }}</p>
                @endif
                
                <div class="flex items-center gap-4 text-sm text-gray-200">
                    <span>
                        <i class="fas fa-box"></i> 
                        {{ $category->products_count }} Products
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($category->products()->where('status', 'active')->get() as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <a href="{{ route('products.show', $product->slug) }}">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-white mb-2 line-clamp-2">{{ $product->name }}</h3>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-blue-600">৳{{ number_format($product->price, 2) }}</span>
                            
                            @if($product->old_price && $product->old_price > $product->price)
                                <span class="text-sm text-gray-200 line-through">৳{{ number_format($product->old_price, 2) }}</span>
                            @endif
                        </div>
                        
                        @if($product->rating > 0)
                            <div class="flex items-center gap-1 mt-2">
                                <div class="flex text-teal-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $product->rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-200">({{ $product->reviews_count }})</span>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-200 text-lg">No products found in this category.</p>
            </div>
        @endforelse
    </div>

    {{-- SEO Content Section (optional) --}}
    @if($category->meta_description)
        <div class="mt-12 bg-gray-50 rounded-lg p-6">
            <h2 class="text-2xl font-bold text-white mb-4">About {{ $category->name }}</h2>
            <p class="text-white leading-relaxed">{{ $category->meta_description }}</p>
            
            @if($category->meta_keywords)
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-gray-100 mb-2">Related Keywords:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($category->meta_keywords_array as $keyword)
                            <span class="px-3 py-1 bg-white rounded-full text-sm text-white border border-gray-200">
                                {{ $keyword }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
