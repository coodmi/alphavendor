@extends('layouts.dashboard')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.orders') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Orders
        </a>
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg font-semibold text-sm transition">
            <i class="fas fa-file-invoice"></i> Print / Download Invoice
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Order #{{ $order->order_number }}</h2>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Order Date</p>
                        <p class="font-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-lg mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 pb-4 border-b">
                                <div class="w-20 h-20 flex-shrink-0 rounded overflow-hidden border">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product_name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop"
                                             alt="{{ $item->product_name }}"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Price: ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-orange-500">${{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Customer Information</h3>
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-semibold">Name:</span> {{ $order->user->name ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-semibold">Email:</span> {{ $order->user->email ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-semibold">Phone:</span> {{ $order->phone }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Shipping Address</h3>
                <div class="text-sm space-y-1">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Commission</span>
                        <span class="font-semibold">${{ number_format($order->commission_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Vendor Earning</span>
                        <span class="font-semibold">${{ number_format($order->vendor_earning, 2) }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-orange-500">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Payment</h3>
                <p class="text-sm mb-2"><span class="font-semibold">Method:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
