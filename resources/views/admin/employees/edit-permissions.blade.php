@extends('layouts.dashboard')
@section('title', 'Edit Permissions – ' . $user->name)
@section('page-title', 'Edit Permissions')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <a href="{{ route('admin.employee-permissions') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
        <i class="fas fa-arrow-left"></i> Back to Permissions
    </a>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Employee Info --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-200">
        @else
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $user->email }}
                @if($user->employee_title)
                    · <span class="text-indigo-600 font-medium">{{ $user->employee_title }}</span>
                @endif
            </p>
        </div>
        <div class="ml-auto">
            <span class="px-3 py-1.5 rounded-xl text-sm font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ ucfirst($user->status) }}
            </span>
        </div>
    </div>

    <form id="permForm" action="{{ route('admin.employee-permissions.update', $user) }}" method="POST">
        @csrf

        {{-- Toolbar --}}
        <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-wrap gap-2 items-center">
            <span class="text-sm font-semibold text-gray-700 mr-2">Quick Apply:</span>
            @foreach($roleTemplates as $key => $template)
            <button type="button" onclick="applyTemplate({{ json_encode($template['permissions']) }})"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ $template['color'] }} hover:opacity-80 transition-opacity border border-current/20">
                {{ $template['label'] }}
            </button>
            @endforeach
            <div class="ml-auto flex gap-2">
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

        {{-- Permission Modules --}}
        <div class="space-y-4">
            @foreach($permissionModules as $moduleKey => $module)
            @php
                $modulePerms = array_keys($module['permissions']);
                $userPerms   = $user->permissions ?? [];
                $allChecked  = count(array_intersect($modulePerms, $userPerms)) === count($modulePerms);
            @endphp
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5 bg-gray-50 border-b border-gray-200">
                    <label class="flex items-center gap-2.5 cursor-pointer font-semibold text-gray-700">
                        <input type="checkbox" class="module-toggle w-4 h-4 text-blue-600 rounded"
                            data-module="{{ $moduleKey }}"
                            {{ $allChecked ? 'checked' : '' }}
                            onchange="toggleModule('{{ $moduleKey }}', this.checked)">
                        <i class="fas {{ $module['icon'] }} text-blue-500"></i>
                        {{ $module['label'] }}
                    </label>
                    <span class="text-xs text-gray-400" id="count-{{ $moduleKey }}">
                        {{ count(array_intersect($modulePerms, $userPerms)) }} / {{ count($modulePerms) }}
                    </span>
                </div>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2" id="module-{{ $moduleKey }}">
                    @foreach($module['permissions'] as $permKey => $permLabel)
                    <label class="flex items-center gap-2.5 cursor-pointer p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
                        <input type="checkbox" name="permissions[]" value="{{ $permKey }}"
                            class="perm-checkbox w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                            data-module="{{ $moduleKey }}"
                            {{ in_array($permKey, $userPerms) ? 'checked' : '' }}
                            onchange="updateModuleCount('{{ $moduleKey }}')">
                        <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $permLabel }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('admin.employees.edit', $user) }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                <i class="fas fa-user-edit"></i> Edit Profile
            </a>
            <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all font-semibold shadow-lg flex items-center gap-2">
                <i class="fas fa-save"></i> Save Permissions
            </button>
        </div>
    </form>
</div>

<script>
function selectAll(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = checked;
        updateModuleCount(cb.dataset.module);
    });
    document.querySelectorAll('.module-toggle').forEach(cb => cb.checked = checked);
}

function toggleModule(moduleKey, checked) {
    document.querySelectorAll(`[data-module="${moduleKey}"].perm-checkbox`).forEach(cb => cb.checked = checked);
    updateModuleCount(moduleKey);
}

function updateModuleCount(moduleKey) {
    const all     = document.querySelectorAll(`[data-module="${moduleKey}"].perm-checkbox`);
    const checked = Array.from(all).filter(cb => cb.checked).length;
    const counter = document.getElementById(`count-${moduleKey}`);
    if (counter) counter.textContent = `${checked} / ${all.length}`;
    // Sync module toggle checkbox
    const toggle = document.querySelector(`.module-toggle[data-module="${moduleKey}"]`);
    if (toggle) toggle.checked = checked === all.length;
}

function applyTemplate(permissions) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = permissions.includes(cb.value);
        updateModuleCount(cb.dataset.module);
    });
}

// Init counts
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.module-toggle').forEach(toggle => {
        updateModuleCount(toggle.dataset.module);
    });
});
</script>
@endsection
