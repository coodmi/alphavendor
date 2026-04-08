@extends('layouts.dashboard')

@section('title', 'Import Page Management')
@section('page-title', 'Import Page')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Import Page Hero Slider</h2>
            <p style="color: #7f8c8d;">Manage import page slider images, titles, descriptions, and CTAs</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('import') }}" target="_blank" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: all 0.3s;">
                <i class="fas fa-external-link-alt"></i> Preview Page
            </a>
            <button onclick="openAddModal()" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                <i class="fas fa-plus"></i> Add New Slide
            </button>
        </div>
    </div>
</div>

<!-- Slides List -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
    @forelse($slides as $slide)
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; position: relative;">
        <div style="position: absolute; top: 12px; right: 12px; z-index: 10;">
            <span style="background: {{ $slide->is_active ? '#10b981' : '#ef4444' }}; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                {{ $slide->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div style="position: absolute; top: 12px; left: 12px; z-index: 10;">
            <span style="background: rgba(0,0,0,0.7); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                Order: {{ $slide->order }}
            </span>
        </div>
        <div style="height: 200px; overflow: hidden; background: #f3f4f6;">
            <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" 
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="padding: 20px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">{{ $slide->title }}</h3>
            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 12px; line-height: 1.5;">{{ Str::limit($slide->description, 100) }}</p>
            
            @if($slide->cta_text)
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding: 10px; background: #f3f4f6; border-radius: 6px;">
                <i class="fas fa-link" style="color: #667eea;"></i>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 12px; color: #7f8c8d;">CTA Button</div>
                    <div style="font-size: 14px; font-weight: 500; color: #2c3e50;">{{ $slide->cta_text }}</div>
                    <div style="font-size: 12px; color: #667eea; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $slide->cta_link }}</div>
                </div>
            </div>
            @endif

            <div style="display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                <button onclick="openEditModal({{ $slide->id }})" style="flex: 1; background: #667eea; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button onclick="toggleStatus({{ $slide->id }})" style="background: {{ $slide->is_active ? '#ef4444' : '#10b981' }}; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-{{ $slide->is_active ? 'eye-slash' : 'eye' }}"></i>
                </button>
                <button onclick="deleteSlide({{ $slide->id }})" style="background: #dc2626; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <i class="fas fa-images" style="font-size: 64px; color: #d1d5db; margin-bottom: 16px;"></i>
        <h3 style="font-size: 20px; color: #6b7280; margin-bottom: 8px;">No Import Slides Yet</h3>
        <p style="color: #9ca3af; margin-bottom: 20px;">Create your first import slide to get started</p>
        <button onclick="openAddModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
            <i class="fas fa-plus"></i> Add First Slide
        </button>
    </div>
    @endforelse
</div>

<!-- Modal -->
<div id="slideModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 12px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modalTitle" style="font-size: 20px; font-weight: 600; color: #2c3e50;">Add New Slide</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; color: #9ca3af; cursor: pointer; padding: 0; width: 32px; height: 32px;">×</button>
        </div>

        <form id="slideForm" method="POST" enctype="multipart/form-data" style="padding: 24px;">
            @csrf
            <input type="hidden" id="slideMethod" name="_method" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Title *</label>
                <input type="text" name="title" id="slideTitle" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Description *</label>
                <textarea name="description" id="slideDescription" rows="3" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Image *</label>
                <div id="imagePreviewContainer" style="margin-bottom: 12px; display: none;">
                    <img id="imagePreview" src="" alt="Preview" 
                        style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <input type="file" name="image" id="slideImage" accept="image/*"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Max 2MB (JPEG, PNG, JPG, GIF, WebP)</p>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">CTA Button Text</label>
                <input type="text" name="cta_text" id="slideCtaText"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="e.g., Shop Now">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">CTA Button Link</label>
                <input type="text" name="cta_link" id="slideCtaLink"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="e.g., /import">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Display Order</label>
                <input type="number" name="order" id="slideOrder" min="0" value="0"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="slideIsActive" value="1" checked
                        style="width: 18px; height: 18px; margin-right: 8px;">
                    <span style="font-size: 14px; font-weight: 500; color: #2c3e50;">Active (Show on import page)</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" style="background: #e5e7eb; color: #6b7280; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    Cancel
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-save"></i> Save Slide
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const slides = @json($slides);

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Slide';
    document.getElementById('slideForm').action = '{{ route("admin.import-page.slides.store") }}';
    document.getElementById('slideMethod').value = 'POST';
    document.getElementById('slideForm').reset();
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('slideImage').required = true;
    document.getElementById('slideModal').style.display = 'flex';
}

