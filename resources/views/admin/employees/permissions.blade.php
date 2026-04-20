@extends('layouts.dashboard')

@section('title', 'Employee Permissions')
@section('page-title', 'Employee Permissions')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white">Employee Permissions Management</h2>
        <p class="text-gray-200 mt-1">Manage access permissions for each employee</p>
    </div>

    @if($employees->count() > 0)
        <div class="space-y-4">
            @foreach($employees as $employee)
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-lg">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">{{ $employee->name }}</h3>
                            <p class="text-sm text-gray-200">{{ $employee->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>

                <form onsubmit="updatePermissions(event, {{ $employee->id }})" class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @php
                            $permissions = [
                                'manage_products' => 'Manage Products',
                                'manage_orders' => 'Manage Orders',
                                'manage_users' => 'Manage Users',
                                'manage_categories' => 'Manage Categories',
                                'manage_brands' => 'Manage Brands',
                                'view_analytics' => 'View Analytics',
                                'manage_reviews' => 'Manage Reviews',
                                'manage_payments' => 'Manage Payments',
                                'manage_tickets' => 'Manage Support Tickets',
                            ];
                            $userPermissions = json_decode($employee->permissions ?? '[]', true);
                        @endphp

                        @foreach($permissions as $key => $label)
                        <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                {{ in_array($key, $userPermissions) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                            <span class="text-sm text-white">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="flex justify-end pt-3 border-t border-gray-200">
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Permissions
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-user-shield text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-200 text-lg">No employees found</p>
            <p class="text-gray-400 text-sm mt-1">Add employees first to manage their permissions</p>
            <a href="{{ route('admin.employees.create') }}" class="inline-block mt-4 px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fas fa-plus"></i> Add Employee
            </a>
        </div>
    @endif
</div>

<script>
async function updatePermissions(event, userId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const permissions = formData.getAll('permissions[]');
    
    try {
        const response = await fetch(`/admin/employee-permissions/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ permissions })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Permissions updated successfully!', 'success');
        } else {
            showToast('Failed to update permissions', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection
