@extends('layouts.app')

@section('title', $offer->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Offer Header -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex flex-col md:flex-row items-center gap-6">
                @if($offer->image)
                    <div class="flex-shrink-0">
                        <img src="{{ $offer->image_url }}" alt="{{ $offer->name }}" class="w-32 h-32 object-cover rounded-xl shadow-lg">
                    </div>
                @endif
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-extrabold mb-3">{{ $offer->name }}</h1>
                    @if($offer->description)
                        <p class="text-lg opacity-90 mb-4">{{ $offer->description }}</p>
                    @endif
                    @if($offer->badge_text)
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-bold" style="background-color: {{ $offer->badge_color ?? '#0d5c63' }};">
                            {{ $offer->badge_text }}
                        </span>
                    @endif
                    @if($offer->start_date || $offer->end_date)
                        <div class="mt-4 text-sm opacity-90">
                            @if($offer->start_date && $offer->end_date)
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ \Carbon\Carbon::parse($offer->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($offer->end_date)->format('M d, Y') }}
                            @elseif($offer->end_date)
                                <i class="fas fa-clock mr-2"></i>
                                Ends: {{ \Carbon\Carbon::parse($offer->end_date)->format('M d, Y') }}
                            @endif
                        </div>
                    @endif
                </div>
                <div class="text-center">
                    <div class="bg-white text-teal-700 rounded-xl px-6 py-4 shadow-lg">
                        <div class="text-4xl font-bold">{{ $products->total() }}</div>
                        <div class="text-sm font-semibold mt-1">Products</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <a href="{{ route('product.show', $product->slug) }}" class="block">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden bg-gray-100" style="height: 250px;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-6xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    @if($product->badge)
                                        <span class="bg-teal-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                            {{ $product->badge }}
                                        </span>
                                    @endif
                                    @if($offer->badge_text)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-lg" style="background-color: {{ $offer->badge_color ?? '#0d5c63' }}; color: white;">
                                            {{ $offer->badge_text }}
                                        </span>
                                    @endif
                                </div>

                                @if($product->free_shipping)
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                            <i class="fas fa-shipping-fast mr-1"></i>Free Shipping
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-teal-700 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                
                                @if($product->brand)
                                    <p class="text-sm text-gray-500 mb-2">{{ $product->brand->name }}</p>
                                @endif

                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center text-teal-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($product->rating))
                                                <i class="fas fa-star text-xs"></i>
                                            @elseif($i - 0.5 <= $product->rating)
                                                <i class="fas fa-star-half-alt text-xs"></i>
                                            @else
                                                <i class="far fa-star text-xs"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-teal-700">৳{{ number_format($product->price, 2) }}</span>
                                        @if($product->old_price && $product->old_price > $product->price)
                                            <div class="text-sm text-gray-400 line-through">৳{{ number_format($product->old_price, 2) }}</div>
                                        @endif
                                    </div>
                                    @if($product->discount_percentage > 0)
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-bold">
                                            -{{ $product->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>

                                @if($product->stock > 0)
                                    <div class="mt-3 text-sm text-green-600 font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>In Stock
                                    </div>
                                @else
                                    <div class="mt-3 text-sm text-red-600 font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i>Out of Stock
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Products Yet</h3>
                <p class="text-gray-500 mb-6">Products will appear here once they are assigned to this special offer.</p>
                <a href="{{ route('shop') }}" class="inline-block bg-teal-700 hover:bg-teal-800 text-white px-8 py-3 rounded-xl font-bold transition-all">
                    <i class="fas fa-shopping-bag mr-2"></i>Browse All Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
