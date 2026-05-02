@extends('layouts.dashboard')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('sidebar-menu')
    @php
        $userRole = auth()->user()->role;
    @endphp

    @if($userRole === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter')
        @include('dashboards.partials.exporter-sidebar')
    @elseif($userRole === 'importer')
        @include('dashboards.partials.importer-sidebar')
    @else
        {{-- Regular User Sidebar --}}
        <div class="menu-section">
            <div class="menu-section-title">Main</div>
            <a href="{{ route('user.dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Shopping</div>
            <a href="{{ route('shop') }}" class="menu-item">
                <i class="fas fa-shopping-bag"></i>
                <span>Browse Products</span>
            </a>
            <a href="{{ route('orders.my-orders') }}" class="menu-item active">
                <i class="fas fa-shopping-cart"></i>
                <span>My Orders</span>
            </a>
            <a href="{{ route('wishlist.index') }}" class="menu-item">
                <i class="fas fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="{{ route('customer.returns.index') }}" class="menu-item">
                <i class="fas fa-undo"></i>
                <span>Returns & Refunds</span>
            </a>
            <a href="{{ route('cart.index') }}" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Shopping Cart</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Account</div>
            <a href="{{ route('profile.show') }}" class="menu-item">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>
            <a href="{{ route('vendor.tickets.index') }}" class="menu-item">
                <i class="fas fa-ticket-alt"></i>
                <span>Support Tickets</span>
            </a>
        </div>
    @endif
@endsection

@section('content')
<div style="max-width: 1200px;">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Order Tracking Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-map-marker-alt text-teal-600"></i> Track Your Order
        </h2>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <form action="{{ route('orders.my-orders') }}" method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enter Order Number</label>
                    <input type="text" name="track_order" value="{{ request('track_order') }}" placeholder="e.g., ORD-1234567890-1234" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                </div>
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition whitespace-nowrap">
                    <i class="fas fa-search mr-2"></i>Track Order
                </button>
            </form>
        </div>

        @if(request('track_order'))
            @php
                $trackingOrder = $orders->firstWhere('order_number', request('track_order'));
            @endphp

            @if($trackingOrder)
                <div class="mt-6 p-6 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg text-white">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-bold mb-1">Order #{{ $trackingOrder->order_number }}</h3>
                            <p class="text-sm opacity-90">Placed on {{ $trackingOrder->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                            {{ ucfirst($trackingOrder->status) }}
                        </span>
                    </div>

                    <!-- Order Progress Timeline -->
                    <div class="relative py-8">
                        @php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                            $currentIndex = array_search($trackingOrder->status, $statuses);
                            if ($currentIndex === false) $currentIndex = 0;
                        @endphp

                        <!-- Progress Line -->
                        <div class="absolute top-1/2 left-0 right-0 h-1 bg-white bg-opacity-30 transform -translate-y-1/2"></div>
                        <div class="absolute top-1/2 left-0 h-1 bg-white transform -translate-y-1/2 transition-all duration-500" 
                             style="width: {{ ($currentIndex / (count($statuses) - 1)) * 100 }}%"></div>

                        <!-- Status Points -->
                        <div class="relative flex justify-between">
                            @foreach($statuses as $index => $status)
                                <div class="flex flex-col items-center" style="flex: 1;">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl mb-2 transition-all duration-300
                                        {{ $index <= $currentIndex ? 'bg-white text-purple-600' : 'bg-white bg-opacity-30 text-white' }}">
                                        @if($index < $currentIndex)
                                            <i class="fas fa-check"></i>
                                        @elseif($index == $currentIndex)
                                            <i class="fas fa-circle text-sm"></i>
                                        @else
                                            <i class="fas fa-circle text-xs opacity-50"></i>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold text-center {{ $index <= $currentIndex ? 'opacity-100' : 'opacity-60' }}">
                                        {{ ucfirst($status) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="mt-6 pt-6 border-t border-white border-opacity-20">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs opacity-80 mb-1">Total Amount</p>
                                <p class="text-2xl font-bold"> {{ currency($trackingOrder->total) }}</p>
                            </div>
                            <div>
                                <p class="text-xs opacity-80 mb-1">Items</p>
                                <p class="text-2xl font-bold">{{ $trackingOrder->items->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs opacity-80 mb-1">Payment</p>
                                <p class="text-lg font-semibold">{{ strtoupper($trackingOrder->payment_method) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('orders.show', $trackingOrder->id) }}" 
                           class="inline-block px-6 py-2 bg-white text-purple-600 rounded-lg font-semibold hover:bg-opacity-90 transition">
                            View Full Details <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 text-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Order not found. Please check the order number and try again.
                    </p>
                </div>
            @endif
        @endif
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-600 mb-6">No orders yet</p>
            <a href="{{ route('shop') }}" class="inline-block bg-teal-600 text-white px-6 py-3 rounded hover:bg-teal-700">
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
                            @include('partials.order-status-badge', ['status' => $order->status])
                            
                            @if($order->payment_status === 'pending_verification')
                                <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-teal-100 text-teal-900 ml-2">
                                    <i class="fas fa-clock mr-1"></i> Payment Verifying
                                </span>
                            @elseif($order->payment_status === 'paid')
                                <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-800 ml-2">
                                    <i class="fas fa-check mr-1"></i> Paid
                                </span>
                            @elseif($order->payment_status === 'failed')
                                <span class="inline-block px-3 py-1 rounded text-sm font-semibold bg-red-100 text-red-800 ml-2">
                                    <i class="fas fa-times mr-1"></i> Payment Failed
                                </span>
                            @endif
                            
                            <p class="text-lg font-bold text-gray-900 mt-2"> {{ currency($order->total) }}</p>
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
                                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }} × {{ currency(($item->price, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold"> {{ currency($item->subtotal) }}</p>
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
                            <a href="{{ route('orders.show', $order->id) }}" class="text-teal-600 hover:text-teal-700 font-semibold">
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
