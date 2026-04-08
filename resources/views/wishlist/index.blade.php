@extends('layouts.dashboard')

@section('title', 'My Wishlist')
@section('page-title', 'My Wishlist')

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
            <a href="{{ route('wishlist.index') }}" class="menu-item active">
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
    <div class="mb-8">
        <p class="text-gray-600 mt-2">{{ $wishlistCount }} item(s) in your wishlist</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $wishlist)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <a href="{{ route('product.show', $wishlist->product->id) }}">
                            @if($wishlist->product->image)
                                <img src="{{ asset('storage/' . $wishlist->product->image) }}" 
                                     alt="{{ $wishlist->product->name }}" 
                                     class="w-full h-64 object-cover">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                                </div>
                            @endif
                        </a>
                        
                        <!-- Remove from Wishlist Button -->
                        <button onclick="removeFromWishlist({{ $wishlist->product->id }}, this)" 
                                class="absolute top-3 right-3 bg-white rounded-full p-2 shadow-md hover:bg-red-50 transition-colors">
                            <i class="fas fa-heart text-red-500 text-xl"></i>
                        </button>

                        @if($wishlist->product->stock <= 0)
                            <div class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Out of Stock
                            </div>
                        @elseif($wishlist->product->stock < 10)
                            <div class="absolute top-3 left-3 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Low Stock
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <a href="{{ route('product.show', $wishlist->product->id) }}" 
                           class="text-lg font-semibold text-gray-900 hover:text-orange-600 line-clamp-2">
                            {{ $wishlist->product->name }}
                        </a>

                        <div class="mt-2 flex items-center justify-between">
                            <div>
                                @if($wishlist->product->discount_price)
                                    <span class="text-xl font-bold text-orange-600">
                                        ${{ number_format($wishlist->product->discount_price, 2) }}
                                    </span>
                                    <span class="text-sm text-gray-500 line-through ml-2">
                                        ${{ number_format($wishlist->product->price, 2) }}
                                    </span>
                                @else
                                    <span class="text-xl font-bold text-gray-900">
                                        ${{ number_format($wishlist->product->price, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-store"></i>
                            {{ $wishlist->product->vendor->name ?? 'Unknown Vendor' }}
                        </div>

                        <div class="mt-4 flex gap-2">
                            @if($wishlist->product->stock > 0)
                                <form action="{{ route('cart.add', $wishlist->product->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            @else
                                <button disabled 
                                        class="flex-1 bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed font-semibold">
                                    Out of Stock
                                </button>
                            @endif
                        </div>

                        <div class="mt-2 text-xs text-gray-500">
                            Added {{ $wishlist->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-heart text-gray-300 text-8xl mb-6"></i>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Wishlist is Empty</h2>
            <p class="text-gray-600 mb-8">Start adding products you love to your wishlist!</p>
            <a href="{{ route('shop') }}" 
               class="inline-block bg-orange-500 text-white py-3 px-8 rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                <i class="fas fa-shopping-bag"></i> Browse Products
            </a>
        </div>
    @endif
</div>

<script>
function removeFromWishlist(productId, button) {
    if (!confirm('Remove this product from your wishlist?')) {
        return;
    }

    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-gray-400"></i>';

    fetch(`/wishlist/remove/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the card from view
            button.closest('.bg-white').remove();
            
            // Update wishlist count
            const countElement = document.querySelector('.text-gray-600');
            if (countElement) {
                countElement.textContent = `${data.wishlistCount} item(s) in your wishlist`;
            }

            // Show empty state if no items left
            if (data.wishlistCount === 0) {
                location.reload();
            }

            // Show success message
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-heart text-red-500 text-xl"></i>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to remove from wishlist', 'error');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-heart text-red-500 text-xl"></i>';
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
