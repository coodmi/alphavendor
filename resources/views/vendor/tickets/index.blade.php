@extends('layouts.dashboard')

@section('title', 'My Support Tickets')
@section('page-title', 'My Support Tickets')

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
            <a href="{{ route('orders.my-orders') }}" class="menu-item">
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
            <a href="{{ route('vendor.tickets.index') }}" class="menu-item active">
                <i class="fas fa-ticket-alt"></i>
                <span>Support Tickets</span>
            </a>
        </div>
    @endif
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Open</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['open'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-folder-open text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">In Progress</p>
                    <p class="text-2xl font-bold text-teal-700">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-teal-100 p-3 rounded-full">
                    <i class="fas fa-spinner text-teal-700 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">My Support Tickets</h2>
                <a href="{{ route('vendor.tickets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Create New Ticket
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($tickets->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Reply</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-blue-600">{{ $ticket->ticket_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $ticket->subject }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $ticket->category_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $ticket->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $ticket->priority === 'normal' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $ticket->priority === 'high' ? 'bg-teal-100 text-teal-900' : '' }}
                                        {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $ticket->status === 'in_progress' ? 'bg-teal-100 text-teal-800' : '' }}
                                        {{ $ticket->status === 'pending_customer' ? 'bg-teal-100 text-teal-900' : '' }}
                                        {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'No replies' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('vendor.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-ticket-alt text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-600 mb-4">No tickets found</p>
                    <a href="{{ route('vendor.tickets.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Create Your First Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
