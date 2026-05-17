@extends('layouts.dashboard')
@section('title', 'Add Employee')
@section('page-title', 'Add New Employee')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <a href="{{ route('admin.employees') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
        <i class="fas fa-arrow-left"></i> Back to Employees
    </a>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <p class="font-semibold mb-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Please fix the errors:</p>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fas fa-user-circle text-blue-500"></i> Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Employee full name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="employee@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="+880 1XXXXXXXXX">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title / Designation</label>
                    <input type="text" name="employee_title" value="{{ old('employee_title') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g. Manager, Supervisor, Support, Accounts">
                    <p class="text-xs text-gray-400 mt-1">Custom title — write anything</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Account Status</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status','active') === 'active' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 text-sm">Active</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" {{ old('status') === 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700 text-sm">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Photo & NID --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fas fa-id-card text-green-500"></i> Photo & NID Card
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Own Photo</label>
                    <input type="file" name="profile_image" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG — max 2MB</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NID Card (Photo)</label>
                    <input type="file" name="nid_card" accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG — max 2MB</p>
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fas fa-lock text-yellow-500"></i> Password
            </h3>
            <p class="text-sm text-gray-500 mb-4">
                <i class="fas fa-info-circle text-blue-400"></i>
                Employee can change their own password later. Admin cannot change it after creation.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 pr-12"
                            placeholder="Minimum 8 characters">
                        <button type="button" onclick="togglePwd('password','icon1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="icon1"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 pr-12"
                            placeholder="Re-enter password">
                        <button type="button" onclick="togglePwd('password_confirmation','icon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="icon2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-purple-500"></i> Permissions
                </h3>
                <div class="flex flex-wrap gap-2">
                    {{-- Role Templates --}}
                    @foreach($roleTemplates as $key => $template)
                    <button type="button" onclick="applyTemplate({{ json_encode($template['permissions']) }})"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $template['color'] }} hover:opacity-80 transition-opacity border border-current/20">
                        {{ $template['label'] }}
                    </button>
                    @endforeach
                    <button type="button" onclick="selectAll(true)"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-800 text-white hover:bg-gray-900 transition-colors">
                        <i class="fas fa-check-double mr-1"></i> Select All
                    </button>
                    <button type="button" onclick="selectAll(false)"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-1"></i> Clear All
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($permissionModules as $moduleKey => $module)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center gap-2 font-semibold text-gray-700">
                            <i class="fas {{ $module['icon'] }} text-blue-500"></i>
                            {{ $module['label'] }}
                        </div>
                        <button type="button" onclick="toggleModule('{{ $moduleKey }}')"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Toggle All
                        </button>
                    </div>
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="module-{{ $moduleKey }}">
                        @foreach($module['permissions'] as $permKey => $permLabel)
                        <label class="flex items-center gap-2.5 cursor-pointer p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
                            <input type="checkbox" name="permissions[]" value="{{ $permKey }}"
                                class="perm-checkbox w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                data-module="{{ $moduleKey }}"
                                {{ in_array($permKey, old('permissions', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $permLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.employees') }}"
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Create Employee
            </button>
        </div>
    </form>
</div>

<script>
function togglePwd(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('fa-eye'); i.classList.toggle('fa-eye-slash');
}

function selectAll(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
}

function toggleModule(moduleKey) {
    const boxes = document.querySelectorAll(`[data-module="${moduleKey}"]`);
    const allChecked = Array.from(boxes).every(cb => cb.checked);
    boxes.forEach(cb => cb.checked = !allChecked);
}

function applyTemplate(permissions) {
    // Clear all first
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
    // Apply template
    permissions.forEach(perm => {
        const cb = document.querySelector(`.perm-checkbox[value="${perm}"]`);
        if (cb) cb.checked = true;
    });
}
</script>
@endsection
