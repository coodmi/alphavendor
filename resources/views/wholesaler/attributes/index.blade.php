@extends('layouts.dashboard')

@section('title', 'Attribute Management')
@section('page-title', 'Attributes')

@section('sidebar-menu')
    @include('dashboards.partials.wholesaler-sidebar')
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Product Attributes</h2>
            <p style="color: #7f8c8d;">Manage product attributes like Size, Color, Material, etc.</p>
        </div>
        <button onclick="openAddModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
            <i class="fas fa-plus"></i> Add Attribute
        </button>
    </div>
</div>

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- Attributes Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
    @forelse($attributes as $attribute)
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; position: relative;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 600; color: white; margin-bottom: 4px;">{{ $attribute->name }}</h3>
                    <span style="background: rgba(255,255,255,0.2); color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                        {{ ucfirst($attribute->type) }}
                    </span>
                </div>
                <div style="display: flex; gap: 8px;">
                    @if($attribute->is_required)
                    <span style="background: #ef4444; color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">Required</span>
                    @endif
                    @if($attribute->is_filterable)
                    <span style="background: #10b981; color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">Filterable</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 20px;">
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Slug</div>
                <div style="font-size: 14px; color: #2c3e50; font-family: monospace; background: #f9fafb; padding: 6px 10px; border-radius: 4px;">{{ $attribute->slug }}</div>
            </div>

            @if($attribute->type === 'select' && $attribute->options)
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 8px;">Options ({{ count($attribute->options) }})</div>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach(array_slice($attribute->options, 0, 5) as $option)
                    <span style="background: #f3f4f6; color: #4b5563; padding: 4px 10px; border-radius: 12px; font-size: 12px;">{{ $option }}</span>
                    @endforeach
                    @if(count($attribute->options) > 5)
                    <span style="background: #667eea; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;">+{{ count($attribute->options) - 5 }} more</span>
                    @endif
                </div>
            </div>
            @endif

            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fas fa-sort" style="color: #7f8c8d;"></i>
                <span style="font-size: 13px; color: #7f8c8d;">Sort Order: <strong style="color: #2c3e50;">{{ $attribute->sort_order }}</strong></span>
            </div>

            <div style="font-size: 12px; color: #9ca3af;">
                <i class="fas fa-clock"></i> Created {{ $attribute->created_at->diffForHumans() }}
            </div>
        </div>

        <!-- Actions -->
        <div style="padding: 16px; border-top: 1px solid #e5e7eb; background: #f9fafb; display: flex; gap: 8px;">
            <button onclick="openEditModal({{ $attribute->id }})" style="flex: 1; background: #667eea; color: white; padding: 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button onclick="deleteAttribute({{ $attribute->id }})" style="background: #ef4444; color: white; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <i class="fas fa-tags" style="font-size: 64px; color: #d1d5db; margin-bottom: 16px;"></i>
        <h3 style="font-size: 20px; color: #6b7280; margin-bottom: 8px;">No Attributes Yet</h3>
        <p style="color: #9ca3af; margin-bottom: 20px;">Create your first product attribute to get started</p>
        <button onclick="openAddModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
            <i class="fas fa-plus"></i> Add First Attribute
        </button>
    </div>
    @endforelse
</div>

