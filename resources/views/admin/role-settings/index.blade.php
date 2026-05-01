@extends('layouts.dashboard')

@section('title', 'Role Settings')
@section('page-title', 'Role Settings')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Role Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Manage system roles and custom employee roles</p>
        </div>
        <button onclick="openAddModal()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold text-sm transition shadow-md">
            <i class="fas fa-plus"></i> Add Employee Role
        </button>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-5">
        <i class="fas fa-check-circle text-green-500"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-5">
        <i class="fas fa-exclamation-circle text-red-500"></i>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-5">
        @foreach($errors->all() as $e)<p class="text-sm">{{ $e }}</p>@endforeach
    </div>
    @endif

    <!-- System Roles -->
    <div class="mb-8">
        <h3 class="text-lg font-bold text-gray-700 mb-1">System Roles</h3>
        <p class="text-sm text-gray-400 mb-4">Built-in roles — cannot be deleted</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($systemRoles as $key => $role)
            @php
                $icons = ['admin'=>'fa-crown','retailer'=>'fa-store','wholesaler'=>'fa-warehouse','exporter'=>'fa-globe','importer'=>'fa-ship','user'=>'fa-user'];
                $icon = $icons[$key] ?? 'fa-user';
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas {{ $icon }} text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold">{{ $role['name'] }}</h4>
                        <p class="text-white/80 text-xs">{{ $role['description'] }}</p>
                    </div>
                </div>
                <div class="px-5 py-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Permissions</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($role['permissions'] as $perm)
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-medium">
                            {{ ucwords(str_replace('_', ' ', $perm)) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                    <span class="text-xs text-gray-400 flex items-center gap-1"><i class="fas fa-lock"></i> System role — read only</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Employee Roles -->
    <div>
        <h3 class="text-lg font-bold text-gray-700 mb-1">Employee Roles</h3>
        <p class="text-sm text-gray-400 mb-4">Custom roles you can create, edit and delete</p>

        @if($employeeRoles->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-gray-200 text-center py-14">
            <i class="fas fa-user-tie text-4xl text-gray-200 mb-3 block"></i>
            <p class="text-gray-400 font-medium">No employee roles yet</p>
            <button onclick="openAddModal()"
                class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold text-sm transition">
                <i class="fas fa-plus"></i> Create First Role
            </button>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($employeeRoles as $er)
            @php
                $lvlColors = ['basic'=>'from-blue-500 to-blue-600','extended'=>'from-purple-500 to-purple-600','full'=>'from-green-500 to-green-600'];
                $lvlColor = $lvlColors[$er->access_level] ?? 'from-gray-500 to-gray-600';
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden {{ !$er->is_active ? 'opacity-60' : '' }}">
                <div class="bg-gradient-to-r {{ $lvlColor }} px-5 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tie text-white text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-white font-bold truncate">{{ $er->name }}</h4>
                        <p class="text-white/80 text-xs">{{ $er->access_level_label }}</p>
                    </div>
                    @if(!$er->is_active)
                    <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">Inactive</span>
                    @endif
                </div>
                <div class="px-5 py-4">
                    @if($er->description)
                    <p class="text-sm text-gray-500 mb-3">{{ $er->description }}</p>
                    @endif
                    @if($er->permissions && count($er->permissions) > 0)
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Permissions</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($er->permissions as $perm)
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs">
                            {{ ucwords(str_replace('_', ' ', $perm)) }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-gray-400 italic">No specific permissions</p>
                    @endif
                </div>
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex gap-2 flex-wrap">
                    <button onclick="openEditModal({{ $er->id }}, '{{ addslashes($er->name) }}', '{{ addslashes($er->description ?? '') }}', '{{ $er->access_level }}', {{ json_encode($er->permissions ?? []) }})"
                        class="flex-1 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition text-center">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <form action="{{ route('admin.employee-roles.toggle', $er) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-1.5 {{ $er->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-{{ $er->is_active ? 'eye-slash' : 'eye' }} mr-1"></i>{{ $er->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.employee-roles.delete', $er) }}" method="POST"
                        onsubmit="return confirm('Delete this role?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="py-1.5 px-3 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- ===== ADD / EDIT MODAL ===== -->
<div id="roleModal" class="fixed inset-0 bg-black/50 z-50 items-center justify-center p-4"
     style="display:none;" onclick="if(event.target===this)closeModal()">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">

        <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="text-white font-bold text-lg">Add Employee Role</h3>
            <button onclick="closeModal()" class="text-white/80 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <form id="roleForm" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="roleName" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-400 outline-none"
                    placeholder="e.g., Sales Manager, Inventory Supervisor">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                <textarea name="description" id="roleDescription" rows="2"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-400 outline-none resize-none"
                    placeholder="Brief description of this role..."></textarea>
            </div>

            <!-- Access Level -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Access Level <span class="text-red-500">*</span></label>
                <select name="access_level" id="accessLevel" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-green-400 outline-none bg-white">
                    <option value="">Select access level</option>
                    <option value="basic">Basic — Standard employee permissions</option>
                    <option value="extended">Extended — Manager level permissions</option>
                    <option value="full">Full — Supervisor level permissions</option>
                </select>
            </div>

            <!-- Permissions -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Permissions</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        'view_dashboard'   => 'View Dashboard',
                        'view_orders'      => 'View Orders',
                        'manage_orders'    => 'Manage Orders',
                        'view_products'    => 'View Products',
                        'manage_products'  => 'Manage Products',
                        'view_reports'     => 'View Reports',
                        'view_analytics'   => 'View Analytics',
                        'manage_team'      => 'Manage Team',
                        'manage_inventory' => 'Manage Inventory',
                        'manage_customers' => 'Manage Customers',
                    ] as $val => $label)
                    <label class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg cursor-pointer hover:bg-green-50 transition text-sm">
                        <input type="checkbox" name="permissions[]" value="{{ $val }}" class="accent-green-600 perm-cb">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold text-sm transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> <span id="submitText">Create Role</span>
                </button>
                <button type="button" onclick="closeModal()"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const modal    = document.getElementById('roleModal');
const form     = document.getElementById('roleForm');
const storeUrl = '{{ route("admin.employee-roles.store") }}';

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Employee Role';
    document.getElementById('submitText').textContent  = 'Create Role';
    document.getElementById('formMethod').value        = 'POST';
    form.action = storeUrl;
    form.reset();
    // Uncheck all
    document.querySelectorAll('.perm-cb').forEach(cb => cb.checked = false);
    modal.style.display = 'flex';
}

function openEditModal(id, name, description, accessLevel, permissions) {
    document.getElementById('modalTitle').textContent  = 'Edit Employee Role';
    document.getElementById('submitText').textContent  = 'Update Role';
    document.getElementById('formMethod').value        = 'PUT';
    form.action = `/admin/employee-roles/${id}`;

    document.getElementById('roleName').value        = name;
    document.getElementById('roleDescription').value = description;
    document.getElementById('accessLevel').value     = accessLevel;

    // Uncheck all first, then check saved ones
    document.querySelectorAll('.perm-cb').forEach(cb => {
        cb.checked = Array.isArray(permissions) && permissions.includes(cb.value);
    });

    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}
</script>
@endsection
