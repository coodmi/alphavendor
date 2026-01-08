@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="py-10 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="bg-white py-4 mb-8 rounded-lg">
            <ul class="flex items-center gap-2 text-sm">
                <li class="flex items-center gap-2 text-gray-500">
                    <a href="{{ route('home') }}" class="text-orange-500 hover:underline">Home</a>
                    <span>/</span>
                </li>
                <li class="flex items-center gap-2 text-gray-500">
                    <a href="{{ route('shop') }}" class="text-orange-500 hover:underline">Shop</a>
                    <span>/</span>
                </li>
                <li class="flex items-center gap-2 text-gray-500">
                    <a href="{{ route('shop', ['categories' => [$product->category_id]]) }}" class="text-orange-500 hover:underline">{{ $product->category->name ?? 'Products' }}</a>
                    <span>/</span>
                </li>
                <li class="text-gray-700">{{ $product->name }}</li>
            </ul>
        </nav>

        <!-- Product Detail -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 bg-white p-10 rounded-2xl shadow-md mb-10">
            <!-- Product Gallery -->
            <div class="flex flex-col gap-5">
                <div class="w-full aspect-square rounded-2xl overflow-hidden border-2 border-gray-200 group">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" id="mainImage" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=800&fit=crop" alt="{{ $product->name }}" id="mainImage" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="flex flex-col gap-5">
                @if($product->badge)
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-semibold uppercase w-fit
                        {{ strtolower($product->badge) === 'new' ? 'bg-green-500' : '' }}
                        {{ strtolower($product->badge) === 'sale' ? 'bg-red-500' : '' }}
                        {{ strtolower($product->badge) === 'hot' ? 'bg-orange-500' : '' }}
                        {{ !in_array(strtolower($product->badge), ['new', 'sale', 'hot']) ? 'bg-orange-500' : '' }}
                        text-white">
                        {{ $product->badge }}
                    </span>
                @endif

                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 leading-tight">{{ $product->name }}</h1>

                <div class="flex items-center gap-5 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400 text-base">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($product->rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i < $product->rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-500 text-sm">{{ number_format($product->rating, 1) }} ({{ $product->reviews_count }} reviews)</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <i class="fas fa-store text-orange-500"></i>
                        <span>Sold by: <strong class="text-gray-700">{{ $product->vendor->name ?? 'AlphaVendor' }}</strong></span>
                    </div>
                </div>

                <div class="py-5 border-b border-gray-200">
                    <div class="flex items-center gap-4 mb-3">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Unit Price</div>
                            <span class="text-2xl font-bold text-gray-700" id="unitPrice">${{ number_format($product->price, 2) }}</span>
                        </div>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-xl text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">-{{ $product->discount_percentage }}% OFF</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <div class="text-sm text-gray-500 mb-1">Total Price</div>
                        <span class="text-4xl font-bold text-orange-500" id="totalPrice">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="mt-2.5 text-sm">
                        @if($product->stock > 0)
                            <span class="text-green-600 font-semibold"><i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span class="text-red-600 font-semibold"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </div>
                </div>

                @if($product->description)
                <div class="py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Product Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>
                @endif

                @if($product->stock > 0)
                <div class="flex flex-col gap-4 py-5">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        <div class="flex items-center gap-2.5 mb-4">
                            <label class="font-semibold text-gray-800">Quantity:</label>
                            <div class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" onclick="decreaseQuantity()" class="w-10 h-10 bg-gray-50 hover:bg-orange-500 hover:text-white transition-all duration-300 text-lg">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" readonly class="w-16 h-10 text-center border-none font-semibold text-base">
                                <button type="button" onclick="increaseQuantity({{ $product->stock }})" class="w-10 h-10 bg-gray-50 hover:bg-orange-500 hover:text-white transition-all duration-300 text-lg">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 px-8 py-4 bg-orange-500 text-white rounded-lg text-base font-semibold hover:bg-orange-600 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2.5">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                            <button type="button" class="w-12 h-12 bg-white border-2 border-orange-500 text-orange-500 rounded-lg text-xl hover:bg-orange-500 hover:text-white transition-all duration-300" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="py-5">
                    <button class="w-full px-8 py-4 bg-gray-400 text-white rounded-lg text-base font-semibold cursor-not-allowed flex items-center justify-center gap-2.5" disabled>
                        <i class="fas fa-ban"></i>
                        Out of Stock
                    </button>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4 py-5">
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-tag text-orange-500 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Category</div>
                            <div class="font-semibold text-gray-800">{{ $product->category->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($product->brand)
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-copyright text-orange-500 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Brand</div>
                            <div class="font-semibold text-gray-800">{{ $product->brand->name }}</div>
                        </div>
                    </div>
                    @endif
                    @if($product->sku)
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-barcode text-orange-500 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">SKU</div>
                            <div class="font-semibold text-gray-800">{{ $product->sku }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-truck text-orange-500 text-xl"></i>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500">Shipping</div>
                            <div class="font-semibold text-gray-800">Free Delivery</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="py-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">You May Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('product.show', $relatedProduct->id) }}" class="block">
                        <div class="relative aspect-square overflow-hidden">
                            @if($relatedProduct->image)
                                <img src="{{ str_starts_with($relatedProduct->image, 'http') ? $relatedProduct->image : asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                            @endif
                            @if($relatedProduct->badge)
                            <span class="absolute top-2.5 left-2.5 px-3 py-1 rounded-full text-white text-xs font-semibold
                                {{ strtolower($relatedProduct->badge) === 'new' ? 'bg-green-500' : '' }}
                                {{ strtolower($relatedProduct->badge) === 'sale' ? 'bg-red-500' : '' }}
                                {{ strtolower($relatedProduct->badge) === 'hot' ? 'bg-orange-500' : '' }}
                                {{ !in_array(strtolower($relatedProduct->badge), ['new', 'sale', 'hot']) ? 'bg-orange-500' : '' }}">
                                {{ $relatedProduct->badge }}
                            </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="text-gray-500 text-xs mb-1">{{ $relatedProduct->category->name ?? 'Uncategorized' }}</div>
                            <h4 class="text-base font-semibold text-gray-800 mb-2.5 line-clamp-2">{{ $relatedProduct->name }}</h4>
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl font-bold text-orange-500">${{ number_format($relatedProduct->price, 2) }}</span>
                                @if($relatedProduct->old_price)
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($relatedProduct->old_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<script>
    const unitPrice = {{ $product->price }};

    function updateTotalPrice() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const total = unitPrice * quantity;
        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
    }

    function increaseQuantity(max) {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < max) {
            input.value = currentValue + 1;
            updateTotalPrice();
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updateTotalPrice();
        }
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-5 right-5 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white font-semibold flex items-center gap-3`;
        toast.style.animation = 'slideInRight 0.3s ease-out';

        toast.innerHTML = `
            <i class="fas fa-check-circle text-xl"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        // Fade out after 2.7 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 2700);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Update cart badge
    function updateCartBadge(count) {
        const badge = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement.querySelector('span');
        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else {
                const cartLink = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement;
                const newBadge = document.createElement('span');
                newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #FFA500; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
                newBadge.textContent = count;
                cartLink.appendChild(newBadge);
            }
        }
    }

    // Add to cart with quantity
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);

            if (data.success) {
                // Update cart badge
                updateCartBadge(data.cartCount);

                // Show success toast
                showToast('Product added to cart successfully!');

                // Redirect to cart page after 3 seconds
                setTimeout(() => {
                    window.location.href = '{{ route("cart.index") }}';
                }, 3000);
            } else {
                throw new Error(data.message || 'Failed to add product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            showToast('Failed to add product to cart. Please try again.', 'error');
        });
    });
</script>
@endsection
