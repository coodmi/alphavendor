<div class="mt-4 border border-indigo-100 rounded-xl p-4 bg-indigo-50/40">
    <label class="block text-sm font-semibold text-gray-700 mb-1">
        Product Gallery Images <span class="text-red-500">*</span>
    </label>
    <p class="text-xs text-gray-500 mb-3">Upload 1 to 4 images (JPEG, PNG, GIF, WEBP). Select multiple files at once.</p>
    <p id="galleryLimitHint" class="text-xs text-indigo-600 mb-2 font-medium">0 / 4 images selected</p>
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
const PRODUCT_GALLERY_MIN = 1;
const PRODUCT_GALLERY_MAX = 4;
let galleryMarkedForRemoval = new Set();

function productGalleryImageUrl(path) {
    if (!path) return '';
    return path.startsWith('http') ? path : `/storage/${path}`;
}

function getGalleryExistingCount() {
    return document.querySelectorAll('#galleryExisting [data-gallery-id]').length;
}

function getGalleryRemainingSlots() {
    return PRODUCT_GALLERY_MAX - (getGalleryExistingCount() - galleryMarkedForRemoval.size);
}

function updateGalleryLimitHint(newFileCount = 0) {
    const hint = document.getElementById('galleryLimitHint');
    if (!hint) return;
    const existing = getGalleryExistingCount() - galleryMarkedForRemoval.size;
    const total = existing + newFileCount;
    hint.textContent = `${total} / ${PRODUCT_GALLERY_MAX} images (${PRODUCT_GALLERY_MAX - total} slot${PRODUCT_GALLERY_MAX - total === 1 ? '' : 's'} left)`;
}

function resetProductGallery() {
    galleryMarkedForRemoval = new Set();
    const input = document.getElementById('productGalleryImages');
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
            showToast(`You can add at most ${maxNew} more image(s) (maximum ${PRODUCT_GALLERY_MAX} total).`, 'error');
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

    if (input) {
        input.value = '';
        input.disabled = images.length >= PRODUCT_GALLERY_MAX;
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
    const input = document.getElementById('productGalleryImages');
    const newCount = input?.files?.length || 0;
    const existing = getGalleryExistingCount() - galleryMarkedForRemoval.size;
    const total = existing + newCount;

    if (total < PRODUCT_GALLERY_MIN) {
        if (typeof showToast === 'function') {
            showToast(`Please add at least ${PRODUCT_GALLERY_MIN} product image(s).`, 'error');
        }
        input?.focus();
        return false;
    }
    if (total > PRODUCT_GALLERY_MAX) {
        if (typeof showToast === 'function') {
            showToast(`You can upload a maximum of ${PRODUCT_GALLERY_MAX} product images.`, 'error');
        }
        input?.focus();
        return false;
    }
    return true;
}
</script>
@endpush
@endonce
