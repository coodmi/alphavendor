@extends('layouts.dashboard')
@section('title', 'Employee Permissions')
@section('page-title', 'Employee Permissions')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Employee Permissions</h2>
            <p class="text-gray-500 mt-1">View and manage permissions for each employee</p>
        </div>
        <a href="{{ route('admin.employees.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold shadow hover:from-blue-700 hover:to-indigo-700 transition-all text-sm">
            <i class="fas fa-user-plus"></i> Add Employee
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Copy Permissions Tool --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-blue-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-copy text-blue-500"></i> Copy Permissions
            <span class="text-xs font-normal text-gray-500 ml-1">— Copy one employee's permissions to another</span>
        </h3>
        <div class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">From Employee</label>
                <select id="copyFrom" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Select employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ count($emp->permissions ?? []) }} perms)</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center text-gray-400 pb-2.5"><i class="fas fa-arrow-right text-lg"></i></div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">To Employee</label>
                <select id="copyTo" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Select employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <button onclick="copyPermissions()"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm flex items-center gap-2">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
    </div>

    {{-- Employee Permission Cards --}}
    @if($employees->count() > 0)
    <div class="grid grid-cols-1 gap-4">
        @foreach($employees as $employee)
        @php $perms = $employee->permissions ?? []; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    @if($employee->profile_image)
                        <img src="{{ asset('storage/' . $employee->profile_image) }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-semibold text-gray-800">{{ $employee->name }}</div>
                        <div class="text-xs text-gray-500">{{ $employee->email }}
                            @if($employee->employee_title)
                                · <span class="text-indigo-600 font-medium">{{ $employee->employee_title }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ count($perms) > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ count($perms) }} / {{ count(\App\Helpers\EmployeePermission::allKeys()) }} permissions
                    </span>
                    <a href="{{ route('admin.employee-permissions.edit', $employee) }}"
                       class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors text-sm font-semibold flex items-center gap-1.5">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            {{-- Permission summary --}}
            <div class="px-6 py-4">
                @if(count($perms) > 0)
                <div class="flex flex-wrap gap-1.5">
                    @foreach(array_slice($perms, 0, 12) as $perm)
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-medium">{{ $perm }}</span>
                    @endforeach
                    @if(count($perms) > 12)
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-medium">+{{ count($perms) - 12 }} more</span>
                    @endif
                </div>
                @else
                    <p class="text-sm text-gray-400 italic">No permissions assigned yet.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm p-16 text-center">
        <i class="fas fa-user-shield text-6xl text-gray-200 mb-4"></i>
        <p class="text-gray-500 text-lg font-medium">No employees found</p>
        <a href="{{ route('admin.employees.create') }}" class="inline-block mt-4 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold">
            <i class="fas fa-plus mr-2"></i> Add Employee
        </a>
    </div>
    @endif
</div>

<script>
async function copyPermissions() {
    const from = document.getElementById('copyFrom').value;
    const to   = document.getElementById('copyTo').value;
    if (!from || !to) { alert('Please select both employees.'); return; }
    if (from === to)  { alert('Cannot copy to the same employee.'); return; }

    const res = await fetch('{{ route("admin.employee-permissions.copy") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ from_user_id: from, to_user_id: to }),
    });
    const data = await res.json();
    if (data.success) {
        showToast(data.message, 'success');
        setTimeout(() => location.reload(), 1200);
    } else {
        showToast(data.error || 'Failed', 'error');
    }
}

function showToast(msg, type) {
    const t = document.createElement('div');
    t.className = `fixed top-4 right-4 px-5 py-3 rounded-xl shadow-lg text-white text-sm font-medium z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
</script>
@endsection
