@extends('layouts.dashboard')

@section('title', 'Vendor Application Details')
@section('page-title', 'Vendor Application Details')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.vendor-applications') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Applications
        </a>
    </div>

    <!-- Vendor Info Card -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <p class="text-gray-400 text-sm">{{ $user->mobile_number }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium
                    {{ $user->role === 'retailer' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $user->role === 'wholesaler' ? 'bg-purple-100 text-purple-700' : '' }}
                    {{ $user->role === 'exporter' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $user->role === 'importer' ? 'bg-teal-100 text-teal-700' : '' }}">
                    {{ ucfirst($user->role === 'exporter' ? 'Importer' : $user->role) }}
                </span>
                <div class="mt-2">
                    <span class="inline-flex px-3 py-1 rounded text-xs font-medium
                        {{ $user->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $user->verification_status === 'verified' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $user->verification_status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $user->verification_status === 'unverified' ? 'bg-gray-100 text-gray-700' : '' }}">
                        {{ ucfirst($user->verification_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-gray-200">
            <div>
                <p class="text-sm text-gray-500">Registered</p>
                <p class="font-medium text-gray-800">{{ $user->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Account Status</p>
                <p class="font-medium text-gray-800">{{ ucfirst($user->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Documents Submitted</p>
                <p class="font-medium text-gray-800">{{ $user->verificationDocuments->count() }} files</p>
            </div>
        </div>
    </div>

    <!-- Business Information -->
    @php
        $roleApp = $user->roleApplications->first();
    @endphp
    @if($roleApp)
    <div class="bg-white rounded-xl shadow-sm p-8 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-briefcase text-blue-600"></i>
            Business Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Business Name</label>
                <p class="text-gray-800">{{ $roleApp->business_name ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Business Type</label>
                <p class="text-gray-800">{{ $roleApp->business_type ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Business Phone</label>
                <p class="text-gray-800">{{ $roleApp->business_phone ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Business Email</label>
                <p class="text-gray-800">{{ $roleApp->business_email ?? 'Not provided' }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Business Address</label>
                <p class="text-gray-800">{{ $roleApp->business_address ?? 'Not provided' }}</p>
            </div>
            @if($roleApp->city || $roleApp->state || $roleApp->country)
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Location</label>
                <p class="text-gray-800">
                    {{ collect([$roleApp->city, $roleApp->state, $roleApp->country])->filter()->implode(', ') }}
                </p>
            </div>
            @endif
            @if($roleApp->contact_person)
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Contact Person</label>
                <p class="text-gray-800">{{ $roleApp->contact_person }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Verification Documents -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-file-alt text-green-600"></i>
            Verification Documents
        </h3>

        @if($user->verificationDocuments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($user->verificationDocuments as $document)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-image text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</p>
                                <p class="text-xs text-gray-500">{{ $document->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex px-2 py-1 rounded text-xs font-medium
                            {{ $document->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $document->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $document->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($document->status) }}
                        </span>
                    </div>
                    
                    <!-- Document Preview -->
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $document->file_path) }}" 
                             alt="{{ $document->document_type }}"
                             class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity"
                             onclick="window.open('{{ asset('storage/' . $document->file_path) }}', '_blank')"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden w-full h-32 bg-gray-100 rounded-lg items-center justify-center">
                            <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                        </div>
                    </div>
                    
                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" 
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm">
                        <i class="fas fa-external-link-alt"></i>
                        View Full Document
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No documents uploaded yet</p>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    @if($user->verification_status === 'pending' || ($roleApp && $roleApp->status === 'pending'))
    <div class="bg-white rounded-xl shadow-sm p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Review Application</h3>

        <div class="flex items-center gap-4">
            <form action="{{ route('admin.vendor-applications.approve', $user) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all font-semibold shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Approve Application
                </button>
            </form>

            <button onclick="showRejectModal()" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:from-red-600 hover:to-pink-700 transition-all font-semibold shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-times-circle"></i>
                Reject Application
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times-circle text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Reject Application</h3>
            <p class="text-gray-500">Please provide a reason for rejection</p>
        </div>
        <form action="{{ route('admin.vendor-applications.reject', $user) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason</label>
                <textarea name="reason" rows="4" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                    placeholder="Explain why this application is being rejected..."></textarea>
            </div>
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Reject Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
