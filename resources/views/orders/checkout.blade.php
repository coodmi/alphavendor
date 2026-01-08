@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Street Address *</label>
                            <textarea name="shipping_address" required class="w-full border rounded px-3 py-2" rows="2"></textarea>
                            @error('shipping_address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">City *</label>
                            <input type="text" name="shipping_city" required class="w-full border rounded px-3 py-2">
                            @error('shipping_city')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">State/Province</label>
                            <input type="text" name="shipping_state" class="w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">ZIP/Postal Code *</label>
                            <input type="text" name="shipping_zip" required class="w-full border rounded px-3 py-2">
                            @error('shipping_zip')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Country *</label>
                            <input type="text" name="shipping_country" required class="w-full border rounded px-3 py-2">
                            @error('shipping_country')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required class="w-full border rounded px-3 py-2">
                            @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Payment Method</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" checked class="mr-3">
                            <span>Cash on Delivery</span>
                        </label>
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                            <span>Bank Transfer</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700 mb-2">Order Notes (Optional)</label>
                        <textarea name="notes" class="w-full border rounded px-3 py-2" rows="3" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    @php $total = 0; @endphp
                    <div class="space-y-4 mb-4 max-h-96 overflow-y-auto">
                        @foreach($cart as $item)
                            @php $total += $item['price'] * $item['quantity']; @endphp
                            <div class="flex gap-3 pb-4 border-b">
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200">
                                    @if($item['image'])
                                        <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2">{{ $item['name'] }}</h3>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Qty: {{ $item['quantity'] }}</span>
                                        <span class="text-gray-600">${{ number_format($item['price'], 2) }} each</span>
                                    </div>
                                    <div class="mt-1 text-right">
                                        <span class="font-bold text-orange-500">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2 mb-6">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span class="font-semibold">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span>Total</span>
                            <span class="text-orange-500">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded hover:bg-orange-600 font-semibold transition-all duration-300 hover:shadow-lg">
                        Place Order
                    </button>

                    <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-gray-600 hover:text-gray-800">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
