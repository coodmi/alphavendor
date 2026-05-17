@extends('layouts.dashboard')
@section('title', 'Employee Management')
@section('page-title', 'All Employees')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Employee Management</h2>
            <p class="text-gray-500 mt-1">Manage employees, titles, photos and permissions</p>
        </div>
        <a href="{{ route('admin.employees.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold shadow hover:from-blue-700 hover:to-indigo-700 transition-all">
            <i class="fas fa-user-plus"></i> Add Employee
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Employee Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        @if($employees->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($employee->profile_image)
                                    <img src="{{ asset('storage/' . $employee->profile_image) }}"
                                         alt="{{ $employee->name }}"
                                         class="w-11 h-11 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $employee->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $employee->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($employee->employee_title)
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                    {{ $employee->employee_title }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $employee->phone ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php $permCount = count($employee->permissions ?? []); @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg {{ $permCount > 0 ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }} text-xs font-semibold">
                                <i class="fas fa-shield-alt"></i> {{ $permCount }} permissions
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.employee-permissions.edit', $employee) }}"
                                   class="p-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors" title="Manage Permissions">
                                    <i class="fas fa-shield-alt"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $employee) }}"
                                   class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $employee->id }}, '{{ addslashes($employee->name) }}')"
                                        class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $employees->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-user-tie text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-500 text-lg font-medium">No employees yet</p>
            <p class="text-gray-400 text-sm mt-1">Click "Add Employee" to create the first one</p>
        </div>
        @endif
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Employee</h3>
            <p class="text-gray-500">Are you sure you want to delete <strong id="deleteEmployeeName" class="text-gray-700"></strong>? This cannot be undone.</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors font-medium">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteEmployeeName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/employees/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
@endsection
