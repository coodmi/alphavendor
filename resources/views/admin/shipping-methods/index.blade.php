@extends('layouts.dashboard')

@section('title', 'Shipping Management')
@section('page-title', 'Shipping Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    <i class="fas fa-shipping-fast text-orange-600 mr-3"></i>Shipping Management
                </h1>
                <p class="text-gray-600 mt-2">Manage shipping methods, zones, and pricing</p>
            </div>
            <button onclick="openCreateModal()" class="bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                <i class="fas fa-plus mr-2"></i>Add Shipping Method
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-white border-l-4 border-green-500 rounded-r-xl shadow-md overflow-hidden animate-fade-in">
                <div class="p-4 flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Methods</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $shippingMethods->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Active Methods</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $shippingMethods->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Zones Covered</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $shippingMethods->pluck('zone')->unique()->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Free Shipping</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $shippingMethods->whereNotNull('free_shipping_threshold')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-gift text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-gray-600"></i>
                    <span class="text-sm font-bold text-gray-700">Filter by Zone:</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterByZone('all')" class="zone-filter-btn active px-4 py-2 rounded-lg font-semibold text-sm transition-all" data-zone="all">
                        All Zones
                    </button>
                    @foreach($zones as $key => $name)
                        <button onclick="filterByZone('{{ $key }}')" class="zone-filter-btn px-4 py-2 rounded-lg font-semibold text-sm transition-all" data-zone="{{ $key }}">
                            {{ $name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Shipping Methods Table -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-orange-600 to-orange-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase">Method Name</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase">Zone</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase">Cost</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase">Delivery Time</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase">Free Shipping</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-white uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($shippingMethods as $method)
                            <tr class="hover:bg-orange-50 transition-colors shipping-row" data-zone="{{ $method->zone }}">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $method->name }}</div>
                                    @if($method->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ $method->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        {{ $method->zone }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-bold text-orange-600">৳{{ number_format($method->cost, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <i class="fas fa-clock text-gray-400 mr-1"></i>
                                    {{ $method->estimated_days_min }}-{{ $method->estimated_days_max }} days
                                </td>
                                <td class="px-6 py-4">
                                    @if($method->free_shipping_threshold)
                                        <span class="text-green-600 font-semibold text-sm">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Over ৳{{ number_format($method->free_shipping_threshold, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($method->is_active)
                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-3">
                                        <button onclick='openEditModal(@json($method))' class="text-blue-600 hover:text-blue-800 font-semibold transition-colors" title="Edit">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <form action="{{ route('admin.shipping-methods.destroy', $method) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this shipping method?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition-colors" title="Delete">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-inbox text-6xl mb-4 opacity-50"></i>
                                        <p class="text-lg font-semibold mb-2">No shipping methods configured</p>
                                        <p class="text-sm">Click "Add Shipping Method" to create your first shipping option</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="shippingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-white">Add Shipping Method</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="shippingForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodId" name="_method" value="POST">
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-tag text-orange-600 mr-2"></i>Method Name *
                    </label>
                    <input type="text" name="name" id="name" required 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                        placeholder="e.g., Standard Delivery">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>Zone *
                    </label>
                    <select name="zone" id="zone" required 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all">
                        <option value="">Select Zone</option>
                        @foreach($zones as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-orange-600 mr-2"></i>Description
                </label>
                <textarea name="description" id="description" rows="2" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                    placeholder="Brief description of this shipping method"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave text-orange-600 mr-2"></i>Shipping Cost (৳) *
                    </label>
                    <input type="number" name="cost" id="cost" step="0.01" min="0" required 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-gift text-orange-600 mr-2"></i>Free Shipping Threshold (৳)
                    </label>
                    <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" step="0.01" min="0" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                        placeholder="Optional">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-clock text-orange-600 mr-2"></i>Min Delivery Days *
                    </label>
                    <input type="number" name="estimated_days_min" id="estimated_days_min" min="1" required 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                        placeholder="1">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-clock text-orange-600 mr-2"></i>Max Delivery Days *
                    </label>
                    <input type="number" name="estimated_days_max" id="estimated_days_max" min="1" required 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                        placeholder="7">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-sort text-orange-600 mr-2"></i>Sort Order
                    </label>
                    <input type="number" name="sort_order" id="sort_order" min="0" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-orange-500 transition-all"
                        placeholder="0">
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                            class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm font-bold text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-save mr-2"></i>Save Shipping Method
                </button>
                <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterByZone(zone) {
    // Update button styles
    document.querySelectorAll('.zone-filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-orange-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
    });
    
    const activeBtn = document.querySelector(`[data-zone="${zone}"]`);
    activeBtn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
    activeBtn.classList.add('active', 'bg-orange-600', 'text-white');
    
    // Filter table rows
    const rows = document.querySelectorAll('.shipping-row');
    rows.forEach(row => {
        if (zone === 'all' || row.dataset.zone === zone) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add Shipping Method';
    document.getElementById('shippingForm').action = '{{ route("admin.shipping-methods.store") }}';
    document.getElementById('methodId').value = 'POST';
    document.getElementById('shippingForm').reset();
    document.getElementById('is_active').checked = true;
    document.getElementById('shippingModal').classList.remove('hidden');
}

function openEditModal(method) {
    document.getElementById('modalTitle').textContent = 'Edit Shipping Method';
    document.getElementById('shippingForm').action = `/admin/shipping-methods/${method.id}`;
    document.getElementById('methodId').value = 'PUT';
    
    document.getElementById('name').value = method.name;
    document.getElementById('zone').value = method.zone;
    document.getElementById('description').value = method.description || '';
    document.getElementById('cost').value = method.cost;
    document.getElementById('free_shipping_threshold').value = method.free_shipping_threshold || '';
    document.getElementById('estimated_days_min').value = method.estimated_days_min;
    document.getElementById('estimated_days_max').value = method.estimated_days_max;
    document.getElementById('sort_order').value = method.sort_order;
    document.getElementById('is_active').checked = method.is_active;
    
    document.getElementById('shippingModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('shippingModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Initialize filter button styles
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.zone-filter-btn').forEach(btn => {
        if (!btn.classList.contains('active')) {
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        } else {
            btn.classList.add('bg-orange-600', 'text-white');
        }
    });
});
</script>
@endsection
