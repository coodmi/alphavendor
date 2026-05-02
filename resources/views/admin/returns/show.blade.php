@extends('layouts.dashboard')

@section('title', 'Return Details')
@section('page-title', 'Return Details')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.returns.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Returns
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Return Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $return->return_number }}</h2>
                        <p class="text-gray-600 mt-1">Requested on {{ $return->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $return->getStatusBadgeClass() }}">
                        {{ ucfirst(str_replace('_', ' ', $return->status)) }}
                    </span>
                </div>

                <!-- Product Info -->
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-lg mb-4">Product Information</h3>
                    <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-lg">
                        @if($return->product->image)
                            <img src="{{ asset('storage/' . $return->product->image) }}" alt="{{ $return->product->name }}" class="w-20 h-20 rounded object-cover">
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $return->product->name }}</h4>
                            <p class="text-sm text-gray-600">Order: {{ $return->order->order_number }}</p>
                            <p class="text-sm text-gray-600">Quantity: {{ $return->quantity }}</p>
                            <p class="text-sm font-semibold text-gray-900">Amount: {{ currency($return->amount) }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $return->getTypeBadgeClass() }}">
                            {{ ucfirst($return->type) }}
                        </span>
                    </div>
                </div>

                <!-- Return Details -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="font-semibold text-lg mb-4">Return Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Reason</p>
                            <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="font-semibold">{{ ucfirst($return->type) }}</p>
                        </div>
                    </div>
                    
                    @if($return->reason_details)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Detailed Explanation</p>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $return->reason_details }}</p>
                        </div>
                    @endif

                    @if($return->customer_notes)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Customer Notes</p>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $return->customer_notes }}</p>
                        </div>
                    @endif

                    @if($return->images && count($return->images) > 0)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Uploaded Images</p>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($return->images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Return proof" class="w-full h-32 object-cover rounded border">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Admin Notes -->
                @if($return->admin_notes)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="font-semibold text-lg mb-2">Admin Notes</h3>
                        <p class="text-gray-900 bg-blue-50 p-3 rounded">{{ $return->admin_notes }}</p>
                    </div>
                @endif

                <!-- Vendor Notes -->
                @if($return->vendor_notes)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="font-semibold text-lg mb-2">Vendor Notes</h3>
                        <p class="text-gray-900 bg-purple-50 p-3 rounded">{{ $return->vendor_notes }}</p>
                    </div>
                @endif

                <!-- Rejection Reason -->
                @if($return->status === 'rejected' && $return->rejection_reason)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="font-semibold text-lg mb-2 text-red-600">Rejection Reason</h3>
                        <p class="text-gray-900 bg-red-50 p-3 rounded">{{ $return->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-4">Customer Information</h3>
                <div class="space-y-2">
                    <p class="text-sm"><span class="text-gray-600">Name:</span> <span class="font-semibold">{{ $return->user->name }}</span></p>
                    <p class="text-sm"><span class="text-gray-600">Email:</span> <span class="font-semibold">{{ $return->user->email }}</span></p>
                    <p class="text-sm"><span class="text-gray-600">Phone:</span> <span class="font-semibold">{{ $return->user->phone ?? 'N/A' }}</span></p>
                </div>
            </div>

            <!-- Vendor Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-4">Vendor Information</h3>
                <div class="space-y-2">
                    <p class="text-sm"><span class="text-gray-600">Name:</span> <span class="font-semibold">{{ $return->vendor->name }}</span></p>
                    <p class="text-sm"><span class="text-gray-600">Email:</span> <span class="font-semibold">{{ $return->vendor->email }}</span></p>
                    <p class="text-sm"><span class="text-gray-600">Role:</span> <span class="font-semibold">{{ ucfirst($return->vendor->role) }}</span></p>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg mb-4">Actions</h3>
                
                @if($return->canBeApproved())
                    <form action="{{ route('admin.returns.approve', $return) }}" method="POST" class="mb-4">
                        @csrf
                        <textarea name="admin_notes" rows="3" placeholder="Add notes (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2"></textarea>
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Approve Return
                        </button>
                    </form>
                @endif

                @if($return->canBeRejected())
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 mb-4">
                        <i class="fas fa-times mr-2"></i>Reject Return
                    </button>
                @endif

                @if(in_array($return->status, ['approved', 'processing', 'shipped_back', 'received']))
                    <form action="{{ route('admin.returns.update-status', $return) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2">
                            <option value="">Update Status</option>
                            <option value="processing">Processing</option>
                            <option value="shipped_back">Shipped Back</option>
                            <option value="received">Received</option>
                            <option value="completed">Completed</option>
                        </select>
                        <textarea name="notes" rows="2" placeholder="Add notes (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-2"></textarea>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update Status
                        </button>
                    </form>
                @endif

                @if($return->canBeRefunded())
                    <button onclick="document.getElementById('refundModal').classList.remove('hidden')" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-money-bill-wave mr-2"></i>Process Refund
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4">Reject Return Request</h3>
        <form action="{{ route('admin.returns.reject', $return) }}" method="POST">
            @csrf
            <textarea name="rejection_reason" rows="4" required placeholder="Enter rejection reason..." class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4"></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4">Process Refund</h3>
        <form action="{{ route('admin.returns.process-refund', $return) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Refund Method</label>
                <select name="refund_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Select method</option>
                    <option value="original_payment">Original Payment Method</option>
                    <option value="wallet">Customer Wallet</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Refund Amount</label>
                <input type="number" name="refund_amount" step="0.01" max="{{ $return->amount }}" value="{{ $return->amount }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction ID (Optional)</label>
                <input type="text" name="refund_transaction_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('refundModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Process Refund
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
