@extends('layouts.dashboard')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('sidebar-menu')
    @include('dashboards.partials.employee-sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">All Users</h2>
            <p class="text-gray-500 mt-1">View and manage registered users</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $totalUsers   = $users->total();
            $activeUsers  = $users->where('status', 'active')->count();
            $pendingUsers = $users->where('status', 'pending')->count();
            $blockedUsers = $users->where('status', 'inactive')->count();
        @endphp
        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-users text-2xl"></i></div>
            <div>
                <p class="text-sm text-gray-500">Total Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-user-check text-2xl"></i></div>
            <div>
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($activeUsers) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-clock text-2xl"></i></div>
            <div>
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingUsers) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-user-slash text-2xl"></i></div>
            <div>
                <p class="text-sm text-gray-500">Blocked</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($blockedUsers) }}</p>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Joined</th>
                        @if(auth()->user()->hasAnyPermission(['users.edit','users.block']))
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $user->role === 'retailer'   ? 'bg-blue-100 text-blue-700'   : '' }}
                                {{ $user->role === 'wholesaler' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $user->role === 'exporter'   ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $user->role === 'user'       ? 'bg-gray-100 text-gray-700'   : '' }}
                                {{ $user->role === 'employee'   ? 'bg-teal-100 text-teal-700'   : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $user->status === 'active'   ? 'bg-green-100 text-green-700' : '' }}
                                {{ $user->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $user->status === 'inactive' ? 'bg-red-100 text-red-700'    : '' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        @if(auth()->user()->hasAnyPermission(['users.edit','users.block']))
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(auth()->user()->hasPermission('users.block'))
                                <form action="{{ route('employee.users.update-status', $user) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    @if($user->status === 'active')
                                        <input type="hidden" name="status" value="inactive">
                                        <button type="submit"
                                            onclick="return confirm('Block this user?')"
                                            class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm"
                                            title="Block User">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit"
                                            onclick="return confirm('Unblock this user?')"
                                            class="p-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm"
                                            title="Unblock User">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-users text-4xl mb-3"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
