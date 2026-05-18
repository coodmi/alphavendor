<div class="mt-4 border border-indigo-100 rounded-xl p-4 bg-indigo-50/40">
    <label class="block text-sm font-semibold text-gray-700 mb-1">
        Product Gallery Images <span class="text-red-500">*</span>
    </label>
    <p class="text-xs text-gray-500 mb-3">Upload at least 2 images (JPEG, PNG, GIF, WEBP). You can select multiple files at once.</p>
    <input type="file"
           name="gallery_images[]"
           id="productGalleryImages"
           accept="image/jpeg,image/png,image/gif,image/webp"
           multiple
           required
           onchange="previewGalleryFiles(event)"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
    <div id="galleryNewPreview" class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-3 hidden"></div>
    <div id="galleryExistingWrap" class="hidden mt-4">
        <p class="text-xs font-semibold text-gray-600 mb-2">Current gallery images <span class="font-normal text-gray-400">(click × to remove on save)</span></p>
        <div id="galleryExisting" class="grid grid-cols-3 sm:grid-cols-4 gap-2"></div>
        <div id="galleryRemoveInputs"></div>
    </div>
</div>

@once
@push('scripts')
<script>
const PRODUCT_GALLERY_MIN = 2;
let galleryMarkedForRemoval = new Set();

function productGalleryImageUrl(path) {
    if (!path) return '';
    return path.startsWith('http') ? path : `/storage/${path}`;
}

function resetProductGallery() {
    galleryMarkedForRemoval = new Set();
    const input = document.getElementById('productGalleryImages');
    if (input) {
        input.value = '';
        input.setAttribute('required', 'required');
    }
    const newPreview = document.getElementById('galleryNewPreview');
    if (newPreview) {
        newPreview.innerHTML = '';
        newPreview.classList.add('hidden');
    }
    const existingWrap = document.getElementById('galleryExistingWrap');
    if (existingWrap) existingWrap.classList.add('hidden');
    const existing = document.getElementById('galleryExisting');
    if (existing) existing.innerHTML = '';
    const removeInputs = document.getElementById('galleryRemoveInputs');
    if (removeInputs) removeInputs.innerHTML = '';
}

function previewGalleryFiles(event) {
    const container = document.getElementById('galleryNewPreview');
    if (!container) return;
    container.innerHTML = '';
    const files = event.target.files;
    if (!files || files.length === 0) {
        container.classList.add('hidden');
        return;
    }
    container.classList.remove('hidden');
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-20 object-cover rounded-lg border border-gray-200 shadow-sm';
            img.alt = file.name;
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

function renderExistingGallery(product) {
    const wrap = document.getElementById('galleryExistingWrap');
    const container = document.getElementById('galleryExisting');
    const removeInputs = document.getElementById('galleryRemoveInputs');
    if (!wrap || !container || !removeInputs) return;

    galleryMarkedForRemoval = new Set();
    removeInputs.innerHTML = '';
    container.innerHTML = '';

    const images = product.images || [];
    if (images.length === 0) {
        wrap.classList.add('hidden');
        return;
    }

    wrap.classList.remove('hidden');
    images.forEach(img => {
        const box = document.createElement('div');
        box.className = 'relative group';
        box.dataset.galleryId = img.id;
        box.innerHTML = `
            <img src="${productGalleryImageUrl(img.image)}" alt="Gallery" class="w-full h-20 object-cover rounded-lg border border-gray-200">
            <button type="button" onclick="toggleRemoveGalleryImage(${img.id}, this)"
                class="absolute top-1 right-1 w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center opacity-90 hover:bg-red-600"
                title="Remove image">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(box);
    });
}

function toggleRemoveGalleryImage(id, btn) {
    const box = btn.closest('[data-gallery-id]');
    const removeInputs = document.getElementById('galleryRemoveInputs');
    if (!box || !removeInputs) return;

    if (galleryMarkedForRemoval.has(id)) {
        galleryMarkedForRemoval.delete(id);
        box.classList.remove('opacity-40', 'ring-2', 'ring-red-400');
        removeInputs.querySelector(`input[data-remove-id="${id}"]`)?.remove();
    } else {
        galleryMarkedForRemoval.add(id);
        box.classList.add('opacity-40', 'ring-2', 'ring-red-400');
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'remove_gallery_ids[]';
        hidden.value = id;
        hidden.dataset.removeId = id;
        removeInputs.appendChild(hidden);
    }
}

function validateProductGallery() {
    const input = document.getElementById('productGalleryImages');
    const newCount = input?.files?.length || 0;
    const existingTotal = document.querySelectorAll('#galleryExisting [data-gallery-id]').length;
    const remaining = existingTotal - galleryMarkedForRemoval.size;
    const total = remaining + newCount;

    if (total < PRODUCT_GALLERY_MIN) {
        if (typeof showToast === 'function') {
            showToast(`Please upload at least ${PRODUCT_GALLERY_MIN} product images in total.`, 'error');
        }
        input?.focus();
        return false;
    }
    return true;
}
</script>
@endpush
@endonce
