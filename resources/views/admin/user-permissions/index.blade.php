@extends('layouts.dashboard')

@section('title', 'User Permissions')
@section('page-title', 'User Permissions')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Permissions</h2>
            <p class="text-sm text-gray-500 mt-1">Manage roles and permissions for all users</p>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle text-green-500"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
        <input type="text" id="searchInput" placeholder="Search users by name or email..."
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 outline-none"
            onkeyup="filterUsers()">
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">All Users ({{ $users->total() }})</h3>
            <span class="text-xs text-gray-400">Click "Edit" to manage permissions</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="usersTable">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Permissions</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="font-medium text-gray-800 text-sm">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @php
                                $roleColors = [
                                    'admin'      => 'bg-red-100 text-red-700',
                                    'retailer'   => 'bg-purple-100 text-purple-700',
                                    'wholesaler' => 'bg-green-100 text-green-700',
                                    'exporter'   => 'bg-orange-100 text-orange-700',
                                    'user'       => 'bg-blue-100 text-blue-700',
                                    'employee'   => 'bg-yellow-100 text-yellow-700',
                                ];
                                $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $roleColor }}">
                                {{ ucfirst($user->role === 'exporter' ? 'Importer' : $user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="text-xs text-green-600 font-semibold"><i class="fas fa-shield-alt mr-1"></i>Full Access</span>
                            @elseif(!empty($user->permissions))
                                <span class="text-xs text-indigo-600 font-semibold">
                                    <i class="fas fa-key mr-1"></i>{{ count($user->permissions) }} permission(s)
                                </span>
                            @else
                                <span class="text-xs text-gray-400">No extra permissions</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.user-permissions.edit', $user) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
function filterUsers() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.user-row').forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
    });
}
</script>
@endsection
