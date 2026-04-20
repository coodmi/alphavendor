@extends('layouts.dashboard')

@section('title', 'Vendor Applications')
@section('page-title', 'Vendor Applications')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white">Vendor Applications</h2>
        <p class="text-gray-200 mt-1">Review and manage vendor registration applications and verification documents</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm font-medium">Total Pending</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['total_pending'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Pending Applications</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['pending_applications'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-user-plus text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Pending Verifications</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $stats['pending_verifications'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-file-alt text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Applications Table -->
    @if($applications->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Business Info</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Documents</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Applied</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-100 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($applications as $application)
                    @php
                        $roleApp = $application->roleApplications->first();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($application->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $application->name }}</div>
                                    <div class="text-sm text-gray-200">{{ $application->email }}</div>
                                    <div class="text-xs text-gray-400">{{ $application->mobile_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                                {{ $application->role === 'retailer' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $application->role === 'wholesaler' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $application->role === 'exporter' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $application->role === 'importer' ? 'bg-teal-100 text-teal-700' : '' }}">
                                {{ ucfirst($application->role === 'exporter' ? 'Importer' : $application->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($roleApp)
                                <div class="text-sm">
                                    <div class="font-medium text-white">{{ $roleApp->business_name ?? 'N/A' }}</div>
                                    <div class="text-gray-200">{{ $roleApp->business_type ?? 'N/A' }}</div>
                                    @if($roleApp->business_phone)
                                        <div class="text-xs text-gray-400">{{ $roleApp->business_phone }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">No application data</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-alt text-gray-400"></i>
                                <span class="text-sm font-medium text-white">{{ $application->verification_documents_count }}</span>
                                <span class="text-xs text-gray-200">docs</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <span class="inline-flex px-2 py-1 rounded text-xs font-medium
                                    {{ $application->verification_status === 'pending' ? 'bg-teal-100 text-teal-700' : '' }}
                                    {{ $application->verification_status === 'verified' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $application->verification_status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $application->verification_status === 'unverified' ? 'bg-gray-100 text-white' : '' }}">
                                    {{ ucfirst($application->verification_status) }}
                                </span>
                                @if($roleApp && $roleApp->status === 'pending')
                                    <span class="block text-xs text-teal-700">
                                        <i class="fas fa-clock"></i> Application Pending
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-white">{{ $application->created_at->format('M d, Y') }}</span>
                            <span class="block text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.vendor-applications.show', $application) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <i class="fas fa-eye"></i>
                                Review
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-200 text-lg">No pending vendor applications</p>
            <p class="text-gray-400 text-sm mt-1">New vendor applications will appear here</p>
        </div>
    @endif
</div>
@endsection
