@php
    $galleryMin = $galleryMin ?? 1;
    $galleryMax = $galleryMax ?? 4;
@endphp
<div class="mt-4 border border-indigo-100 rounded-xl p-4 bg-indigo-50/40 product-gallery-block"
     data-gallery-min="{{ $galleryMin }}"
     data-gallery-max="{{ $galleryMax }}">
    <label class="block text-sm font-semibold text-gray-700 mb-1">
        Product Images <span class="text-red-500">*</span>
    </label>
    <p class="text-xs text-gray-500 mb-3">Upload {{ $galleryMin }} to {{ $galleryMax }} images (JPEG, PNG, GIF, WEBP). Select multiple files at once.</p>
    <p id="galleryLimitHint" class="text-xs text-indigo-600 mb-2 font-medium">0 / {{ $galleryMax }} images selected</p>
    <input type="file"
           name="gallery_images[]"
           id="productGalleryImages"
           accept="image/jpeg,image/png,image/gif,image/webp"
           multiple
           required
           onchange="previewGalleryFiles(event)"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
    <div id="galleryNewPreview" class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 hidden"></div>
    <div id="galleryExistingWrap" class="hidden mt-4">
        <p class="text-xs font-semibold text-gray-600 mb-2">Current gallery images <span class="font-normal text-gray-400">(click × to remove on save)</span></p>
        <div id="galleryExisting" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
        <div id="galleryRemoveInputs"></div>
    </div>
</div>

@once
@push('scripts')
<script>
let galleryMarkedForRemoval = new Set();

function getGalleryConfig() {
    const block = document.querySelector('.product-gallery-block');
    return {
        min: parseInt(block?.dataset.galleryMin || '1', 10),
        max: parseInt(block?.dataset.galleryMax || '4', 10),
    };
}

function productGalleryImageUrl(path) {
    if (!path) return '';
    return path.startsWith('http') ? path : `/storage/${path}`;
}

function getGalleryExistingCount() {
    const rows = document.querySelectorAll('#galleryExisting [data-gallery-id]').length;
    const legacy = document.querySelectorAll('#galleryExisting [data-gallery-legacy]').length;
    return rows + legacy;
}

function hasLegacyMainImagePreview() {
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('previewImg');
    return !!(preview && !preview.classList.contains('hidden') && img?.src);
}

function isEditingProduct() {
    return typeof editingProductId !== 'undefined' && editingProductId;
}

function editProductFromData(button) {
    try {
        const raw = button.getAttribute('data-product');
        if (!raw) {
            throw new Error('Missing product data');
        }
        editProduct(JSON.parse(raw));
    } catch (error) {
        console.error('editProductFromData:', error);
        if (typeof showToast === 'function') {
            showToast('Could not load this product for editing. Please refresh and try again.', 'error');
        }
    }
}

async function parseSellerProductResponse(response) {
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
        const message = data.message
            || (data.errors && Object.values(data.errors).flat().join(' '))
            || 'An error occurred while saving the product';
        throw new Error(message);
    }
    return data;
}

function getGalleryRemainingSlots() {
    const { max } = getGalleryConfig();
    return max - (getGalleryExistingCount() - galleryMarkedForRemoval.size);
}

function updateGalleryLimitHint(newFileCount = 0) {
    const hint = document.getElementById('galleryLimitHint');
    if (!hint) return;
    const { min, max } = getGalleryConfig();
    const existing = getGalleryExistingCount() - galleryMarkedForRemoval.size;
    const total = existing + newFileCount;
    const left = max - total;
    hint.textContent = `${total} / ${max} images (${left} slot${left === 1 ? '' : 's'} left, minimum ${min} required)`;
}

function resetProductGallery() {
    galleryMarkedForRemoval = new Set();
    const input = document.getElementById('productGalleryImages');
    const { min } = getGalleryConfig();
    if (input) {
        input.value = '';
        input.setAttribute('required', 'required');
        input.disabled = false;
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
    updateGalleryLimitHint(0);
}

function previewGalleryFiles(event) {
    const input = event.target;
    const container = document.getElementById('galleryNewPreview');
    if (!container || !input) return;

    const maxNew = getGalleryRemainingSlots();
    let files = Array.from(input.files || []);

    if (files.length > maxNew) {
        if (typeof showToast === 'function') {
            const { max } = getGalleryConfig();
            showToast(`You can add at most ${maxNew} more image(s) (maximum ${max} total).`, 'error');
        }
        files = files.slice(0, maxNew);
    }

    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    input.files = dt.files;

    container.innerHTML = '';
    if (files.length === 0) {
        container.classList.add('hidden');
        updateGalleryLimitHint(0);
        return;
    }

    container.classList.remove('hidden');
    files.forEach(file => {
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

    updateGalleryLimitHint(files.length);

    if (getGalleryRemainingSlots() <= 0) {
        input.disabled = true;
    }
}

function renderExistingGallery(product) {
    const wrap = document.getElementById('galleryExistingWrap');
    const container = document.getElementById('galleryExisting');
    const removeInputs = document.getElementById('galleryRemoveInputs');
    const input = document.getElementById('productGalleryImages');
    if (!wrap || !container || !removeInputs) return;

    galleryMarkedForRemoval = new Set();
    removeInputs.innerHTML = '';
    container.innerHTML = '';

    const images = product.images || [];
    if (images.length === 0 && product.image) {
        wrap.classList.remove('hidden');
        const box = document.createElement('div');
        box.className = 'relative group';
        box.dataset.galleryLegacy = '1';
        box.innerHTML = `
            <img src="${productGalleryImageUrl(product.image)}" alt="Main product image" class="w-full h-20 object-cover rounded-lg border border-gray-200">
            <span class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-gray-800/80 text-white text-[10px]">Main image</span>
        `;
        container.appendChild(box);
        if (input) {
            input.value = '';
            input.removeAttribute('required');
            input.disabled = false;
        }
        updateGalleryLimitHint(0);
        return;
    }

    if (images.length === 0) {
        wrap.classList.add('hidden');
        if (input) {
            input.disabled = false;
            input.value = '';
        }
        updateGalleryLimitHint(0);
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

    const { max } = getGalleryConfig();
    if (input) {
        input.value = '';
        input.disabled = images.length >= max;
    }
    updateGalleryLimitHint(0);
}

function toggleRemoveGalleryImage(id, btn) {
    const box = btn.closest('[data-gallery-id]');
    const removeInputs = document.getElementById('galleryRemoveInputs');
    const input = document.getElementById('productGalleryImages');
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

    if (input) {
        const slots = getGalleryRemainingSlots();
        input.disabled = slots <= 0;
        if (slots > 0 && input.files.length === 0) {
            input.removeAttribute('required');
        }
    }
    updateGalleryLimitHint(input?.files?.length || 0);
}

function validateProductGallery() {
    const { min, max } = getGalleryConfig();
    const input = document.getElementById('productGalleryImages');
    const newCount = input?.files?.length || 0;
    const existing = getGalleryExistingCount() - galleryMarkedForRemoval.size;
    const total = existing + newCount;

    if (total < min) {
        if (isEditingProduct() && (existing > 0 || hasLegacyMainImagePreview())) {
            return true;
        }
        if (typeof showToast === 'function') {
            showToast(`Please add at least ${min} product image(s).`, 'error');
        }
        input?.focus();
        return false;
    }
    if (total > max) {
        if (typeof showToast === 'function') {
            showToast(`You can upload a maximum of ${max} product images.`, 'error');
        }
        input?.focus();
        return false;
    }
    return true;
}
</script>
@endpush
@endonce
