@extends('layouts.dashboard')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('sidebar-menu')
    @include('dashboards.partials.retailer-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('retailer.orders') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Orders
        </a>
        <a href="{{ route('retailer.orders.invoice', $order) }}" target="_blank"
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
                            {{ $order->status === 'pending' ? 'bg-teal-100 text-teal-800' : '' }}
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
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-500">Unit Price: {{ currency($item->price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900"> {{ currency($item->subtotal) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Summary -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium">{{ $order->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">{{ $order->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Shipping Address</h3>
                <p class="text-gray-700">{{ $order->address ?? 'N/A' }}</p>
                @if($order->city || $order->state || $order->zip_code)
                    <p class="text-gray-700 mt-2">
                        {{ $order->city }}{{ $order->state ? ', ' . $order->state : '' }} {{ $order->zip_code }}
                    </p>
                @endif
                @if($order->country)
                    <p class="text-gray-700">{{ $order->country }}</p>
                @endif
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Payment Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="font-medium">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Status</p>
                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium"> {{ currency($order->subtotal) }}</span>
                    </div>
                    @if($order->tax > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium"> {{ currency($order->tax) }}</span>
                        </div>
                    @endif
                    @if($order->shipping_cost > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium"> {{ currency($order->shipping_cost) }}</span>
                        </div>
                    @endif
                    <div class="border-t pt-2 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span> {{ currency($order->total) }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between text-green-600 font-semibold">
                        <span>Your Earning</span>
                        <span>৳{{ number_format($order->vendor_earning, 2) }}</span>
                    </div>
                    @if(($order->commission_amount ?? 0) > 0)
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Platform Commission ({{ $order->commission_rate }}%)</span>
                            <span class="text-red-500">-৳{{ number_format($order->commission_amount, 2) }}</span>
                        </div>
                    @endif
                    @if(($order->cod_commission_amount ?? 0) > 0)
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>COD Commission ({{ $order->cod_commission_rate }}%)</span>
                            <span class="text-red-500">-৳{{ number_format($order->cod_commission_amount, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-4">Order Status</h3>
                @if(in_array($order->status, ['pending', 'processing']))
                <p class="text-sm text-gray-500 mb-4">
                    You can only mark this order as <strong>Shipped</strong> once it has been dispatched.
                </p>
                <form action="{{ route('retailer.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="shipped">
                    <button type="submit"
                            onclick="return confirm('Confirm: Mark this order as Shipped?')"
                            class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-shipping-fast"></i> Mark as Shipped
                    </button>
                </form>
                @else
                <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                    @php
                        $sc = ['shipped'=>'text-purple-600','delivered'=>'text-green-600','cancelled'=>'text-red-600'];
                    @endphp
                    <i class="fas fa-info-circle {{ $sc[$order->status] ?? 'text-gray-400' }}"></i>
                    <span class="text-sm text-gray-600">
                        This order is <strong>{{ ucfirst($order->status) }}</strong>. No further status changes are available for vendors.
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
