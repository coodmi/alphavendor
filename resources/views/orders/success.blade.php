@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-green-100 text-green-700 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-4xl"></i>
        </div>

        <h1 class="text-3xl font-bold mb-4">Order Placed Successfully!</h1>
        <p class="text-gray-600 mb-8">Thank you for your order. We've received your order and will process it soon.</p>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('orders.my') }}" class="bg-orange-500 text-white px-6 py-3 rounded hover:bg-orange-600">
                View My Orders
            </a>
            <a href="{{ route('shop') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded hover:bg-gray-300">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
