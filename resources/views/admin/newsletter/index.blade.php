@extends('layouts.dashboard')
@section('title', 'Newsletter Subscribers')
@section('page-title', 'Newsletter Subscribers')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Newsletter Subscribers</h2>
            <p class="text-gray-500 text-sm mt-1">Manage all email subscribers from your site</p>
        </div>
        <a href="{{ route('admin.newsletter.export') }}"
           class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white rounded-lg text-sm font-semibold transition shadow-sm">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>

    @if(session('success'))
    <div class="mb-5 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-600"></i>
        <span class="text-green-800 font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-5 text-white shadow">
            <p class="text-teal-100 text-sm mb-1">Total Subscribers</p>
            <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow">
            <p class="text-green-100 text-sm mb-1">Active</p>
            <p class="text-3xl font-bold">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-red-400 to-red-500 rounded-xl p-5 text-white shadow">
            <p class="text-red-100 text-sm mb-1">Unsubscribed</p>
            <p class="text-3xl font-bold">{{ $stats['unsubscribed'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow">
            <p class="text-purple-100 text-sm mb-1">New Today</p>
            <p class="text-3xl font-bold">{{ $stats['today'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Search Email</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by email..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Status</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active"       {{ request('status') == 'active'       ? 'selected' : '' }}>Active</option>
                    <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-search mr-1"></i> Search
                </button>
                <a href="{{ route('admin.newsletter.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Subscribed At</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($subscribers as $sub)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $sub->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-teal-600 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">{{ $sub->email }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($sub->status === 'active')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Unsubscribed
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->ip_address ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">{{ $sub->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $sub->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Toggle status --}}
                            <button onclick="toggleStatus({{ $sub->id }}, this)"
                                    data-status="{{ $sub->status }}"
                                    title="{{ $sub->status === 'active' ? 'Unsubscribe' : 'Re-activate' }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition
                                           {{ $sub->status === 'active' ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                <i class="fas {{ $sub->status === 'active' ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                            </button>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.newsletter.destroy', $sub) }}"
                                  onsubmit="return confirm('Delete this subscriber?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="fas fa-envelope-open text-5xl text-gray-200 mb-4 block"></i>
                        <p class="text-gray-400 font-medium">No subscribers yet</p>
                        <p class="text-gray-300 text-sm mt-1">Subscribers will appear here once people sign up</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($subscribers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $subscribers->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleStatus(id, btn) {
    fetch(`/admin/newsletter/${id}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) window.location.reload();
    });
}
</script>
@endsection