function openEditModal(slideId) {
    const slide = slides.find(s => s.id === slideId);
    if (!slide) return;

    document.getElementById('modalTitle').textContent = 'Edit Slide';
    document.getElementById('slideForm').action = `/admin/import-page/slides/${slideId}`;
    document.getElementById('slideMethod').value = 'PUT';
    document.getElementById('slideTitle').value = slide.title;
    document.getElementById('slideDescription').value = slide.description;
    document.getElementById('slideCtaText').value = slide.cta_text || '';
    document.getElementById('slideCtaLink').value = slide.cta_link || '';
    document.getElementById('slideOrder').value = slide.order;
    document.getElementById('slideIsActive').checked = slide.is_active;
    document.getElementById('slideImage').required = false;
    
    const preview = document.getElementById('imagePreview');
    preview.src = `/storage/${slide.image}`;
    document.getElementById('imagePreviewContainer').style.display = 'block';
    
    document.getElementById('slideModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('slideModal').style.display = 'none';
}

function toggleStatus(slideId) {
    if (!confirm('Are you sure you want to toggle the status of this slide?')) return;
    
    fetch(`/admin/import-page/slides/${slideId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteSlide(slideId) {
    if (!confirm('Are you sure you want to delete this slide? This action cannot be undone.')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/import-page/slides/${slideId}`;
    
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

document.getElementById('slideImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagePreview').src = event.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('slideModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Wholesale Page Hero Slider</h2>
            <p style="color: #7f8c8d;">Manage wholesale page slider images, titles, descriptions, and CTAs</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('wholesale') }}" target="_blank" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: all 0.3s;">
                <i class="fas fa-external-link-alt"></i> Preview Page
            </a>
            <button onclick="openAddModal()" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                <i class="fas fa-plus"></i> Add New Slide
            </button>
        </div>
    </div>
</div>

<!-- Slides List -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
    @forelse($slides as $slide)
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; position: relative;">
        <div style="position: absolute; top: 12px; right: 12px; z-index: 10;">
            <span style="background: {{ $slide->is_active ? '#10b981' : '#ef4444' }}; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                {{ $slide->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div style="position: absolute; top: 12px; left: 12px; z-index: 10;">
            <span style="background: rgba(0,0,0,0.7); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                Order: {{ $slide->order }}
            </span>
        </div>
        <div style="height: 200px; overflow: hidden; background: #f3f4f6;">
            <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" 
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="padding: 20px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 8px;">{{ $slide->title }}</h3>
            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 12px; line-height: 1.5;">{{ Str::limit($slide->description, 100) }}</p>
            
            @if($slide->cta_text)
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; padding: 10px; background: #f3f4f6; border-radius: 6px;">
                <i class="fas fa-link" style="color: #667eea;"></i>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 12px; color: #7f8c8d;">CTA Button</div>
                    <div style="font-size: 14px; font-weight: 500; color: #2c3e50;">{{ $slide->cta_text }}</div>
                    <div style="font-size: 12px; color: #667eea; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $slide->cta_link }}</div>
                </div>
            </div>
            @endif

            <div style="display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                <button onclick="openEditModal({{ $slide->id }})" style="flex: 1; background: #667eea; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button onclick="toggleStatus({{ $slide->id }})" style="background: {{ $slide->is_active ? '#ef4444' : '#10b981' }}; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-{{ $slide->is_active ? 'eye-slash' : 'eye' }}"></i>
                </button>
                <button onclick="deleteSlide({{ $slide->id }})" style="background: #dc2626; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <i class="fas fa-images" style="font-size: 64px; color: #d1d5db; margin-bottom: 16px;"></i>
        <h3 style="font-size: 20px; color: #6b7280; margin-bottom: 8px;">No Wholesale Slides Yet</h3>
        <p style="color: #9ca3af; margin-bottom: 20px;">Create your first wholesale slide to get started</p>
        <button onclick="openAddModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
            <i class="fas fa-plus"></i> Add First Slide
        </button>
    </div>
    @endforelse
</div>

<!-- Modal (same as home page) -->
<div id="slideModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 12px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modalTitle" style="font-size: 20px; font-weight: 600; color: #2c3e50;">Add New Slide</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; color: #9ca3af; cursor: pointer; padding: 0; width: 32px; height: 32px;">×</button>
        </div>

        <form id="slideForm" method="POST" enctype="multipart/form-data" style="padding: 24px;">
            @csrf
            <input type="hidden" id="slideMethod" name="_method" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Title *</label>
                <input type="text" name="title" id="slideTitle" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Description *</label>
                <textarea name="description" id="slideDescription" rows="3" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Image *</label>
                <div id="imagePreviewContainer" style="margin-bottom: 12px; display: none;">
                    <img id="imagePreview" src="" alt="Preview" 
                        style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                </div>
                <input type="file" name="image" id="slideImage" accept="image/*"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Max 2MB (JPEG, PNG, JPG, GIF, WebP)</p>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">CTA Button Text</label>
                <input type="text" name="cta_text" id="slideCtaText"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="e.g., Shop Now">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">CTA Button Link</label>
                <input type="text" name="cta_link" id="slideCtaLink"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="e.g., /wholesale">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Display Order</label>
                <input type="number" name="order" id="slideOrder" min="0" value="0"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="slideIsActive" value="1" checked
                        style="width: 18px; height: 18px; margin-right: 8px;">
                    <span style="font-size: 14px; font-weight: 500; color: #2c3e50;">Active (Show on wholesale page)</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" style="background: #e5e7eb; color: #6b7280; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    Cancel
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-save"></i> Save Slide
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const slides = @json($slides);

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Slide';
    document.getElementById('slideForm').action = '{{ route("admin.wholesale-page.slides.store") }}';
    document.getElementById('slideMethod').value = 'POST';
    document.getElementById('slideForm').reset();
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('slideImage').required = true;
    document.getElementById('slideModal').style.display = 'flex';
}

function openEditModal(slideId) {
    const slide = slides.find(s => s.id === slideId);
    if (!slide) return;

    document.getElementById('modalTitle').textContent = 'Edit Slide';
    document.getElementById('slideForm').action = `/admin/wholesale-page/slides/${slideId}`;
    document.getElementById('slideMethod').value = 'PUT';
    document.getElementById('slideTitle').value = slide.title;
    document.getElementById('slideDescription').value = slide.description;
    document.getElementById('slideCtaText').value = slide.cta_text || '';
    document.getElementById('slideCtaLink').value = slide.cta_link || '';
    document.getElementById('slideOrder').value = slide.order;
    document.getElementById('slideIsActive').checked = slide.is_active;
    document.getElementById('slideImage').required = false;
    
    const preview = document.getElementById('imagePreview');
    preview.src = `/storage/${slide.image}`;
    document.getElementById('imagePreviewContainer').style.display = 'block';
    
    document.getElementById('slideModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('slideModal').style.display = 'none';
}

function toggleStatus(slideId) {
    if (!confirm('Are you sure you want to toggle the status of this slide?')) return;
    
    fetch(`/admin/wholesale-page/slides/${slideId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteSlide(slideId) {
    if (!confirm('Are you sure you want to delete this slide? This action cannot be undone.')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/wholesale-page/slides/${slideId}`;
    
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

document.getElementById('slideImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagePreview').src = event.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('slideModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
