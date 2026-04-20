@extends('layouts.dashboard')

@section('title', 'Supplier Locations Management')
@section('page-title', 'Supplier Locations')

@section('sidebar-menu')
    @include('dashboards.partials.wholesaler-sidebar')
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-white mb-1">Supplier Locations Management</h2>
            <p class="text-gray-100">Manage supplier locations for your products</p>
        </div>
        <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i> Add Supplier Location
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Country</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Sort Order</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($locations as $location)
                <tr id="location-row-{{ $location->id }}" class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($location->name, 0, 2)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $location->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-white">{{ $location->country ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-100">{{ Str::limit($location->description, 50) ?? 'No description' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $location->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-white' }}">
                            {{ $location->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $location->sort_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <button data-location='@json($location)' onclick="editLocationFromData(this)" class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-150 mr-2">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button onclick="confirmDelete({{ $location->id }}, '{{ addslashes($location->name) }}')" class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-150">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="fas fa-map-marker-alt text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-200 text-lg">No supplier locations found. Click "Add Supplier Location" to create one.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="locationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Add Supplier Location</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form id="locationForm" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Location Name *</label>
                <input type="text" name="name" id="locationName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="e.g., China, United States">
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Country</label>
                <input type="text" name="country" id="locationCountry" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="e.g., China">
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Description</label>
                <textarea name="description" id="locationDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200" placeholder="Brief description about this supplier location"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="locationSortOrder" min="0" value="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>

            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="locationStatus" checked class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-3 text-sm font-medium text-white">Active</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="flex-1 px-6 py-3 bg-gray-200 text-white rounded-lg hover:bg-gray-300 transition-colors duration-150 font-semibold">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-semibold shadow-lg">
                    <i class="fas fa-save mr-2"></i>Save Location
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md shadow-2xl">
        <div class="p-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white text-center mb-2" id="deleteModalTitle">Delete Supplier Location?</h3>
            <p class="text-gray-100 text-center mb-6" id="deleteModalMessage">Are you sure you want to delete this supplier location?</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-gray-200 text-white rounded-lg hover:bg-gray-300 transition-colors duration-150 font-semibold">
                    Cancel
                </button>
                <button onclick="deleteLocation()" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-150 font-semibold">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let editingLocationId = null;
let deleteTargetId = null;

function openAddModal() {
    editingLocationId = null;
    document.getElementById('modalTitle').textContent = 'Add Supplier Location';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('locationForm').reset();
    document.getElementById('locationStatus').checked = true;
    document.getElementById('locationModal').classList.remove('hidden');
}

function editLocationFromData(button) {
    const location = JSON.parse(button.getAttribute('data-location'));
    editLocation(location);
}

function editLocation(location) {
    editingLocationId = location.id;
    document.getElementById('modalTitle').textContent = 'Edit Supplier Location';
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('locationName').value = location.name;
    document.getElementById('locationCountry').value = location.country || '';
    document.getElementById('locationDescription').value = location.description || '';
    document.getElementById('locationSortOrder').value = location.sort_order;
    document.getElementById('locationStatus').checked = location.is_active;

    document.getElementById('locationModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

function confirmDelete(id, name) {
    deleteTargetId = id;
    document.getElementById('deleteModalMessage').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteTargetId = null;
}

function deleteLocation() {
    if (!deleteTargetId) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/wholesaler/supplier-locations/${deleteTargetId}`;

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';

    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';

    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    form.submit();
}

// Form submission
document.getElementById('locationForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const url = editingLocationId ? `/wholesaler/supplier-locations/${editingLocationId}` : '{{ route('wholesaler.supplier-locations.store') }}';
    const method = editingLocationId ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });

        if (response.ok) {
            const message = editingLocationId ? 'Supplier location updated successfully!' : 'Supplier location created successfully!';
            showToast(message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const data = await response.json();
            showToast(data.message || 'An error occurred', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while saving the supplier location', 'error');
    }
});

// Close modals when clicking outside
document.getElementById('locationModal').addEventListener('click', function(event) {
    if (event.target.id === 'locationModal') {
        closeModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(event) {
    if (event.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});
</script>
@endsection
