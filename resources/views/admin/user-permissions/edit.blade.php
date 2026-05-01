@extends('layouts.dashboard')

@section('title', 'Edit User Permissions')
@section('page-title', 'Edit User Permissions')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Back -->
    <a href="{{ route('admin.user-permissions') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5 transition">
        <i class="fas fa-arrow-left"></i> Back to User Permissions
    </a>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-5">
        <i class="fas fa-check-circle text-green-500"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-5">
        @foreach($errors->all() as $error)<p class="text-sm">{{ $error }}</p>@endforeach
    </div>
    @endif

    <!-- User Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5 flex items-center gap-4">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">
        @else
            <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-2xl border-2 border-indigo-200">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h3 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h3>
            <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            <span class="inline-block mt-1 px-2.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                Current Role: {{ ucfirst($user->role === 'exporter' ? 'Importer' : $user->role) }}
            </span>
        </div>
    </div>

    <form action="{{ route('admin.user-permissions.update', $user) }}" method="POST">
        @csrf
        {{-- Hidden field ensures empty permissions array is always sent --}}
        <input type="hidden" name="permissions_submitted" value="1">

        <!-- Role Assignment -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-user-tag text-indigo-500"></i> Role Assignment
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($roles as $role)
                @php
                    $roleIcons = [
                        'admin'      => ['icon' => 'fa-shield-alt',  'color' => 'border-red-300 bg-red-50 text-red-700',       'check' => 'accent-red-600'],
                        'retailer'   => ['icon' => 'fa-store',        'color' => 'border-purple-300 bg-purple-50 text-purple-700','check' => 'accent-purple-600'],
                        'wholesaler' => ['icon' => 'fa-warehouse',    'color' => 'border-green-300 bg-green-50 text-green-700',  'check' => 'accent-green-600'],
                        'exporter'   => ['icon' => 'fa-globe',        'color' => 'border-orange-300 bg-orange-50 text-orange-700','check' => 'accent-orange-600'],
                        'user'       => ['icon' => 'fa-user',         'color' => 'border-blue-300 bg-blue-50 text-blue-700',    'check' => 'accent-blue-600'],
                        'employee'   => ['icon' => 'fa-id-badge',     'color' => 'border-yellow-300 bg-yellow-50 text-yellow-700','check' => 'accent-yellow-600'],
                        'importer'   => ['icon' => 'fa-ship',         'color' => 'border-teal-300 bg-teal-50 text-teal-700',    'check' => 'accent-teal-600'],
                    ];
                    $ri = $roleIcons[$role] ?? ['icon' => 'fa-user', 'color' => 'border-gray-300 bg-gray-50 text-gray-700', 'check' => 'accent-gray-600'];
                    $label = $role === 'exporter' ? 'Importer' : ucfirst($role);
                @endphp
                <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition hover:shadow-sm {{ $user->role === $role ? $ri['color'] . ' border-2' : 'border-gray-200 bg-white' }}">
                    <input type="radio" name="role" value="{{ $role }}" {{ $user->role === $role ? 'checked' : '' }}
                        class="{{ $ri['check'] }}" onchange="updateRoleUI()">
                    <div class="flex items-center gap-2">
                        <i class="fas {{ $ri['icon'] }} text-sm"></i>
                        <span class="font-semibold text-sm">{{ $label }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Specific Permissions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-key text-indigo-500"></i> Specific Permissions
                </h4>
                <div class="flex gap-2">
                    <button type="button" onclick="selectAll()" class="text-xs px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition font-semibold">
                        Select All
                    </button>
                    <button type="button" onclick="clearAll()" class="text-xs px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition font-semibold">
                        Clear All
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-400 mb-4">
                <i class="fas fa-info-circle mr-1"></i>
                Admins always have full access regardless of these settings. For other roles, these grant additional capabilities.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($permissions as $key => $label)
                @php
                    $permIcons = [
                        'can_create_products' => 'fa-plus-circle',
                        'can_edit_products'   => 'fa-edit',
                        'can_delete_products' => 'fa-trash',
                        'can_manage_orders'   => 'fa-shopping-bag',
                        'can_view_analytics'  => 'fa-chart-bar',
                        'can_manage_users'    => 'fa-users',
                        'can_access_admin'    => 'fa-cog',
                    ];
                    $icon = $permIcons[$key] ?? 'fa-check';
                    $isChecked = in_array($key, $user->permissions ?? []);
                @endphp
                <label class="flex items-start gap-3 p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition group {{ $isChecked ? 'border-indigo-300 bg-indigo-50' : '' }}">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}"
                        {{ $isChecked ? 'checked' : '' }}
                        class="w-4 h-4 mt-0.5 accent-indigo-600 perm-checkbox">
                    <div>
                        <div class="flex items-center gap-2">
                            <i class="fas {{ $icon }} text-indigo-400 text-sm"></i>
                            <span class="font-semibold text-sm text-gray-700">{{ $label }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">
                            @switch($key)
                                @case('can_create_products') Allows adding new products to the catalog @break
                                @case('can_edit_products') Allows modifying existing product details @break
                                @case('can_delete_products') Allows removing products from the catalog @break
                                @case('can_manage_orders') Allows viewing and updating order statuses @break
                                @case('can_view_analytics') Allows access to sales reports and analytics @break
                                @case('can_manage_users') Allows viewing and editing user accounts @break
                                @case('can_access_admin') Allows access to the admin dashboard @break
                                @default Grants this specific permission @break
                            @endswitch
                        </p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 sm:flex-none px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition flex items-center justify-center gap-2 shadow-md">
                <i class="fas fa-save"></i> Save Permissions
            </button>
            <a href="{{ route('admin.user-permissions') }}"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition flex items-center gap-2">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script>
function selectAll() {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = true;
        cb.closest('label').classList.add('border-indigo-300', 'bg-indigo-50');
        cb.closest('label').classList.remove('border-gray-200');
    });
}

function clearAll() {
    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.checked = false;
        cb.closest('label').classList.remove('border-indigo-300', 'bg-indigo-50');
        cb.closest('label').classList.add('border-gray-200');
    });
}

// Toggle label style on checkbox change
document.querySelectorAll('.perm-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const label = this.closest('label');
        if (this.checked) {
            label.classList.add('border-indigo-300', 'bg-indigo-50');
            label.classList.remove('border-gray-200');
        } else {
            label.classList.remove('border-indigo-300', 'bg-indigo-50');
            label.classList.add('border-gray-200');
        }
    });
});
</script>
@endsection
