@extends('layouts.dashboard')

@section('title', 'Special Offers Management')
@section('page-title', 'Special Offers Management')

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
                    <i class="fas fa-tag text-teal-700 mr-3"></i>Special Offers Management
                </h1>
                <p class="text-gray-600 mt-2">Create and manage special offer categories for products</p>
            </div>
            <button onclick="openCreateModal()" class="bg-gradient-to-r from-teal-700 to-teal-800 hover:from-teal-800 hover:to-teal-900 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                <i class="fas fa-plus mr-2"></i>Add Special Offer
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

        <!-- Special Offers Grid -->
        @if($offers->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offers as $offer)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all">
                        @if($offer->image)
                            <div class="h-48 overflow-hidden bg-gray-100">
                                <img src="{{ $offer->image_url }}" alt="{{ $offer->name }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-900">{{ $offer->name }}</h3>
                                @if($offer->badge_text)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ $offer->badge_color ?? '#0d5c63' }}; color: white;">
                                        {{ $offer->badge_text }}
                                    </span>
                                @endif
                            </div>

                            @if($offer->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $offer->description }}</p>
                            @endif

                            <div class="flex items-center gap-4 mb-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-box mr-2 text-teal-600"></i>
                                    <span class="font-semibold">{{ $offer->products()->count() }}</span>
                                    <span class="ml-1">Products</span>
                                </div>
                                <div class="flex items-center">
                                    @if($offer->is_active)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($offer->start_date || $offer->end_date)
                                <div class="text-xs text-gray-500 mb-4">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    @if($offer->start_date && $offer->end_date)
                                        {{ $offer->start_date->format('M d, Y') }} - {{ $offer->end_date->format('M d, Y') }}
                                    @elseif($offer->end_date)
                                        Ends: {{ $offer->end_date->format('M d, Y') }}
                                    @elseif($offer->start_date)
                                        Starts: {{ $offer->start_date->format('M d, Y') }}
                                    @endif
                                </div>
                            @endif

                            <div class="flex gap-2 pt-4 border-t border-gray-100">
                                <button onclick='openEditModal(@json($offer))' class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.special-offers.destroy', $offer) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this special offer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-tag text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Special Offers Yet</h3>
                <p class="text-gray-500 mb-6">Create your first special offer to start organizing products</p>
                <button onclick="openCreateModal()" class="inline-block bg-teal-700 hover:bg-teal-800 text-white px-8 py-3 rounded-xl font-bold transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Special Offer
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="offerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-teal-700 to-teal-800 px-6 py-4 flex justify-between items-center">
            <h3 id="modalTitle" class="text-xl font-bold text-white">Add Special Offer</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="offerForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="methodId" name="_method" value="POST">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-tag text-teal-700 mr-2"></i>Offer Name *
                </label>
                <input type="text" name="name" id="name" required 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all"
                    placeholder="e.g., Summer Sale">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-teal-700 mr-2"></i>Description
                </label>
                <textarea name="description" id="description" rows="3" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all"
                    placeholder="Brief description of this offer"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-certificate text-teal-700 mr-2"></i>Badge Text
                    </label>
                    <input type="text" name="badge_text" id="badge_text" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all"
                        placeholder="e.g., 50% OFF">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-palette text-teal-700 mr-2"></i>Badge Color
                    </label>
                    <input type="color" name="badge_color" id="badge_color" value="#0d5c63"
                        class="w-full h-12 px-2 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-image text-teal-700 mr-2"></i>Offer Image
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all">
                <p class="text-xs text-gray-500 mt-1">Recommended: 800x400px</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-teal-700 mr-2"></i>Start Date
                    </label>
                    <input type="date" name="start_date" id="start_date"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-teal-700 mr-2"></i>End Date
                    </label>
                    <input type="date" name="end_date" id="end_date"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-sort text-teal-700 mr-2"></i>Sort Order
                    </label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="0"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-200 focus:border-teal-600 transition-all">
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                            class="w-5 h-5 text-teal-700 border-gray-300 rounded focus:ring-teal-600">
                        <span class="text-sm font-bold text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-teal-700 to-teal-800 hover:from-teal-800 hover:to-teal-900 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-save mr-2"></i>Save Offer
                </button>
                <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add Special Offer';
    document.getElementById('offerForm').action = '{{ route("admin.special-offers.store") }}';
    document.getElementById('methodId').value = 'POST';
    document.getElementById('offerForm').reset();
    document.getElementById('is_active').checked = true;
    document.getElementById('badge_color').value = '#0d5c63';
    document.getElementById('offerModal').classList.remove('hidden');
}

function openEditModal(offer) {
    document.getElementById('modalTitle').textContent = 'Edit Special Offer';
    document.getElementById('offerForm').action = `/admin/special-offers/${offer.id}`;
    document.getElementById('methodId').value = 'PUT';
    
    document.getElementById('name').value = offer.name;
    document.getElementById('description').value = offer.description || '';
    document.getElementById('badge_text').value = offer.badge_text || '';
    document.getElementById('badge_color').value = offer.badge_color || '#0d5c63';
    document.getElementById('start_date').value = offer.start_date || '';
    document.getElementById('end_date').value = offer.end_date || '';
    document.getElementById('sort_order').value = offer.sort_order;
    document.getElementById('is_active').checked = offer.is_active;
    
    document.getElementById('offerModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('offerModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
