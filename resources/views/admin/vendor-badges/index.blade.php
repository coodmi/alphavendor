@extends('layouts.dashboard')

@section('title', 'Vendor Badges')
@section('page-title', 'Vendor Badges Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        <i class="fas fa-award text-indigo-600 mr-3"></i>Vendor Badges
                    </h1>
                    <p class="text-gray-100 mt-2 text-sm sm:text-base">Create and manage badges to recognize your top-performing vendors</p>
                </div>
                <button onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-plus-circle text-lg"></i>
                    <span>Create New Badge</span>
                </button>
            </div>
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

        <!-- Badges Grid -->
        @if($badges->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($badges as $badge)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-200 overflow-hidden group transform hover:-translate-y-1">
                        <!-- Badge Preview Section -->
                        <div class="p-6 bg-gradient-to-br from-white to-gray-50">
                            <div class="flex items-start justify-between mb-4">
                                <!-- Badge Display -->
                                <div class="flex-1">
                                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-transform group-hover:scale-105" 
                                        style="background-color: {{ $badge->bg_color }}; color: {{ $badge->color }};">
                                        @if($badge->icon)
                                            @if(str_starts_with($badge->icon, 'fa'))
                                                <i class="{{ $badge->icon }} text-base"></i>
                                            @else
                                                <span class="text-base">{{ $badge->icon }}</span>
                                            @endif
                                        @endif
                                        <span>{{ $badge->name }}</span>
                                    </div>
                                </div>
                                
                                <!-- Status Indicator -->
                                @if($badge->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                        <i class="fas fa-circle text-[6px]"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 text-gray-100 text-xs font-bold rounded-full">
                                        <i class="fas fa-circle text-[6px]"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="mt-3">
                                @if($badge->description)
                                    <p class="text-gray-100 text-sm leading-relaxed line-clamp-2">{{ $badge->description }}</p>
                                @else
                                    <p class="text-gray-400 text-sm italic">No description provided</p>
                                @endif
                            </div>
                        </div>

                        <!-- Stats Section -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center">
                                    <div class="text-2xl font-extrabold text-indigo-600">{{ $badge->vendors_count }}</div>
                                    <div class="text-xs text-gray-200 font-medium mt-1">Vendors</div>
                                </div>
                                <div class="text-center border-x border-gray-200">
                                    <div class="text-2xl font-extrabold text-purple-600">{{ $badge->sort_order }}</div>
                                    <div class="text-xs text-gray-200 font-medium mt-1">Priority</div>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-7 h-7 rounded-lg border-2 border-gray-300 shadow-sm" style="background-color: {{ $badge->color }};"></div>
                                        <div class="w-7 h-7 rounded-lg border-2 border-gray-300 shadow-sm" style="background-color: {{ $badge->bg_color }};"></div>
                                    </div>
                                    <div class="text-xs text-gray-200 font-medium mt-1">Colors</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="p-4 bg-white border-t border-gray-100">
                            <div class="flex gap-2">
                                <button onclick="editBadge({{ $badge->id }})" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-2.5 px-4 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </button>
                                <form action="{{ route('admin.vendor-badges.destroy', $badge) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this badge? It will be removed from all vendors.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-2.5 px-4 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-award text-6xl text-indigo-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">No Badges Created Yet</h3>
                <p class="text-gray-100 mb-8 max-w-md mx-auto">Start recognizing your top vendors by creating custom badges that highlight their achievements and trustworthiness</p>
                <button onclick="openAddModal()" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-plus-circle text-lg"></i>
                    <span>Create Your First Badge</span>
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Badge Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
    <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden transform transition-all">
        <form action="{{ route('admin.vendor-badges.store') }}" method="POST" onsubmit="console.log('Form submitting...'); return true;">
            @csrf
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-award text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Create New Badge</h3>
                        <p class="text-indigo-100 text-sm">Design a custom badge for your vendors</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-all">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <!-- Display Validation Errors -->
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3 mt-0.5"></i>
                            <div>
                                <h4 class="text-red-800 font-bold mb-2">Please fix the following errors:</h4>
                                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Badge Name -->
                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-tag text-indigo-600"></i>
                        Badge Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all text-white font-medium @error('name') border-red-500 @enderror" placeholder="e.g., Verified Seller, Top Rated, Premium Partner">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-indigo-600"></i>
                        Description
                    </label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all text-white @error('description') border-red-500 @enderror" placeholder="Brief description of what this badge represents and how vendors can earn it">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-icons text-indigo-600"></i>
                        Icon (Font Awesome or Emoji)
                    </label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all text-white font-medium @error('icon') border-red-500 @enderror" placeholder="e.g., fas fa-star or 🏆 or ⭐">
                    <p class="text-xs text-gray-200 mt-2 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i>
                        Paste any emoji (🏆 ⭐ 💎) or Font Awesome class (fas fa-star)
                    </p>
                    @error('icon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Colors -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                            <i class="fas fa-palette text-indigo-600"></i>
                            Text Color
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="color" value="#3B82F6" class="h-14 w-24 rounded-xl border-2 border-gray-300 cursor-pointer shadow-md hover:shadow-lg transition-all">
                            <div class="flex-1">
                                <p class="text-sm text-gray-100 font-medium">Badge text color</p>
                                <p class="text-xs text-gray-400">Choose a contrasting color</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                            <i class="fas fa-fill-drip text-indigo-600"></i>
                            Background Color
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="bg_color" value="#EFF6FF" class="h-14 w-24 rounded-xl border-2 border-gray-300 cursor-pointer shadow-md hover:shadow-lg transition-all">
                            <div class="flex-1">
                                <p class="text-sm text-gray-100 font-medium">Badge background</p>
                                <p class="text-xs text-gray-400">Light colors work best</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sort Order -->
                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-sort-numeric-down text-indigo-600"></i>
                        Display Priority
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all text-white font-medium @error('sort_order') border-red-500 @enderror">
                    <p class="text-xs text-gray-200 mt-2 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i>
                        Lower numbers appear first (0 = highest priority)
                    </p>
                    @error('sort_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-5 rounded-xl border-2 border-indigo-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="addIsActive" checked class="w-6 h-6 text-indigo-600 rounded-lg focus:ring-4 focus:ring-indigo-200 border-2 border-gray-300">
                        <div>
                            <span class="text-sm font-bold text-white block">Active Badge</span>
                            <span class="text-xs text-gray-100">Badge can be assigned to vendors immediately</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-200">
                <button type="button" onclick="closeAddModal()" class="px-6 py-3 border-2 border-gray-300 text-white rounded-xl font-semibold hover:bg-gray-100 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Create Badge
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Badge Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
    <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden transform transition-all">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-8 py-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Edit Badge</h3>
                        <p class="text-blue-100 text-sm">Update badge details and settings</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-all">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-tag text-blue-600"></i>
                        Badge Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="editName" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all text-white font-medium">
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-align-left text-blue-600"></i>
                        Description
                    </label>
                    <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all text-white"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-icons text-blue-600"></i>
                        Icon (Font Awesome or Emoji)
                    </label>
                    <input type="text" name="icon" id="editIcon" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all text-white font-medium" placeholder="e.g., fas fa-star or 🏆 or ⭐">
                    <p class="text-xs text-gray-200 mt-2 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i>
                        Paste any emoji (🏆 ⭐ 💎) or Font Awesome class (fas fa-star)
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                            <i class="fas fa-palette text-blue-600"></i>
                            Text Color
                        </label>
                        <input type="color" name="color" id="editColor" class="h-14 w-full rounded-xl border-2 border-gray-300 cursor-pointer shadow-md hover:shadow-lg transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                            <i class="fas fa-fill-drip text-blue-600"></i>
                            Background Color
                        </label>
                        <input type="color" name="bg_color" id="editBgColor" class="h-14 w-full rounded-xl border-2 border-gray-300 cursor-pointer shadow-md hover:shadow-lg transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2 flex items-center gap-2">
                        <i class="fas fa-sort-numeric-down text-blue-600"></i>
                        Display Priority
                    </label>
                    <input type="number" name="sort_order" id="editSortOrder" min="0" value="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all text-white font-medium">
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-5 rounded-xl border-2 border-blue-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="editIsActive" class="w-6 h-6 text-blue-600 rounded-lg focus:ring-4 focus:ring-blue-200 border-2 border-gray-300">
                        <div>
                            <span class="text-sm font-bold text-white block">Active Badge</span>
                            <span class="text-xs text-gray-100">Badge can be assigned to vendors</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 border-2 border-gray-300 text-white rounded-xl font-semibold hover:bg-gray-100 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update Badge
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const badges = @json($badges);

function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function editBadge(id) {
    const badge = badges.find(b => b.id === id);
    if (!badge) return;

    document.getElementById('editForm').action = `/admin/vendor-badges/${id}`;
    document.getElementById('editName').value = badge.name;
    document.getElementById('editDescription').value = badge.description || '';
    document.getElementById('editIcon').value = badge.icon || '';
    document.getElementById('editColor').value = badge.color;
    document.getElementById('editBgColor').value = badge.bg_color;
    document.getElementById('editSortOrder').value = badge.sort_order;
    document.getElementById('editIsActive').checked = badge.is_active;

    openEditModal();
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
});

// Close modals on backdrop click
document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

// Reopen modal if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openAddModal();
    });
@endif
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
