@extends('layouts.dashboard')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

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
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $ticket->ticket_number }}</h2>
                        <p class="text-gray-600 mt-1">{{ $ticket->subject }}</p>
                    </div>
                    <a href="{{ route('vendor.tickets.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Back to Tickets
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Status</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $ticket->status === 'in_progress' ? 'bg-teal-100 text-teal-800' : '' }}
                                {{ $ticket->status === 'waiting_response' ? 'bg-teal-100 text-teal-900' : '' }}
                                {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Priority</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $ticket->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $ticket->priority === 'medium' ? 'bg-teal-100 text-teal-800' : '' }}
                                {{ $ticket->priority === 'high' ? 'bg-teal-100 text-teal-900' : '' }}
                                {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Category</label>
                        <p class="text-gray-900 mt-1">{{ ucfirst($ticket->category) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Created</label>
                        <p class="text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($ticket->assignedTo)
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Assigned To</label>
                            <p class="text-gray-900">{{ $ticket->assignedTo->name }}</p>
                        </div>
                    @endif
                </div>

                <!-- Original Message -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Original Message</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Replies -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">Conversation</h3>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                @forelse($ticket->replies as $reply)
                    <div class="flex {{ $reply->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-2xl {{ $reply->is_admin_reply ? 'bg-blue-50' : 'bg-gray-50' }} p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-semibold text-sm">{{ $reply->user->name }}</span>
                                @if($reply->is_admin_reply)
                                    <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">Support Team</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No replies yet. Our support team will respond soon.</p>
                @endforelse
            </div>
        </div>

        <!-- Reply Form -->
        @if($ticket->status !== 'closed')
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">Add Reply</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('vendor.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Type your message here..."></textarea>
                        <div class="mt-4 flex justify-between">
                            <form action="{{ route('vendor.tickets.close', $ticket) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                                        onclick="return confirm('Are you sure you want to close this ticket?')">
                                    Close Ticket
                                </button>
                            </form>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-gray-100 rounded-lg p-6 text-center">
                <i class="fas fa-lock text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">This ticket is closed. No further replies can be added.</p>
            </div>
        @endif
    </div>
</div>
@endsection
