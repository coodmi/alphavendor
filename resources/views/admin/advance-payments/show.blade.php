@extends('layouts.dashboard')

@section('title', 'Advance Payment Details')
@section('page-title', 'Advance Payment #' . $advancePayment->id)

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.advance-payments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Information -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    Payment Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Payment ID</label>
                        <p class="font-semibold text-gray-800">#{{ $advancePayment->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <p><span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $advancePayment->status_badge }}">
                            {{ ucfirst($advancePayment->status) }}
                        </span></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Total Amount</label>
                        <p class="font-semibold text-gray-800"> {{ currency($advancePayment->total_amount) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Advance Amount ({{ $advancePayment->advance_percentage }}%)</label>
                        <p class="font-semibold text-green-600"> {{ currency($advancePayment->advance_amount) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Remaining Amount</label>
                        <p class="font-semibold text-teal-700"> {{ currency($advancePayment->remaining_amount) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Payment Method</label>
                        <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $advancePayment->payment_method)) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Contact Number</label>
                        <p class="font-semibold text-gray-800">{{ $advancePayment->contact_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Transaction ID</label>
                        <p class="font-semibold text-gray-800">{{ $advancePayment->transaction_id ?? 'N/A' }}</p>
                    </div>
                    @if($advancePayment->payment_method === 'bank_transfer')
                    <div class="col-span-2 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm font-bold text-blue-700 mb-3"><i class="fas fa-university mr-1"></i> Sender Bank Details</p>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <label class="text-xs text-gray-500">Bank Name</label>
                                <p class="font-semibold text-gray-800">{{ $advancePayment->bank_name ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Account Holder</label>
                                <p class="font-semibold text-gray-800">{{ $advancePayment->bank_account_holder ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Account Number</label>
                                <p class="font-semibold text-gray-800">{{ $advancePayment->bank_account_number ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-500">Requested Date</label>
                        <p class="font-semibold text-gray-800">{{ $advancePayment->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($advancePayment->approved_at)
                    <div>
                        <label class="text-sm text-gray-500">Approved Date</label>
                        <p class="font-semibold text-gray-800">{{ $advancePayment->approved_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                    @if($advancePayment->paid_at)
                    <div>
                        <label class="text-sm text-gray-500">Paid Date</label>
                        <p class="font-semibold text-gray-800">{{ $advancePayment->paid_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>

                @if($advancePayment->notes)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <label class="text-sm text-gray-500">Customer Notes</label>
                    <p class="text-gray-700 mt-1">{{ $advancePayment->notes }}</p>
                </div>
                @endif

                @if($advancePayment->admin_notes)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <label class="text-sm text-gray-500">Admin Notes</label>
                    <p class="text-gray-700 mt-1">{{ $advancePayment->admin_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-box text-teal-700"></i>
                    Product Details
                </h3>
                <div class="flex gap-4">
                    @if($advancePayment->product->image)
                    <img src="{{ str_starts_with($advancePayment->product->image, 'http') ? $advancePayment->product->image : asset('storage/' . $advancePayment->product->image) }}" 
                         alt="{{ $advancePayment->product->name }}" 
                         class="w-24 h-24 object-cover rounded-lg">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 mb-2">{{ $advancePayment->product->name }}</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Unit Price:</span>
                                <span class="font-semibold text-gray-800"> {{ currency($advancePayment->product->price) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Quantity:</span>
                                <span class="font-semibold text-gray-800">{{ $advancePayment->quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Vendor Details -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-purple-600"></i>
                        Customer
                    </h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-gray-800">{{ $advancePayment->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $advancePayment->user->email }}</p>
                        <p class="text-sm text-gray-600">{{ $advancePayment->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-store text-green-600"></i>
                        Vendor
                    </h3>
                    <div class="space-y-2">
                        <p class="font-semibold text-gray-800">{{ $advancePayment->vendor->name }}</p>
                        <p class="text-sm text-gray-600">{{ $advancePayment->vendor->email }}</p>
                        <p class="text-sm"><span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">{{ ucfirst($advancePayment->vendor->role) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Update Status</h3>
                <form id="updateStatusForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $advancePayment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $advancePayment->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $advancePayment->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="paid" {{ $advancePayment->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="completed" {{ $advancePayment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $advancePayment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                            <input type="text" name="transaction_id" value="{{ $advancePayment->transaction_id }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter transaction ID">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Add notes...">{{ $advancePayment->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <button onclick="window.print()" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                        <i class="fas fa-print"></i> Print Details
                    </button>
                    <form action="{{ route('admin.advance-payments.destroy', $advancePayment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm">
                            <i class="fas fa-trash"></i> Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('updateStatusForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    
    try {
        const response = await fetch('{{ route("admin.advance-payments.update-status", $advancePayment) }}', {
            method: 'PATCH',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'Failed to update status', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalContent;
    }
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-5 right-5 z-50 px-6 py-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white font-semibold`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
