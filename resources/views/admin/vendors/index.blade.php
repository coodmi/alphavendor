@extends('layouts.dashboard')

@section('title', 'Vendor Management')
@section('page-title', 'All Vendors')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Total Vendors</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_vendors'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-store text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Active Vendors</p>
                <h3 class="text-3xl font-bold">{{ $stats['active_vendors'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Retailers</p>
                <h3 class="text-3xl font-bold">{{ $stats['retailers'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm mb-1">Wholesalers</p>
                <h3 class="text-3xl font-bold">{{ $stats['wholesalers'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-boxes text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm mb-1">Importers</p>
                <h3 class="text-3xl font-bold">{{ $stats['exporters'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-globe text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Vendors Table -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Vendor Management</h2>
            <p class="text-gray-200 mt-1">Manage all vendors and their activities</p>
        </div>
        <div class="flex gap-3">
            <select id="roleFilter" onchange="filterByRole()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="retailer">Retailers</option>
                <option value="wholesaler">Wholesalers</option>
                <option value="exporter">Importers</option>
            </select>
            <select id="statusFilter" onchange="filterByStatus()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="pending">Pending Approval</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-blue-700 font-medium">
                <span id="selectedCount">0</span> vendor(s) selected
            </span>
        </div>
        <div class="flex gap-2">
            <button onclick="bulkUpdateStatus('pending')" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                <i class="fas fa-clock"></i> Set Pending
            </button>
            <button onclick="bulkUpdateStatus('active')" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                <i class="fas fa-check-circle"></i> Approve
            </button>
            <button onclick="bulkUpdateStatus('inactive')" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-ban"></i> Deactivate
            </button>
            <button onclick="bulkUpdateStatus('suspended')" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                <i class="fas fa-pause-circle"></i> Suspend
            </button>
            <button onclick="bulkDelete()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <i class="fas fa-trash"></i> Delete
            </button>
            <button onclick="clearSelection()" class="px-4 py-2 bg-white border border-gray-300 text-white rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>

    @if($vendors->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Badge</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-100 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($vendors as $vendor)
                    <tr class="hover:bg-gray-50 transition-colors" data-role="{{ $vendor->role }}" data-status="{{ $vendor->status }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="vendor-checkbox w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer" value="{{ $vendor->id }}" onchange="updateSelection()">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($vendor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $vendor->name }}</div>
                                    <div class="text-xs text-gray-200">ID: #{{ $vendor->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium 
                                {{ $vendor->role === 'retailer' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $vendor->role === 'wholesaler' ? 'bg-teal-100 text-teal-800' : '' }}
                                {{ $vendor->role === 'exporter' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ $vendor->role === 'exporter' ? 'Importer' : ucfirst($vendor->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <select onchange="updateVendorBadge({{ $vendor->id }}, this.value)" 
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                                <option value="">No Badge</option>
                                @foreach($badges as $badge)
                                    <option value="{{ $badge->id }}" {{ $vendor->vendor_badge_id == $badge->id ? 'selected' : '' }}>
                                        {{ $badge->icon }} {{ $badge->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($vendor->vendorBadge)
                                <div class="mt-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold" 
                                        style="background-color: {{ $vendor->vendorBadge->bg_color }}; color: {{ $vendor->vendorBadge->color }};">
                                        @if($vendor->vendorBadge->icon)
                                            @if(str_starts_with($vendor->vendorBadge->icon, 'fa'))
                                                <i class="{{ $vendor->vendorBadge->icon }}"></i>
                                            @else
                                                <span>{{ $vendor->vendorBadge->icon }}</span>
                                            @endif
                                        @endif
                                        {{ $vendor->vendorBadge->name }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-white">{{ $vendor->email }}</div>
                                <div class="text-gray-200">{{ $vendor->phone ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white font-medium">{{ $vendor->products_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white font-medium">{{ $vendor->orders_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <select onchange="updateVendorStatus({{ $vendor->id }}, this.value)" 
                                class="px-3 py-1 rounded-full text-xs font-medium border-0 cursor-pointer
                                {{ $vendor->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $vendor->status === 'inactive' ? 'bg-gray-100 text-white' : '' }}
                                {{ $vendor->status === 'pending' ? 'bg-teal-100 text-teal-700' : '' }}
                                {{ $vendor->status === 'suspended' ? 'bg-red-100 text-red-700' : '' }}">
                                <option value="pending" {{ $vendor->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ $vendor->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $vendor->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $vendor->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.vendors.show', $vendor) }}" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $vendors->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-store text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-200 text-lg">No vendors found</p>
        </div>
    @endif
</div>

<script>
// Bulk selection functions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.vendor-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelection();
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.vendor-checkbox:checked');
    const count = checkboxes.length;
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = count;
    
    if (count > 0) {
        bulkActionsBar.classList.remove('hidden');
    } else {
        bulkActionsBar.classList.add('hidden');
    }
    
    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.vendor-checkbox');
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = count === allCheckboxes.length && count > 0;
}

function getSelectedVendorIds() {
    const checkboxes = document.querySelectorAll('.vendor-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.vendor-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateSelection();
}

async function bulkUpdateStatus(status) {
    const vendorIds = getSelectedVendorIds();
    
    if (vendorIds.length === 0) {
        showToast('Please select vendors first', 'error');
        return;
    }
    
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    if (!confirm(`Are you sure you want to ${statusText.toLowerCase()} ${vendorIds.length} vendor(s)?`)) {
        return;
    }
    
    try {
        const response = await fetch('/admin/vendors/bulk-update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ vendor_ids: vendorIds, status: status })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(`${vendorIds.length} vendor(s) ${statusText.toLowerCase()}d successfully!`, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Failed to update vendors', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

async function bulkDelete() {
    const vendorIds = getSelectedVendorIds();
    
    if (vendorIds.length === 0) {
        showToast('Please select vendors first', 'error');
        return;
    }
    
    if (!confirm(`⚠️ WARNING: Are you sure you want to DELETE ${vendorIds.length} vendor(s)?\n\nThis will also delete:\n- All their products\n- All their orders\n- All their data\n\nThis action CANNOT be undone!`)) {
        return;
    }
    
    // Double confirmation for safety
    if (!confirm(`Final confirmation: Delete ${vendorIds.length} vendor(s) permanently?`)) {
        return;
    }
    
    try {
        const response = await fetch('/admin/vendors/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ vendor_ids: vendorIds })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(`${vendorIds.length} vendor(s) deleted successfully!`, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Failed to delete vendors', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

async function updateVendorStatus(vendorId, status) {
    try {
        const response = await fetch(`/admin/vendors/${vendorId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Vendor status updated successfully!', 'success');
        } else {
            showToast('Failed to update status', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

async function updateVendorBadge(vendorId, badgeId) {
    try {
        const response = await fetch(`/admin/vendors/${vendorId}/badge`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ vendor_badge_id: badgeId || null })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Vendor badge updated successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Failed to update badge', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

function filterByRole() {
    const role = document.getElementById('roleFilter').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (!role || row.dataset.role === role) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (!status || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
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
