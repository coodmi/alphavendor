@extends('layouts.dashboard')

@section('title', 'Add Employee')
@section('page-title', 'Add New Employee')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-white rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Employees
        </a>
    </div>

    <!-- Create Employee Form -->
    <div class="bg-white rounded-xl shadow-sm p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-2">Create Employee Account</h2>
            <p class="text-gray-100">Add a new employee and assign their role and permissions</p>
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

        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-white mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="Enter employee's full name">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="employee@example.com">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">
                        Phone Number
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="+880 1XXX-XXXXXX">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                            placeholder="Minimum 8 characters">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-200 hover:text-white">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-white mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                            placeholder="Re-enter password">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-200 hover:text-white">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-white mb-2">
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
                                {{ old('employee_role_id') == $employeeRole->id ? 'selected' : '' }}>
                                {{ $employeeRole->name }} ({{ $employeeRole->access_level_label }})
                            </option>
                        @endforeach
                    </select>
                    <p id="roleDescription" class="mt-2 text-sm text-gray-100 italic"></p>
                    <p class="mt-2 text-sm text-gray-200">
                        <i class="fas fa-info-circle"></i> 
                        Roles can be managed in <a href="{{ route('admin.role-settings') }}" class="text-blue-600 hover:underline">Role Settings</a>
                    </p>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-white mb-2">
                        Account Status
                    </label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-white">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" {{ old('status') == 'inactive' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-white">Inactive</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-white mb-2">
                        Additional Notes (Optional)
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="Any additional information about this employee...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Permissions Preview -->
            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-blue-600"></i>
                    Default Permissions
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                        <span class="text-white">View Dashboard</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                        <span class="text-white">View Orders</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                        <span class="text-white">View Products</span>
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-100">
                    <i class="fas fa-info-circle"></i>
                    You can customize all permissions after creating the account in the Employee Permissions section.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.employees') }}" class="px-6 py-3 bg-gray-100 text-white rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                    <i class="fas fa-user-plus"></i>
                    Create Employee Account
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    // You can add visual feedback here if needed
});

// Trigger description update on page load if there's an old value
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="employee_role_id"]');
    if (roleSelect && roleSelect.value) {
        updateRoleDescription(roleSelect);
    }
});
</script>
@endsection
