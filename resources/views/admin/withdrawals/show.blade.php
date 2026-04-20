@extends('layouts.admin')

@section('title', 'Withdrawal Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.withdrawals.index') }}" class="text-teal-700 hover:text-teal-900 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Back to Withdrawals
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Withdrawal Details Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Withdrawal Details</h2>
                        <p class="text-gray-100 mt-1">{{ $withdrawal->withdrawal_number }}</p>
                    </div>
                    @if($withdrawal->status == 'pending')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-teal-100 text-teal-800">Pending</span>
                    @elseif($withdrawal->status == 'approved')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                    @elseif($withdrawal->status == 'completed')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                    @elseif($withdrawal->status == 'rejected')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-100 mb-1">Amount</p>
                        <p class="text-3xl font-bold text-white">{{ currency_symbol() }}{{ number_format($withdrawal->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-100 mb-1">Request Date</p>
                        <p class="text-lg font-semibold text-white">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                @if($withdrawal->notes)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm font-semibold text-white mb-2">Vendor Notes:</p>
                        <p class="text-gray-100">{{ $withdrawal->notes }}</p>
                    </div>
                @endif

                @if($withdrawal->admin_notes)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-semibold text-blue-700 mb-2">Admin Notes:</p>
                        <p class="text-blue-600">{{ $withdrawal->admin_notes }}</p>
                    </div>
                @endif

                @if($withdrawal->approved_at)
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-100 mb-1">Approved At</p>
                            <p class="font-semibold text-white">{{ $withdrawal->approved_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($withdrawal->approver)
                            <div>
                                <p class="text-sm text-gray-100 mb-1">Approved By</p>
                                <p class="font-semibold text-white">{{ $withdrawal->approver->name }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if($withdrawal->completed_at)
                    <div class="mb-6">
                        <p class="text-sm text-gray-100 mb-1">Completed At</p>
                        <p class="font-semibold text-white">{{ $withdrawal->completed_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif
            </div>

            <!-- Payment Method Details -->
            @if($withdrawal->withdrawalMethod)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Payment Method Details</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                            @if($withdrawal->withdrawalMethod->type == 'bank')
                                <i class="fas fa-university text-3xl text-blue-500"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-white">Bank Transfer</p>
                                    <p class="text-sm text-gray-100">{{ $withdrawal->withdrawalMethod->bank_name }}</p>
                                </div>
                            @elseif($withdrawal->withdrawalMethod->type == 'bkash')
                                <i class="fas fa-mobile-alt text-3xl text-pink-500"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-white">bKash</p>
                                    <p class="text-sm text-gray-100">Mobile Wallet</p>
                                </div>
                            @elseif($withdrawal->withdrawalMethod->type == 'nagad')
                                <i class="fas fa-mobile-alt text-3xl text-teal-600"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-white">Nagad</p>
                                    <p class="text-sm text-gray-100">Mobile Wallet</p>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-100 mb-1">Account Name</p>
                                <p class="font-semibold text-white">{{ $withdrawal->withdrawalMethod->account_name }}</p>
                            </div>

                            @if($withdrawal->withdrawalMethod->type == 'bank')
                                <div>
                                    <p class="text-sm text-gray-100 mb-1">Account Number</p>
                                    <p class="font-mono font-semibold text-white">{{ $withdrawal->withdrawalMethod->account_number }}</p>
                                </div>
                                @if($withdrawal->withdrawalMethod->branch_name)
                                    <div>
                                        <p class="text-sm text-gray-100 mb-1">Branch</p>
                                        <p class="font-semibold text-white">{{ $withdrawal->withdrawalMethod->branch_name }}</p>
                                    </div>
                                @endif
                                @if($withdrawal->withdrawalMethod->routing_number)
                                    <div>
                                        <p class="text-sm text-gray-100 mb-1">Routing Number</p>
                                        <p class="font-mono font-semibold text-white">{{ $withdrawal->withdrawalMethod->routing_number }}</p>
                                    </div>
                                @endif
                            @elseif($withdrawal->withdrawalMethod->type == 'bkash')
                                <div>
                                    <p class="text-sm text-gray-100 mb-1">bKash Number</p>
                                    <p class="font-mono font-semibold text-white">{{ $withdrawal->withdrawalMethod->bkash_number }}</p>
                                </div>
                            @elseif($withdrawal->withdrawalMethod->type == 'nagad')
                                <div>
                                    <p class="text-sm text-gray-100 mb-1">Nagad Number</p>
                                    <p class="font-mono font-semibold text-white">{{ $withdrawal->withdrawalMethod->nagad_number }}</p>
                                </div>
                            @endif
                        </div>

                        @if($withdrawal->withdrawalMethod->additional_details)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-semibold text-white mb-2">Additional Details:</p>
                                <p class="text-gray-100">{{ $withdrawal->withdrawalMethod->additional_details }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            @if($withdrawal->status == 'pending')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Actions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Approve -->
                        <button onclick="openApproveModal()" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 font-semibold">
                            <i class="fas fa-check mr-2"></i>Approve
                        </button>

                        <!-- Complete -->
                        <button onclick="openCompleteModal()" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-semibold">
                            <i class="fas fa-check-double mr-2"></i>Complete
                        </button>

                        <!-- Reject -->
                        <button onclick="openRejectModal()" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 font-semibold">
                            <i class="fas fa-times mr-2"></i>Reject
                        </button>
                    </div>
                </div>
            @elseif($withdrawal->status == 'approved')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Actions</h3>
                    
                    <button onclick="openCompleteModal()" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 font-semibold">
                        <i class="fas fa-check-double mr-2"></i>Mark as Completed
                    </button>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Vendor Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-white mb-4">Vendor Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-100">Name</p>
                        <p class="font-semibold text-white">{{ $withdrawal->vendor->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-100">Email</p>
                        <p class="font-semibold text-white">{{ $withdrawal->vendor->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-100">Role</p>
                        <p class="font-semibold text-white">{{ ucfirst($withdrawal->vendor->role) }}</p>
                    </div>
                    @if($withdrawal->vendor->mobile_number)
                        <div>
                            <p class="text-sm text-gray-100">Mobile</p>
                            <p class="font-semibold text-white">{{ $withdrawal->vendor->mobile_number }}</p>
                        </div>
                    @endif
                </div>

                <a href="{{ route('admin.vendors.show', $withdrawal->vendor->id) }}" 
                   class="block mt-4 text-center bg-gray-100 text-white px-4 py-2 rounded-lg hover:bg-gray-200 font-semibold">
                    View Vendor Profile
                </a>
            </div>

            <!-- Wallet Info -->
            @if($wallet)
                <div class="bg-gradient-to-br from-teal-600 to-teal-600 rounded-lg shadow-md p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Wallet Balance</h3>
                    <p class="text-3xl font-bold">{{ currency_symbol() }}{{ number_format($wallet->balance, 2) }}</p>
                    <p class="text-sm opacity-90 mt-2">Available Balance</p>
                </div>
            @endif

            <!-- Recent Withdrawals -->
            @if($recentWithdrawals->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Recent Withdrawals</h3>
                    
                    <div class="space-y-3">
                        @foreach($recentWithdrawals as $recent)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-mono text-xs text-gray-100">{{ $recent->withdrawal_number }}</p>
                                    <p class="font-semibold text-white">{{ currency_symbol() }}{{ number_format($recent->amount, 2) }}</p>
                                </div>
                                @if($recent->status == 'completed')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                @elseif($recent->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">Pending</span>
                                @elseif($recent->status == 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-white mb-4">Approve Withdrawal</h3>
        <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-2">Admin Notes (Optional)</label>
                <textarea name="admin_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Add any notes..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-200 text-white px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold">
                    Approve
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-white mb-4">Complete Withdrawal</h3>
        <form action="{{ route('admin.withdrawals.complete', $withdrawal->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-2">Transaction Reference (Optional)</label>
                <input type="text" name="transaction_reference" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500" placeholder="e.g., TXN123456">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-2">Admin Notes (Optional)</label>
                <textarea name="admin_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500" placeholder="Add any notes..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeCompleteModal()" class="flex-1 bg-gray-200 text-white px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 font-semibold">
                    Complete
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-white mb-4">Reject Withdrawal</h3>
        <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-2">Reason for Rejection <span class="text-red-500">*</span></label>
                <textarea name="admin_notes" rows="4" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500" placeholder="Please provide a reason..."></textarea>
                <p class="text-xs text-gray-200 mt-1">The amount will be refunded to vendor's wallet</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-200 text-white px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 font-semibold">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function openCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals on outside click
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script>
@endsection