<!-- Add/Edit Modal -->
<div id="attributeModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 12px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 id="modalTitle" style="font-size: 20px; font-weight: 600; color: white;">Add New Attribute</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; color: white; cursor: pointer; padding: 0; width: 32px; height: 32px;">×</button>
        </div>

        <form id="attributeForm" method="POST" style="padding: 24px;">
            @csrf
            <input type="hidden" id="attributeMethod" name="_method" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Attribute Name *</label>
                <input type="text" name="name" id="attributeName" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="e.g., Size, Color, Material">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Type *</label>
                <select name="type" id="attributeType" required onchange="toggleOptionsField()"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="text">Text (Free text input)</option>
                    <option value="select">Select (Dropdown with options)</option>
                    <option value="color">Color (Color picker)</option>
                    <option value="number">Number (Numeric input)</option>
                </select>
            </div>

            <div id="optionsField" style="margin-bottom: 20px; display: none;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Options (for Select type)</label>
                <div id="optionsList" style="margin-bottom: 12px;"></div>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="newOption" placeholder="Add option..."
                        style="flex: 1; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <button type="button" onclick="addOption()" style="background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                <input type="hidden" name="options" id="optionsInput">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Sort Order</label>
                <input type="number" name="sort_order" id="attributeSortOrder" value="0" min="0"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer; padding: 12px; background: #f9fafb; border-radius: 8px;">
                    <input type="checkbox" name="is_required" id="attributeRequired" value="1"
                        style="width: 18px; height: 18px; margin-right: 12px;">
                    <div>
                        <div style="font-size: 14px; font-weight: 500; color: #2c3e50;">Required Attribute</div>
                        <div style="font-size: 12px; color: #7f8c8d;">Customers must provide this attribute when ordering</div>
                    </div>
                </label>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer; padding: 12px; background: #f9fafb; border-radius: 8px;">
                    <input type="checkbox" name="is_filterable" id="attributeFilterable" value="1"
                        style="width: 18px; height: 18px; margin-right: 12px;">
                    <div>
                        <div style="font-size: 14px; font-weight: 500; color: #2c3e50;">Filterable</div>
                        <div style="font-size: 12px; color: #7f8c8d;">Allow customers to filter products by this attribute</div>
                    </div>
                </label>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" style="background: #e5e7eb; color: #6b7280; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    Cancel
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-save"></i> Save Attribute
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const attributes = @json($attributes);
let currentOptions = [];

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Attribute';
    document.getElementById('attributeForm').action = '{{ route("wholesaler.attributes.store") }}';
    document.getElementById('attributeMethod').value = 'POST';
    document.getElementById('attributeForm').reset();
    currentOptions = [];
    updateOptionsList();
    toggleOptionsField();
    document.getElementById('attributeModal').style.display = 'flex';
}

function openEditModal(attributeId) {
    const attribute = attributes.find(a => a.id === attributeId);
    if (!attribute) return;

    document.getElementById('modalTitle').textContent = 'Edit Attribute';
    document.getElementById('attributeForm').action = `/wholesaler/attributes/${attributeId}`;
    document.getElementById('attributeMethod').value = 'PUT';
    document.getElementById('attributeName').value = attribute.name;
    document.getElementById('attributeType').value = attribute.type;
    document.getElementById('attributeSortOrder').value = attribute.sort_order;
    document.getElementById('attributeRequired').checked = attribute.is_required;
    document.getElementById('attributeFilterable').checked = attribute.is_filterable;
    
    currentOptions = attribute.options || [];
    updateOptionsList();
    toggleOptionsField();
    
    document.getElementById('attributeModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('attributeModal').style.display = 'none';
}

function toggleOptionsField() {
    const type = document.getElementById('attributeType').value;
    const optionsField = document.getElementById('optionsField');
    optionsField.style.display = type === 'select' ? 'block' : 'none';
}

function addOption() {
    const input = document.getElementById('newOption');
    const value = input.value.trim();
    
    if (value && !currentOptions.includes(value)) {
        currentOptions.push(value);
        updateOptionsList();
        input.value = '';
    }
}

function removeOption(index) {
    currentOptions.splice(index, 1);
    updateOptionsList();
}

function updateOptionsList() {
    const list = document.getElementById('optionsList');
    const input = document.getElementById('optionsInput');
    
    if (currentOptions.length === 0) {
        list.innerHTML = '<div style="padding: 12px; background: #f9fafb; border-radius: 8px; text-align: center; color: #9ca3af; font-size: 13px;">No options added yet</div>';
    } else {
        list.innerHTML = currentOptions.map((option, index) => `
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: #f3f4f6; border-radius: 8px; margin-bottom: 8px;">
                <span style="font-size: 14px; color: #2c3e50;">${option}</span>
                <button type="button" onclick="removeOption(${index})" style="background: #ef4444; color: white; padding: 4px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `).join('');
    }
    
    input.value = JSON.stringify(currentOptions);
}

function deleteAttribute(attributeId) {
    if (!confirm('Are you sure you want to delete this attribute? This action cannot be undone.')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/wholesaler/attributes/${attributeId}`;
    
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

// Close modal on outside click
document.getElementById('attributeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Handle Enter key in new option input
document.getElementById('newOption').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addOption();
    }
});
</script>
@endsection
