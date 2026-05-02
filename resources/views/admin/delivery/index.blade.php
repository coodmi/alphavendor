@extends('layouts.dashboard')

@section('title', 'Delivery Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Delivery Management</h1>
        <p class="text-gray-600 mt-2">Manage Paperfly delivery orders and track shipments</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if(session('tracking_data'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
            <strong>Tracking Status:</strong> {{ ucfirst(str_replace('_', ' ', session('tracking_data')['status'])) }}
            @if(session('tracking_data')['status_time'])
                <br><strong>Time:</strong> {{ session('tracking_data')['status_time'] }}
            @endif
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Pickup</p>
                    <h3 class="text-2xl font-bold text-teal-700">{{ $stats['pending_pickup'] }}</h3>
                </div>
                <div class="bg-teal-100 p-3 rounded-full">
                    <i class="fas fa-clock text-teal-700 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">In Transit</p>
                    <h3 class="text-2xl font-bold text-blue-600">{{ $stats['in_transit'] }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-truck text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Delivered</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Returned</p>
                    <h3 class="text-2xl font-bold text-red-600">{{ $stats['returned'] }}</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-undo text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.delivery.index') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Order number, tracking..."
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Delivery Status</label>
                <select name="delivery_status" onchange="document.getElementById('filterForm').submit()" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="picked" {{ request('delivery_status') == 'picked' ? 'selected' : '' }}>Picked</option>
                    <option value="in_transit" {{ request('delivery_status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="out_for_delivery" {{ request('delivery_status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="returned" {{ request('delivery_status') == 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Order Status</label>
                <select name="status" onchange="document.getElementById('filterForm').submit()" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">All Orders</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.delivery.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tracking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $order->vendor->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">৳{{ number_format($order->total, 2) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->paperfly_tracking_number)
                                    <div class="text-sm font-mono text-blue-600">{{ $order->paperfly_tracking_number }}</div>
                                @else
                                    <span class="text-gray-400">Not created</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'teal',
                                        'picked' => 'blue',
                                        'in_transit' => 'indigo',
                                        'out_for_delivery' => 'purple',
                                        'delivered' => 'green',
                                        'returned' => 'red',
                                        'cancelled' => 'gray',
                                    ];
                                    $color = $statusColors[$order->delivery_status ?? 'pending'] ?? 'gray';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->delivery_status ?? 'pending')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(!$order->paperfly_tracking_number)
                                    <button type="button"
                                            onclick="createDelivery({{ $order->id }}, this)"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition">
                                        <i class="fas fa-shipping-fast"></i> Create
                                    </button>
                                @else
                                    <form action="{{ route('admin.delivery.track', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                            <i class="fas fa-sync"></i> Track
                                        </button>
                                    </form>
                                    
                                    @if($order->delivery_status != 'delivered' && $order->delivery_status != 'cancelled')
                                        <form action="{{ route('admin.delivery.cancel', $order->id) }}" method="POST" class="inline ml-1" 
                                              onsubmit="return confirm('Are you sure you want to cancel this delivery?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
function createDelivery(orderId, btn) {
    if (!confirm('Create a Paperfly delivery order for this shipment?')) return;

    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/delivery/' + orderId + '/create';
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
