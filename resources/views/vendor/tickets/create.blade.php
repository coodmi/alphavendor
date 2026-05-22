@extends('layouts.dashboard')

@section('title', 'Create Support Ticket')
@section('page-title', 'Create Support Ticket')

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
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Create New Support Ticket</h2>
                    <a href="{{ route('vendor.tickets.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Back to Tickets
                    </a>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('vendor.tickets.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
                            <input type="text" id="subject" name="subject" required
                                   value="{{ old('subject') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror"
                                   placeholder="Brief description of your issue">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category (Optional) -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <select id="category" name="category"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a category</option>
                                <option value="general"   {{ old('category') === 'general'   ? 'selected' : '' }}>General Inquiry</option>
                                <option value="technical" {{ old('category') === 'technical' ? 'selected' : '' }}>Technical Issue</option>
                                <option value="billing"   {{ old('category') === 'billing'   ? 'selected' : '' }}>Billing & Payments</option>
                                <option value="product"   {{ old('category') === 'product'   ? 'selected' : '' }}>Product Related</option>
                                <option value="order"     {{ old('category') === 'order'     ? 'selected' : '' }}>Order Issue</option>
                                <option value="other"     {{ old('category') === 'other'     ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">Priority *</label>
                            <select id="priority" name="priority" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                                <option value="">Select priority</option>
                                <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low - General question</option>
                                <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal - Need assistance</option>
                                <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>High - Important issue</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent - Critical problem</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                            <textarea id="description" name="description" rows="6" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Please provide detailed information about your issue...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Please be as detailed as possible to help us resolve your issue quickly.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('vendor.tickets.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
