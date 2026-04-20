@extends('layouts.app')

@section('title', 'Special Offers')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-orange-50 to-teal-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-teal-700 to-teal-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                    <i class="fas fa-tag mr-3"></i>Special Offers
                </h1>
                <p class="text-xl md:text-2xl opacity-90">
                    Discover amazing deals and exclusive offers
                </p>
            </div>
        </div>
    </div>

    <!-- Offers Grid -->
    <div class="container mx-auto px-4 py-12">
        @if($specialOffers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($specialOffers as $offer)
                    <a href="{{ route('special-offers.show', $offer->slug) }}" class="block group">
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                            <!-- Image -->
                            @if($offer->image)
                                <div class="relative h-56 overflow-hidden">
                                    <img src="{{ $offer->image_url }}" alt="{{ $offer->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    
                                    <!-- Badge -->
                                    @if($offer->badge_text)
                                        <div class="absolute top-4 right-4">
                                            <span class="px-4 py-2 rounded-full text-sm font-bold text-white shadow-lg"
                                                style="background-color: {{ $offer->badge_color ?? '#0d5c63' }}">
                                                {{ $offer->badge_text }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                </div>
                            @endif

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-700 transition-colors">
                                    {{ $offer->name }}
                                </h3>

                                @if($offer->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                        {{ $offer->description }}
                                    </p>
                                @endif

                                <!-- Stats -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-box text-teal-600 mr-2"></i>
                                        <span class="text-sm font-semibold">{{ $offer->products()->count() }} Products</span>
                                    </div>
                                    
                                    <div class="text-teal-700 font-bold group-hover:translate-x-1 transition-transform">
                                        View Offer <i class="fas fa-arrow-right ml-1"></i>
                                    </div>
                                </div>

                                <!-- Date Range -->
                                @if($offer->start_date || $offer->end_date)
                                    <div class="mt-3 text-xs text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        @if($offer->start_date && $offer->end_date)
                                            {{ $offer->start_date->format('M d') }} - {{ $offer->end_date->format('M d, Y') }}
                                        @elseif($offer->end_date)
                                            Ends: {{ $offer->end_date->format('M d, Y') }}
                                        @elseif($offer->start_date)
                                            Starts: {{ $offer->start_date->format('M d, Y') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-block p-8 bg-white rounded-full shadow-lg mb-6">
                    <i class="fas fa-tag text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Special Offers Available</h3>
                <p class="text-gray-500 mb-6">Check back soon for exciting deals and offers!</p>
                <a href="{{ route('shop') }}" class="inline-block bg-teal-700 hover:bg-teal-800 text-white px-8 py-3 rounded-xl font-bold transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>Browse Products
                </a>
            </div>
        @endif
    </div>

    <!-- Call to Action -->
    @if($specialOffers->count() > 0)
        <div class="bg-gradient-to-r from-teal-700 to-teal-600 text-white py-12 mt-12">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Don't Miss Out on These Amazing Deals!</h2>
                <p class="text-xl mb-6 opacity-90">Shop now and save big on your favorite products</p>
                <a href="{{ route('shop') }}" class="inline-block bg-white text-teal-700 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-colors shadow-lg">
                    <i class="fas fa-shopping-cart mr-2"></i>Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
