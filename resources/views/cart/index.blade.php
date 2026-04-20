@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart))
        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-600 mb-6">Your cart is empty</p>
            <a href="{{ route('shop') }}" class="inline-block bg-teal-600 text-white px-6 py-3 rounded hover:bg-teal-700">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    @foreach($cart as $productId => $item)
                        <div class="flex items-center gap-4 p-4 border-b" data-product-id="{{ $productId }}">
                            <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                 alt="{{ $item['name'] }}"
                                 class="w-24 h-24 object-cover rounded">

                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">{{ $item['name'] }}</h3>
                                <p class="text-gray-600 text-sm">Vendor: {{ $item['vendor_name'] }}</p>
                                <p class="text-teal-600 font-bold mt-2" data-unit-price="{{ $item['price'] }}">${{ number_format($item['price'], 2) }}</p>
                                @if(isset($item['coupon_code']) && isset($item['discount_amount']))
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs rounded">
                                            <i class="fas fa-ticket-alt"></i>
                                            <span class="font-semibold">{{ $item['coupon_code'] }}</span>
                                        </span>
                                        <span class="text-green-600 text-sm font-semibold">-${{ number_format($item['discount_amount'], 2) }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="flex items-center">
                                    <button type="button" onclick="updateQuantity({{ $productId }}, -1)"
                                            class="bg-gray-200 px-3 py-1 rounded-l hover:bg-gray-300 transition">-</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" data-quantity="{{ $productId }}"
                                           min="1" class="w-16 text-center border-y border-gray-200 py-1 font-semibold" readonly>
                                    <button type="button" onclick="updateQuantity({{ $productId }}, 1)"
                                            class="bg-gray-200 px-3 py-1 rounded-r hover:bg-gray-300 transition">+</button>
                                </div>

                                <button type="button" onclick="removeFromCart({{ $productId }})" class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="text-right font-bold" data-item-total="{{ $productId }}">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-between">
                    <a href="{{ route('shop') }}" class="text-teal-600 hover:text-teal-700">
                        <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold" id="subtotal">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-teal-600" id="total">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('orders.checkout') }}" class="block w-full bg-teal-600 text-white text-center py-3 rounded hover:bg-teal-700 font-semibold">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-trash-alt text-red-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Remove Item</h3>
            <p class="text-gray-600 text-center mb-6">Are you sure you want to remove this item from your cart?</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                    Yes, Remove
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let productToDelete = null;

function updateQuantity(productId, change) {
    console.log('updateQuantity called with productId:', productId, 'change:', change);

    const quantityInput = document.querySelector(`input[data-quantity="${productId}"]`);
    console.log('quantityInput:', quantityInput);

    const currentQuantity = parseInt(quantityInput.value);
    const newQuantity = currentQuantity + change;

    console.log('currentQuantity:', currentQuantity, 'newQuantity:', newQuantity);

    if (newQuantity < 1) {
        console.log('Quantity cannot be less than 1');
        return;
    }

    // Update UI immediately for better UX
    quantityInput.value = newQuantity;

    // Send AJAX request
    fetch(`/cart/update/${productId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            updateCartDisplay(productId, newQuantity);
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        // Revert on error
        quantityInput.value = currentQuantity;
        alert('Failed to update cart. Please try again.');
    });
}

function updateCartDisplay(productId, quantity) {
    console.log('updateCartDisplay called with productId:', productId, 'quantity:', quantity);

    const itemRow = document.querySelector(`[data-product-id="${productId}"]`);
    console.log('itemRow:', itemRow);

    const unitPriceElement = itemRow.querySelector('[data-unit-price]');
    console.log('unitPriceElement:', unitPriceElement);

    const unitPrice = parseFloat(unitPriceElement.dataset.unitPrice);
    console.log('unitPrice:', unitPrice);

    const itemTotal = unitPrice * quantity;
    console.log('itemTotal:', itemTotal);

    // Update item total
    const itemTotalElement = itemRow.querySelector(`[data-item-total="${productId}"]`);
    console.log('itemTotalElement:', itemTotalElement);

    itemTotalElement.textContent = '$' + itemTotal.toFixed(2);

    // Calculate new cart total
    let cartTotal = 0;
    document.querySelectorAll('[data-item-total]').forEach(element => {
        const total = parseFloat(element.textContent.replace('$', ''));
        cartTotal += total;
        console.log('Adding to cart total:', total, 'New cart total:', cartTotal);
    });

    console.log('Final cart total:', cartTotal);

    // Update totals
    document.getElementById('subtotal').textContent = '$' + cartTotal.toFixed(2);
    document.getElementById('total').textContent = '$' + cartTotal.toFixed(2);

    console.log('Cart display updated successfully');
}

function removeFromCart(productId) {
    productToDelete = productId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    productToDelete = null;
}

function confirmDelete() {
    if (!productToDelete) return;

    const productId = productToDelete;
    closeDeleteModal();

    fetch(`/cart/remove/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const itemRow = document.querySelector(`[data-product-id="${productId}"]`);
            itemRow.remove();

            // Recalculate totals
            let cartTotal = 0;
            document.querySelectorAll('[data-item-total]').forEach(element => {
                const total = parseFloat(element.textContent.replace('$', ''));
                cartTotal += total;
            });

            document.getElementById('subtotal').textContent = '$' + cartTotal.toFixed(2);
            document.getElementById('total').textContent = '$' + cartTotal.toFixed(2);

            // Reload if cart is empty
            if (document.querySelectorAll('[data-product-id]').length === 0) {
                window.location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to remove item. Please try again.');
    });
}
</script>
@endsection
