@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('orders.my-orders') }}" class="text-orange-500 hover:text-orange-600">
            <i class="fas fa-arrow-left mr-2"></i>Back to My Orders
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <span class="inline-block px-4 py-2 rounded text-sm font-semibold
                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h2 class="font-bold text-lg mb-3">Shipping Information</h2>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="font-semibold">{{ $order->user->name }}</p>
                    <p class="text-gray-700 mt-2">{{ $order->shipping_address }}</p>
                    <p class="text-gray-700">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p class="text-gray-700">{{ $order->shipping_country }}</p>
                    <p class="text-gray-700 mt-2">Phone: {{ $order->phone }}</p>
                </div>
            </div>

            <div>
                <h2 class="font-bold text-lg mb-3">Payment Information</h2>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-700">
                        <span class="font-semibold">Payment Method:</span>
                        {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                    </p>
                    <p class="text-gray-700 mt-2">
                        <span class="font-semibold">Payment Status:</span>
                        <span class="inline-block ml-2 px-2 py-1 rounded text-xs
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($order->payment_status === 'unpaid') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </p>
                    <p class="text-gray-700 mt-2">
                        <span class="font-semibold">Vendor:</span> {{ $order->vendor->name }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="font-bold text-lg mb-4">Order Items</h2>
            <div class="border rounded">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
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
                                        <span class="font-medium">{{ $item->product_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($order->status, ['delivered']) && !$item->returns()->whereNotIn('status', ['rejected', 'cancelled'])->exists())
                                        <a href="{{ route('customer.returns.create', ['order_item_id' => $item->id]) }}" 
                                           class="inline-block px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 text-sm">
                                            <i class="fas fa-undo mr-1"></i> Return/Refund
                                        </a>
                                    @elseif($item->returns()->whereNotIn('status', ['rejected', 'cancelled'])->exists())
                                        <span class="text-sm text-gray-500">Return Requested</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end">
            <div class="w-full md:w-1/3">
                <div class="bg-gray-50 p-6 rounded">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">Subtotal:</span>
                        <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">Shipping:</span>
                        <span class="font-semibold">Free</span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between text-lg">
                            <span class="font-bold">Total:</span>
                            <span class="font-bold text-orange-500">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="mt-6 p-4 bg-blue-50 rounded">
                <h3 class="font-semibold mb-2">Order Notes:</h3>
                <p class="text-gray-700">{{ $order->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
