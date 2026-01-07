@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart))
        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-600 mb-6">Your cart is empty</p>
            <a href="{{ route('shop') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded hover:bg-orange-600">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    @foreach($cart as $productId => $item)
                        <div class="flex items-center gap-4 p-4 border-b">
                            <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                 alt="{{ $item['name'] }}"
                                 class="w-24 h-24 object-cover rounded">

                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">{{ $item['name'] }}</h3>
                                <p class="text-gray-600 text-sm">Vendor: {{ $item['vendor_name'] }}</p>
                                <p class="text-orange-500 font-bold mt-2">${{ number_format($item['price'], 2) }}</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <form action="{{ route('cart.update', $productId) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="this.nextElementSibling.stepDown(); this.form.submit();"
                                            class="bg-gray-200 px-3 py-1 rounded-l">-</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                           min="1" class="w-16 text-center border-y border-gray-200 py-1">
                                    <button type="button" onclick="this.previousElementSibling.stepUp(); this.form.submit();"
                                            class="bg-gray-200 px-3 py-1 rounded-r">+</button>
                                </form>

                                <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="text-right font-bold">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-between">
                    <a href="{{ route('shop') }}" class="text-orange-500 hover:text-orange-600">
                        <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-orange-500">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('orders.checkout') }}" class="block w-full bg-orange-500 text-white text-center py-3 rounded hover:bg-orange-600 font-semibold">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
