@extends('layouts.app')

@section('title', 'Request Return/Refund')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('orders.show', $orderItem->order) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Order
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Request Return/Refund</h2>

            <!-- Product Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    @if($orderItem->product->image)
                        <img src="{{ asset('storage/' . $orderItem->product->image) }}" alt="{{ $orderItem->product->name }}" class="w-20 h-20 rounded object-cover mr-4">
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $orderItem->product->name }}</h3>
                        <p class="text-sm text-gray-600">Order: {{ $orderItem->order->order_number }}</p>
                        <p class="text-sm text-gray-600">Quantity: {{ $orderItem->quantity }}</p>
                        <p class="text-sm font-semibold text-gray-900">Price: {{ currency($orderItem->price, 2) }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('customer.returns.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_item_id" value="{{ $orderItem->id }}">

                <!-- Return Type -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Request Type *</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="type" value="return" required class="mr-3">
                            <div>
                                <div class="font-semibold">Return</div>
                                <div class="text-xs text-gray-500">Send back item</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="type" value="refund" required class="mr-3">
                            <div>
                                <div class="font-semibold">Refund</div>
                                <div class="text-xs text-gray-500">Get money back</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="type" value="exchange" required class="mr-3">
                            <div>
                                <div class="font-semibold">Exchange</div>
                                <div class="text-xs text-gray-500">Replace item</div>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Reason *</label>
                    <select name="reason" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a reason</option>
                        <option value="defective">Defective/Not Working</option>
                        <option value="wrong_item">Wrong Item Received</option>
                        <option value="not_as_described">Not as Described</option>
                        <option value="damaged">Damaged in Shipping</option>
                        <option value="size_issue">Size/Fit Issue</option>
                        <option value="quality_issue">Quality Issue</option>
                        <option value="changed_mind">Changed Mind</option>
                        <option value="other">Other</option>
                    </select>
                    @error('reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason Details -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Detailed Explanation *</label>
                    <textarea name="reason_details" rows="4" required maxlength="1000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Please provide detailed information about the issue..."></textarea>
                    @error('reason_details')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                    <input type="number" name="quantity" min="1" max="{{ $orderItem->quantity }}" value="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Maximum: {{ $orderItem->quantity }}</p>
                    @error('quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Images -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Images (Optional)</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-sm text-gray-500 mt-1">Upload photos showing the issue (max 2MB per image)</p>
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Exchange Product (shown only when exchange is selected) -->
                <div id="exchange-section" class="mb-6 hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Exchange With Product</label>
                    <select name="exchange_product_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a product</option>
                        <!-- This would be populated with similar products -->
                    </select>
                </div>

                <!-- Customer Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea name="customer_notes" rows="3" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Any additional information..."></textarea>
                    @error('customer_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                        Submit Request
                    </button>
                    <a href="{{ route('orders.show', $orderItem->order) }}" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const exchangeSection = document.getElementById('exchange-section');
        if (this.value === 'exchange') {
            exchangeSection.classList.remove('hidden');
            exchangeSection.querySelector('select').required = true;
        } else {
            exchangeSection.classList.add('hidden');
            exchangeSection.querySelector('select').required = false;
        }
    });
});
</script>
@endsection
