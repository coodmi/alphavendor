@extends('layouts.dashboard')

@section('title', 'Attribute Management')
@section('page-title', 'Attributes')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Product Attributes</h2>
            <p class="text-sm text-gray-500 mt-1">Manage attributes like Size, Color, Material, Weight, etc.</p>
        </div>
        <button onclick="openAddModal()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition shadow-md">
            <i class="fas fa-plus"></i> Add Attribute
        </button>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle text-green-500 text-lg"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Attributes Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($attributes as $attribute)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
            <!-- Card Header -->
            <div class="px-5 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-start justify-between">
                <div>
                    <h3 class="text-white font-bold text-base">{{ $attribute->name }}</h3>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="bg-white/20 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            @if($attribute->type === 'text') <i class="fas fa-font mr-1"></i>Text
                            @elseif($attribute->type === 'select') <i class="fas fa-list mr-1"></i>Select
                            @elseif($attribute->type === 'color') <i class="fas fa-palette mr-1"></i>Color
                            @elseif($attribute->type === 'number') <i class="fas fa-hashtag mr-1"></i>Number
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex flex-col gap-1 items-end">
                    @if($attribute->is_required)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">Required</span>
                    @endif
                    @if($attribute->is_filterable)
                    <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">Filterable</span>
                    @endif
                </div>
            </div>

            <!-- Card Body -->
            <div class="px-5 py-4 space-y-3">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Slug</p>
                    <code class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded font-mono">{{ $attribute->slug }}</code>
                </div>

                @if($attribute->type === 'select' && $attribute->options && count($attribute->options) > 0)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-2">Options ({{ count($attribute->options) }})</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(array_slice($attribute->options, 0, 6) as $option)
                        <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full">{{ $option }}</span>
                        @endforeach
                        @if(count($attribute->options) > 6)
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-semibold">+{{ count($attribute->options) - 6 }} more</span>
                        @endif
                    </div>
                </div>
                @elseif($attribute->type === 'color')
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Type</p>
                    <span class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <span class="w-5 h-5 rounded-full border border-gray-300 bg-gradient-to-br from-red-400 via-green-400 to-blue-400 inline-block"></span>
                        Color Picker Input
                    </span>
                </div>
                @elseif($attribute->type === 'number')
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Type</p>
                    <span class="text-sm text-gray-600"><i class="fas fa-hashtag text-indigo-400 mr-1"></i>Numeric Input</span>
                </div>
                @else
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Type</p>
                    <span class="text-sm text-gray-600"><i class="fas fa-font text-indigo-400 mr-1"></i>Free Text Input</span>
                </div>
                @endif

                <div class="flex items-center justify-between text-xs text-gray-400 pt-1 border-t border-gray-50">
                    <span><i class="fas fa-sort mr-1"></i>Order: <strong class="text-gray-600">{{ $attribute->sort_order }}</strong></span>
                    <span><i class="fas fa-clock mr-1"></i>{{ $attribute->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                <button onclick="openEditModal({{ $attribute->id }})"
                    class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button onclick="deleteAttribute({{ $attribute->id }})"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
            <i class="fas fa-tags text-5xl text-gray-200 mb-4 block"></i>
            <h3 class="text-lg font-semibold text-gray-500 mb-2">No Attributes Yet</h3>
            <p class="text-gray-400 text-sm mb-5">Create your first product attribute to get started</p>
            <button onclick="openAddModal()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition">
                <i class="fas fa-plus"></i> Add First Attribute
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- ===== ADD / EDIT MODAL ===== -->
<div id="attributeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="backdrop-filter: blur(2px);">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="text-white font-bold text-lg">Add New Attribute</h3>
            <button onclick="closeModal()" class="text-white/80 hover:text-white text-2xl leading-none">&times;</button>
        </div>

        <!-- Modal Form -->
        <form id="attributeForm" method="POST" class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
            @csrf
            <input type="hidden" id="attributeMethod" name="_method" value="POST">

            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Attribute Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="attributeName" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition"
                    placeholder="e.g., Size, Color, Material, Weight">
            </div>

            <!-- Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
                <select name="type" id="attributeType" required onchange="handleTypeChange()"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition bg-white">
                    <option value="text">📝 Text — Free text input</option>
                    <option value="select">📋 Select — Dropdown with options</option>
                    <option value="color">🎨 Color — Color picker</option>
                    <option value="number">🔢 Number — Numeric input</option>
                </select>
            </div>

            <!-- Type Preview -->
            <div id="typePreview" class="hidden p-3 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-2">Preview</p>
                <div id="typePreviewContent"></div>
            </div>

            <!-- Options (for Select type) -->
            <div id="optionsField" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Options <span class="text-red-500">*</span></label>
                <div id="optionsList" class="mb-3 space-y-2 max-h-40 overflow-y-auto"></div>
                <div class="flex gap-2">
                    <input type="text" id="newOption" placeholder="Type an option and press Enter or click Add..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
                    <button type="button" onclick="addOption()"
                        class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                <input type="hidden" name="options" id="optionsInput">
                <p class="text-xs text-gray-400 mt-1.5">Add all possible values for this attribute (e.g., Small, Medium, Large)</p>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sort Order</label>
                <input type="number" name="sort_order" id="attributeSortOrder" value="0" min="0"
                    class="w-32 px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
                <p class="text-xs text-gray-400 mt-1">Lower numbers appear first</p>
            </div>

            <!-- Checkboxes -->
            <div class="space-y-3">
                <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition">
                    <input type="checkbox" name="is_required" id="attributeRequired" value="1"
                        class="w-4 h-4 mt-0.5 accent-indigo-600">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Required Attribute</p>
                        <p class="text-xs text-gray-500">Vendors must fill this attribute when adding products</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition">
                    <input type="checkbox" name="is_filterable" id="attributeFilterable" value="1"
                        class="w-4 h-4 mt-0.5 accent-indigo-600">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Filterable</p>
                        <p class="text-xs text-gray-500">Allow customers to filter products by this attribute on the shop page</p>
                    </div>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()"
                    class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Save Attribute
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const attributes = @json($attributes);
let currentOptions = [];

// ---- Modal Open/Close ----
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Attribute';
    document.getElementById('attributeForm').action = '{{ route("admin.attributes.store") }}';
    document.getElementById('attributeMethod').value = 'POST';
    document.getElementById('attributeForm').reset();
    currentOptions = [];
    renderOptionsList();
    handleTypeChange();
    document.getElementById('attributeModal').classList.remove('hidden');
}

function openEditModal(id) {
    const attr = attributes.find(a => a.id === id);
    if (!attr) return;

    document.getElementById('modalTitle').textContent = 'Edit Attribute';
    document.getElementById('attributeForm').action = `/admin/attributes/${id}`;
    document.getElementById('attributeMethod').value = 'PUT';
    document.getElementById('attributeName').value = attr.name;
    document.getElementById('attributeType').value = attr.type;
    document.getElementById('attributeSortOrder').value = attr.sort_order;
    document.getElementById('attributeRequired').checked = !!attr.is_required;
    document.getElementById('attributeFilterable').checked = !!attr.is_filterable;

    currentOptions = Array.isArray(attr.options) ? [...attr.options] : [];
    renderOptionsList();
    handleTypeChange();

    document.getElementById('attributeModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('attributeModal').classList.add('hidden');
}

// Close on backdrop click
document.getElementById('attributeModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ---- Type Change Handler ----
function handleTypeChange() {
    const type = document.getElementById('attributeType').value;
    const optionsField = document.getElementById('optionsField');
    const preview = document.getElementById('typePreview');
    const previewContent = document.getElementById('typePreviewContent');

    // Show/hide options field
    optionsField.classList.toggle('hidden', type !== 'select');

    // Show type preview
    preview.classList.remove('hidden');

    if (type === 'text') {
        previewContent.innerHTML = `<input type="text" disabled placeholder="e.g., Cotton, Polyester, Leather..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-400" style="cursor:not-allowed">`;
    } else if (type === 'select') {
        previewContent.innerHTML = `<select disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-400" style="cursor:not-allowed"><option>Select an option...</option></select>`;
    } else if (type === 'color') {
        previewContent.innerHTML = `
            <div class="flex items-center gap-3">
                <input type="color" value="#0d5c63" class="w-12 h-10 rounded-lg border border-gray-300 cursor-pointer p-0.5">
                <span class="text-sm text-gray-600">Customer picks a color using the color picker</span>
            </div>`;
    } else if (type === 'number') {
        previewContent.innerHTML = `<input type="number" disabled placeholder="e.g., 42, 100, 2.5..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-400" style="cursor:not-allowed">`;
    }
}

// ---- Options Management ----
function addOption() {
    const input = document.getElementById('newOption');
    const value = input.value.trim();
    if (!value) return;
    if (currentOptions.includes(value)) {
        input.style.borderColor = '#ef4444';
        setTimeout(() => input.style.borderColor = '', 1500);
        return;
    }
    currentOptions.push(value);
    renderOptionsList();
    input.value = '';
    input.focus();
}

function removeOption(index) {
    currentOptions.splice(index, 1);
    renderOptionsList();
}

function renderOptionsList() {
    const list = document.getElementById('optionsList');
    const hiddenInput = document.getElementById('optionsInput');

    if (currentOptions.length === 0) {
        list.innerHTML = `<div class="text-center py-3 text-gray-400 text-sm bg-gray-50 rounded-lg border border-dashed border-gray-200">No options yet — add some above</div>`;
    } else {
        list.innerHTML = currentOptions.map((opt, i) => `
            <div class="flex items-center justify-between px-3 py-2 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 bg-indigo-100 text-indigo-600 rounded-full text-xs flex items-center justify-center font-bold">${i + 1}</span>
                    <span class="text-sm text-gray-700 font-medium">${escHtml(opt)}</span>
                </div>
                <button type="button" onclick="removeOption(${i})"
                    class="text-red-400 hover:text-red-600 hover:bg-red-50 w-7 h-7 rounded-lg flex items-center justify-center transition text-xs">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
    }

    hiddenInput.value = JSON.stringify(currentOptions);
}

// ---- Delete ----
function deleteAttribute(id) {
    if (!confirm('Delete this attribute? Products using it will lose this attribute value.')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/attributes/${id}`;
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;
    document.body.appendChild(form);
    form.submit();
}

// ---- Helpers ----
function escHtml(t) {
    return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Enter key in option input
document.getElementById('newOption').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); addOption(); }
});

// Init preview on page load
document.addEventListener('DOMContentLoaded', handleTypeChange);
</script>
@endsection
