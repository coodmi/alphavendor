@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="bg-green-100 text-green-700 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl"></i>
            </div>

            <h1 class="text-3xl font-bold mb-4">Order Placed Successfully!</h1>
            <p class="text-gray-600 mb-4">Thank you for your order. We've received your order and will process it soon.</p>

            @if(session('payment_pending_verification'))
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 text-left max-w-2xl mx-auto">
                    <div class="flex items-start gap-3">
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-yellow-800">Payment Verification Pending</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                Your payment is being verified. We will confirm your payment within 24 hours.
                                You will receive a notification once your payment is verified.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Details -->
        @if(isset($orders) && $orders->count() > 0)
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <!-- Order Header -->
                    <div class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                                <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">
                                    Payment: 
                                    @if($order->payment_status == 'pending_verification')
                                        <span class="text-yellow-600">Pending Verification</span>
                                    @else
                                        <span class="text-gray-600">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Shipping Address</h3>
                            <p class="text-gray-600 text-sm">
                                {{ $order->user->name }}<br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_zip }}<br>
                                {{ $order->shipping_country }}<br>
                                Phone: {{ $order->phone }}
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Payment Method</h3>
                            <p class="text-gray-600 text-sm">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </p>
                            @if($order->manualPayment)
                                <p class="text-sm text-gray-500 mt-2">
                                    Transaction ID: {{ $order->manualPayment->transaction_id }}<br>
                                    Sender: {{ $order->manualPayment->sender_number }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Order Items</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right text-gray-600">৳{{ number_format($item->price, 2) }}</td>
                                            <td class="px-4 py-3 text-right font-medium text-gray-900">৳{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-4">
                        <div class="flex justify-end">
                            <div class="w-full md:w-1/2">
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-medium">৳{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->delivery_charge > 0)
                                    <div class="flex justify-between py-2">
                                        <span class="text-gray-600">Delivery Charge:</span>
                                        <span class="font-medium">৳{{ number_format($order->delivery_charge, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between py-2 border-t font-bold text-lg">
                                    <span>Total:</span>
                                    <span class="text-teal-700">৳{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-6 pt-4 border-t">
                        <a href="{{ route('orders.show', $order->id) }}" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded text-center hover:bg-teal-700">
                            <i class="fas fa-file-invoice mr-2"></i>View Invoice
                        </a>
                        <button onclick="window.print()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                            <i class="fas fa-print mr-2"></i>Print Invoice
                        </button>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Navigation Buttons -->
        <div class="flex gap-4 justify-center mt-8">
            <a href="{{ route('orders.my-orders') }}" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                <i class="fas fa-list mr-2"></i>View All Orders
            </a>
            <a href="{{ route('shop') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded hover:bg-gray-300">
                <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
            </a>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white, .bg-white * {
            visibility: visible;
        }
        .bg-white {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button, .flex.gap-4 {
            display: none !important;
        }
    }
</style>
@endsection
