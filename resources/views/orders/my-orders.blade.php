@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">My Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-600 mb-6">No orders yet</p>
            <a href="{{ route('shop') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded hover:bg-orange-600">
                Start Shopping
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Order {{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p class="text-sm text-gray-600">Vendor: {{ $order->vendor->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded text-sm font-semibold
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="text-lg font-bold text-gray-900 mt-2">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-3">Items:</h4>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product_name }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-medium">{{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold">${{ number_format($item->subtotal, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Shipping Address:</p>
                                <p class="text-sm">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
                                <p class="text-sm">{{ $order->shipping_country }}</p>
                            </div>
                            <a href="{{ route('orders.show', $order->id) }}" class="text-orange-500 hover:text-orange-600 font-semibold">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
