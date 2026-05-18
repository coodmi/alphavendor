@extends('layouts.dashboard')

@section('title', 'Order #' . ($order->order_number ?? $order->id))
@section('page-title', 'Order Details')

@section('sidebar-menu')
    @include('dashboards.partials.employee-sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number ?? $order->id }}</h2>
            <p class="text-gray-500 mt-1">{{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('employee.orders') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Order Items</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <div class="px-6 py-4 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($item->product && $item->product->image)
                                <img src="{{ str_starts_with($item->product->image,'http') ? $item->product->image : asset('storage/'.$item->product->image) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-box text-gray-400"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item->product->name ?? $item->product_name ?? 'Product' }}</p>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-teal-600">{{ currency($item->price * $item->quantity) }}</p>
                            <p class="text-xs text-gray-400">{{ currency($item->price) }} each</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Subtotal</span>
                        <span>{{ currency($order->subtotal ?? $order->total) }}</span>
                    </div>
                    @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Shipping</span>
                        <span>{{ currency($order->shipping_cost) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-200 mt-2">
                        <span>Total</span>
                        <span class="text-teal-600">{{ currency($order->total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Info Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Order Status</h3>
                <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                    {{ $order->status === 'pending'    ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800'    : '' }}
                    {{ $order->status === 'shipped'    ? 'bg-purple-100 text-purple-800': '' }}
                    {{ $order->status === 'delivered'  ? 'bg-green-100 text-green-800'  : '' }}
                    {{ $order->status === 'cancelled'  ? 'bg-red-100 text-red-800'      : '' }}">
                    {{ ucfirst($order->status) }}
                </span>

                @if(auth()->user()->hasPermission('orders.update_status'))
                <form action="{{ route('employee.orders.update-status', $order) }}" method="POST" class="mt-4">
                    @csrf @method('PATCH')
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                    @php
                        $service  = app(\App\Services\WholesaleOrderStatusService::class);
                        $allowed  = $order->isWholesaleOrImport()
                            ? $service->allowedTransitionsFor($order, auth()->user())
                            : ['pending','processing','shipped','delivered','cancelled'];
                    @endphp
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent mb-3"
                        {{ empty($allowed) ? 'disabled' : '' }}>
                        @forelse($allowed as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ \App\Helpers\OrderStatus::label($status) }}
                            </option>
                        @empty
                            <option value="{{ $order->status }}">{{ \App\Helpers\OrderStatus::label($order->status) }} (no transitions available)</option>
                        @endforelse
                    </select>
                    @if(!empty($allowed))
                    <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors">
                        Update Status
                    </button>
                    @endif
                </form>
                @endif
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Customer</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Payment</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Method</span>
                        <span class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium {{ ($order->payment_status ?? '') === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($order->payment_status ?? 'Pending') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
