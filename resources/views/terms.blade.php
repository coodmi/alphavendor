@extends('layouts.app')

@section('title', $page->title ?? 'Terms & Conditions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-yellow-50 to-amber-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-yellow-500 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block p-4 bg-white/10 rounded-full mb-6">
                    <i class="fas fa-file-contract text-5xl"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                    {{ $page->title ?? 'Terms & Conditions' }}
                </h1>
                <p class="text-xl opacity-90">
                    Last updated: {{ $page && $page->updated_at ? $page->updated_at->format('F d, Y') : date('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                @if($page && $page->content)
                    <div class="prose prose-lg max-w-none text-gray-700">
                        {!! $page->content !!}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-contract text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Content not available. Please check back later.</p>
                    </div>
                @endif

                <!-- Contact Section -->
                <div class="mt-12 p-6 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl border-l-4 border-orange-500">
                    <h3 class="text-xl font-bold text-orange-900 mb-3">
                        <i class="fas fa-envelope mr-2 text-orange-600"></i>Questions About Our Terms?
                    </h3>
                    <p class="text-gray-700 mb-4">
                        If you have any questions about these Terms & Conditions, please contact us:
                    </p>
                    <div class="space-y-2 text-gray-700">
                        <p><i class="fas fa-phone text-orange-600 mr-2"></i> <a href="tel:+8801700000000" class="hover:text-orange-600">+880 1700-000000</a></p>
                        <p><i class="fas fa-envelope text-orange-600 mr-2"></i> <a href="mailto:support@armarketbd.com" class="hover:text-orange-600">support@armarketbd.com</a></p>
                        <p><i class="fas fa-map-marker-alt text-orange-600 mr-2"></i> Dhaka, Bangladesh</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
