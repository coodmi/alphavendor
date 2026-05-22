@extends('layouts.app')

@section('title', 'Return Request Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('customer.returns.index') }}" class="text-teal-600 hover:text-teal-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Returns & Exchanges
            </a>
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

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $return->return_number }}</h1>
                    <p class="text-gray-600 mt-1">Submitted {{ $return->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $return->getStatusBadgeClass() }}">
                    {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Order</p>
                    <p class="font-semibold text-gray-900">{{ $return->order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Request Type</p>
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $return->getTypeBadgeClass() }}">
                        {{ ucfirst($return->type) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Reason</p>
                    <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="font-semibold text-gray-900">{{ currency($return->amount) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Product</h2>
            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                @if($return->product && $return->product->image)
                    <img src="{{ str_starts_with($return->product->image, 'http') ? $return->product->image : asset('storage/' . $return->product->image) }}"
                         alt="{{ $return->product->name }}" class="w-20 h-20 rounded object-cover">
                @endif
                <div>
                    <p class="font-semibold text-gray-900">{{ $return->product->name ?? 'Product' }}</p>
                    <p class="text-sm text-gray-600">Quantity: {{ $return->quantity }}</p>
                </div>
            </div>

            @if($return->type === 'exchange' && $return->exchangeProduct)
                <div class="mt-4 p-4 border border-purple-200 bg-purple-50 rounded-lg">
                    <p class="text-sm font-semibold text-purple-800">Exchange requested with:</p>
                    <p class="text-gray-900">{{ $return->exchangeProduct->name }}</p>
                </div>
            @elseif($return->type === 'exchange')
                <div class="mt-4 p-4 border border-purple-200 bg-purple-50 rounded-lg">
                    <p class="text-sm text-purple-800">Exchange requested — our team will contact you about replacement options.</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Your Message</h2>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $return->reason_details }}</p>
            @if($return->customer_notes)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-1">Additional notes</p>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $return->customer_notes }}</p>
                </div>
            @endif

            @if($return->images && count($return->images) > 0)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-2">Uploaded photos</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($return->images as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $image) }}" alt="Proof" class="w-full h-28 object-cover rounded border">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if(in_array($return->status, ['pending', 'approved']))
            <form action="{{ route('customer.returns.cancel', $return) }}" method="POST" class="bg-white rounded-lg shadow p-6"
                  onsubmit="return confirm('Cancel this return/exchange request?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Cancel Request
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
