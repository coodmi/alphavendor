@extends('layouts.app')

@section('title', 'About Us - AlphaVendor Multi Vendor Marketplace')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fadeInLeft {
        animation: fadeInLeft 0.8s ease-out forwards;
    }

    .animate-fadeInRight {
        animation: fadeInRight 0.8s ease-out forwards;
    }

    .animate-scaleIn {
        animation: scaleIn 0.6s ease-out forwards;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .delay-100 { animation-delay: 0.1s; opacity: 0; }
    .delay-200 { animation-delay: 0.2s; opacity: 0; }
    .delay-300 { animation-delay: 0.3s; opacity: 0; }
    .delay-400 { animation-delay: 0.4s; opacity: 0; }
    .delay-500 { animation-delay: 0.5s; opacity: 0; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
    <section class="relative bg-[#0d5c63] text-white py-16 sm:py-20 md:py-24 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 bg-[#0a4a50] opacity-30"></div>
        <div class="absolute inset-0">
            <div class="absolute top-10 left-10 w-48 sm:w-64 md:w-72 h-48 sm:h-64 md:h-72 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float"></div>
            <div class="absolute bottom-10 right-10 w-48 sm:w-64 md:w-72 h-48 sm:h-64 md:h-72 bg-[#2e9099] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float" style="animation-delay: 2s;"></div>
        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6 animate-fadeInUp">{{ $content['hero_title'] ?? 'About AlphaVendor' }}</h1>
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 sm:mb-8 animate-fadeInUp delay-100 max-w-3xl mx-auto px-4">{{ $content['hero_subtitle'] ?? 'Your trusted partner in global commerce, connecting businesses worldwide' }}</p>
                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 animate-fadeInUp delay-200 px-4">
                    @if(!empty($content['hero_cta_primary_text']))
                    <a href="{{ $content['hero_cta_primary_link'] ?? route('shop') }}" class="bg-white text-[#0d5c63] px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">{{ $content['hero_cta_primary_text'] }}</a>
                    @endif
                    @if(!empty($content['hero_cta_secondary_text']))
                    <a href="{{ $content['hero_cta_secondary_link'] ?? '#contact' }}" class="border-2 border-white text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-[#0d5c63] transition-all duration-300 transform hover:scale-105">{{ $content['hero_cta_secondary_text'] }}</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 lg:gap-16 items-center">
                <div class="animate-fadeInLeft bg-white p-8 rounded-2xl shadow-lg h-full flex flex-col justify-center">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">{{ $content['story_title'] ?? 'Our Story' }}</h2>
                    <p class="text-white text-base sm:text-lg mb-5 leading-relaxed">
                        {{ $content['story_paragraph_1'] ?? 'Founded with a vision to revolutionize B2B commerce, AlphaVendor has grown into a leading multi-vendor marketplace serving thousands of businesses worldwide.' }}
                    </p>
                    <p class="text-white text-base sm:text-lg mb-5 leading-relaxed">
                        {{ $content['story_paragraph_2'] ?? 'We believe in creating seamless connections between retailers, wholesalers, and exporters, making global trade accessible and efficient for businesses of all sizes.' }}
                    </p>
                    <p class="text-white text-base sm:text-lg leading-relaxed">
                        {{ $content['story_paragraph_3'] ?? 'Our platform is built on trust, transparency, and innovation, empowering vendors to reach new markets and buyers to discover quality products from verified suppliers.' }}
                    </p>
                </div>
                <div class="animate-fadeInRight mt-8 md:mt-0">
                    <div class="bg-gradient-to-br from-[#0d5c63] to-[#2e9099] p-8 rounded-2xl shadow-lg h-full flex items-center justify-center">
                        <img src="{{ asset('About logo.png') }}" alt="Our Story" class="w-full h-auto rounded-xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 bg-[#0d5c63] text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8 text-center">
                <div class="animate-scaleIn delay-100">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2">{{ $content['stats_vendors'] ?? '10K+' }}</div>
                    <div class="text-sm sm:text-base md:text-lg opacity-90">Active Vendors</div>
                </div>
                <div class="animate-scaleIn delay-200">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2">{{ $content['stats_products'] ?? '50K+' }}</div>
                    <div class="text-sm sm:text-base md:text-lg opacity-90">Products</div>
                </div>
                <div class="animate-scaleIn delay-300">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2">{{ $content['stats_customers'] ?? '100K+' }}</div>
                    <div class="text-sm sm:text-base md:text-lg opacity-90">Happy Customers</div>
                </div>
                <div class="animate-scaleIn delay-400">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-bold mb-2">{{ $content['stats_countries'] ?? '45+' }}</div>
                    <div class="text-sm sm:text-base md:text-lg opacity-90">Countries</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
                <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 animate-fadeInLeft">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#0d5c63] rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-bullseye text-white text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4 sm:mb-6">{{ $content['mission_title'] ?? 'Our Mission' }}</h3>
                    <p class="text-gray-100 text-base sm:text-lg leading-relaxed">
                        {{ $content['mission_content'] ?? 'To empower businesses of all sizes by providing a transparent, efficient, and innovative marketplace that simplifies global trade and fosters meaningful connections between buyers and sellers worldwide.' }}
                    </p>
                </div>
                <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 animate-fadeInRight">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#0d5c63] rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-eye text-white text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4 sm:mb-6">{{ $content['vision_title'] ?? 'Our Vision' }}</h3>
                    <p class="text-gray-100 text-base sm:text-lg leading-relaxed">
                        {{ $content['vision_content'] ?? 'To become the world\'s most trusted and innovative multi-vendor marketplace, where every business finds opportunities to grow, scale, and succeed in the global market.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section id="contact" class="py-16 sm:py-20 bg-[#0d5c63] text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto animate-fadeInUp">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6">{{ $content['cta_title'] ?? 'Ready to Start Your Journey?' }}</h2>
                <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 opacity-90 px-4">{{ $content['cta_subtitle'] ?? 'Join thousands of successful vendors and buyers on AlphaVendor today' }}</p>
                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 px-4">
                    @if(!empty($content['cta_primary_text']))
                    <a href="{{ $content['cta_primary_link'] ?? route('register') }}" class="bg-white text-[#0d5c63] px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        {{ $content['cta_primary_text'] }}
                    </a>
                    @endif
                    @if(!empty($content['cta_secondary_text']))
                    <a href="{{ $content['cta_secondary_link'] ?? route('shop') }}" class="border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg hover:bg-white hover:text-[#0d5c63] transition-all duration-300 transform hover:scale-105">
                        {{ $content['cta_secondary_text'] }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
