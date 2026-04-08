@extends('layouts.dashboard')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Employees
        </a>
    </div>

    <!-- Edit Employee Form -->
    <div class="bg-white rounded-xl shadow-sm p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Edit Employee Account</h2>
            <p class="text-gray-600">Update employee information and role</p>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.employees.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="Enter employee's full name">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="employee@example.com">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="+880 1XXX-XXXXXX">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        New Password (Leave blank to keep current)
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                            placeholder="Minimum 8 characters">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                            placeholder="Re-enter password">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Employee Role <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_role_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        onchange="updateRoleDescription(this)">
                        <option value="">Select a role</option>
                        @foreach($employeeRoles as $employeeRole)
                            <option value="{{ $employeeRole->id }}" 
                                data-description="{{ $employeeRole->description }}"
                                data-access="{{ $employeeRole->access_level_label }}"
                                {{ old('employee_role_id', $user->employee_role_id) == $employeeRole->id ? 'selected' : '' }}>
                                {{ $employeeRole->name }} ({{ $employeeRole->access_level_label }})
                            </option>
                        @endforeach
                    </select>
                    <p id="roleDescription" class="mt-2 text-sm text-gray-600 italic"></p>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle"></i> 
                        Roles can be managed in <a href="{{ route('admin.role-settings') }}" class="text-blue-600 hover:underline">Role Settings</a>
                    </p>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Account Status
                    </label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status', $user->status) == 'active' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" {{ old('status', $user->status) == 'inactive' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">Inactive</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Additional Notes (Optional)
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="Any additional information about this employee...">{{ old('notes', $user->notes) }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.employees') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function updateRoleDescription(select) {
    const selectedOption = select.options[select.selectedIndex];
    const description = selectedOption.getAttribute('data-description');
    const descriptionElement = document.getElementById('roleDescription');
    
    if (description && description !== 'null') {
        descriptionElement.textContent = description;
        descriptionElement.classList.remove('hidden');
    } else {
        descriptionElement.textContent = '';
        descriptionElement.classList.add('hidden');
    }
}

// Trigger description update on page load
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="employee_role_id"]');
    if (roleSelect && roleSelect.value) {
        updateRoleDescription(roleSelect);
    }
});
</script>
@endsection
