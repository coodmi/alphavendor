@extends('layouts.dashboard')

@section('title', 'Employee Management')
@section('page-title', 'All Employees')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Employee Management</h2>
            <p class="text-gray-200 mt-1">Manage your employees and their access</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all flex items-center gap-2 shadow-lg">
            <i class="fas fa-plus"></i> Add Employee
        </a>
    </div>

    @if($employees->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-100 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $employee->name }}</div>
                                    <div class="text-xs text-gray-200">
                                        @if($employee->employeeRole)
                                            <span class="inline-flex px-2 py-0.5 rounded {{ $employee->employeeRole->access_level_color }} font-medium">
                                                {{ $employee->employeeRole->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded bg-gray-100 text-white font-medium">
                                                {{ ucfirst($employee->role) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white">{{ $employee->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white">{{ $employee->phone ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white text-sm">{{ $employee->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button onclick="deleteEmployee({{ $employee->id }}, '{{ $employee->name }}')" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $employees->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-user-tie text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-200 text-lg">No employees found</p>
            <p class="text-gray-400 text-sm mt-1">Click "Add Employee" to create your first employee</p>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Delete Employee</h3>
            <p class="text-gray-200 mb-6">Are you sure you want to delete "<span id="deleteEmployeeName" class="font-semibold text-white"></span>"? This action cannot be undone.</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 text-white rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function deleteEmployee(id, name) {
    document.getElementById('deleteEmployeeName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/employees/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
