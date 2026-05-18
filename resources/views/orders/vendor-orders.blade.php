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
    @endif
@endsection

@section('content')
<div class="bg-white rounded-lg shadow">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Your Earning</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->items->count() }} items
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ currency($order->total) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            {{ currency($order->vendor_earning) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- Status badge --}}
                            @php
                                $statusColor = \App\Helpers\OrderStatus::color($order->status);
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                {{ \App\Helpers\OrderStatus::label($order->status) }}
                            </span>
                            {{-- Show "Mark Shipped" only when order is in Processing (wholesale/import) or pending/processing (retail) --}}
                            @if($order->status === 'processing')
                            <form action="{{ route('vendor.orders.update-status', $order->id) }}" method="POST" class="mt-1.5">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="shipped">
                                <button type="submit"
                                        onclick="return confirm('Mark this order as Shipped?')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition">
                                    <i class="fas fa-shipping-fast"></i> Mark Shipped
                                </button>
                            </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if(auth()->user()->role === 'retailer')
                                <a href="{{ route('retailer.orders.show', $order) }}" class="text-teal-700 hover:text-teal-900 font-medium">View Details</a>
                            @elseif(auth()->user()->role === 'wholesaler')
                                <a href="{{ route('wholesaler.orders.show', $order) }}" class="text-teal-700 hover:text-teal-900 font-medium">View Details</a>
                            @else
                                <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="text-teal-700 hover:text-teal-900 font-medium">Invoice</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No orders yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4">
        {{ $orders->links() }}
    </div>
</div>

@endsection
