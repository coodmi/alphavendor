@extends('layouts.dashboard')

@section('title', 'Employee Dashboard')
@section('page-title', 'Employee Dashboard')

@section('sidebar-menu')
    @include('dashboards.partials.employee-sidebar')
@endsection

@section('content')
<div class="employee-dashboard">
    <!-- Welcome Section -->
    <div class="welcome-section mb-8">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg p-6 text-white">
            <h1 class="text-3xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-green-100">Manage website content, orders, and user applications as a moderator.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal-100 text-teal-700">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_applications']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                    <a href="{{ route('employee.orders') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders->take(5) as $order)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">Order #{{ $order->order_number ?? $order->id }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->user->name ?? 'Guest' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-teal-600"> {{ currency($order->total) }}</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $order->status === 'pending' ? 'bg-teal-100 text-teal-800' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No orders found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Applications</h3>
                    <a href="{{ route('employee.applications') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentApplications->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="font-medium text-gray-900">{{ $application->user->name }}</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">
                                        {{ ucfirst($application->requested_role) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y') }}</p>
                                <div class="flex gap-2 mt-2">
                                    <form action="{{ route('employee.applications.approve', $application) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                    <a href="{{ route('employee.applications.show', $application) }}" class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No pending applications</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Products -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Products</h3>
                    <a href="{{ route('employee.products') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProducts as $product)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <i class="fas fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ Str::limit($product->name, 30) }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->vendor->name ?? 'Unknown Vendor' }}</p>
                                    <p class="text-xs text-teal-600 font-bold"> {{ currency($product->price) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-box text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No products found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('employee.orders') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl mr-3"></i>
                    <span class="font-medium text-blue-800">Manage Orders</span>
                </a>
                <a href="{{ route('employee.applications') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-user-plus text-green-600 text-xl mr-3"></i>
                    <span class="font-medium text-green-800">Review Applications</span>
                </a>
                <a href="{{ route('employee.products') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="fas fa-box text-purple-600 text-xl mr-3"></i>
                    <span class="font-medium text-purple-800">Manage Products</span>
                </a>
                <a href="{{ route('employee.users') }}" class="flex items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                    <i class="fas fa-users text-teal-700 text-xl mr-3"></i>
                    <span class="font-medium text-teal-800">Manage Users</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.badge {
    background: #e74c3c;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    margin-left: auto;
}
</style>
@endsection