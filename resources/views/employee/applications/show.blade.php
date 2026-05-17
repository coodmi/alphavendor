@extends('layouts.dashboard')

@section('title', 'Application Details')
@section('page-title', 'Application Details')

@section('sidebar-menu')
    @include('dashboards.partials.employee-sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Role Application</h2>
            <p class="text-gray-500 mt-1">Submitted {{ $application->created_at->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('employee.applications') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-500 to-blue-500 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($application->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $application->user->name }}</h3>
                        <p class="text-gray-500">{{ $application->user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Current Role</p>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ ucfirst($application->user->role) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Requested Role</p>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $application->requested_role === 'retailer'   ? 'bg-blue-100 text-blue-700'   : '' }}
                            {{ $application->requested_role === 'wholesaler' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $application->requested_role === 'exporter'   ? 'bg-purple-100 text-purple-700' : '' }}">
                            {{ ucfirst($application->requested_role) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Status</p>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $application->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $application->status === 'approved' ? 'bg-green-100 text-green-700'  : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700'      : '' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Applied</p>
                        <p class="text-sm text-gray-700">{{ $application->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                @if($application->message)
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Applicant Message</p>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4">{{ $application->message }}</p>
                </div>
                @endif

                @if($application->rejection_reason)
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Rejection Reason</p>
                    <p class="text-sm text-red-700 bg-red-50 rounded-xl p-4">{{ $application->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($application->status === 'pending')
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Actions</h3>
                <form action="{{ route('employee.applications.approve', $application) }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Approve this application?')"
                        class="w-full px-4 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i> Approve Application
                    </button>
                </form>

                <form action="{{ route('employee.applications.reject', $application) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason (Optional)</label>
                        <textarea name="rejection_reason" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Reason for rejection..."></textarea>
                    </div>
                    <button type="submit"
                        onclick="return confirm('Reject this application?')"
                        class="w-full px-4 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Reject Application
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
