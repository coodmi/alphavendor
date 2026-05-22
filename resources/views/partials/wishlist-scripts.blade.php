@once
@auth
@push('scripts')
<script>
function updateWishlistBadge(count) {
    document.querySelectorAll('[data-wishlist-count]').forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'flex' : 'none';
    });
}

function toggleWishlist(productId, button) {
    if (!productId || !button) return;

    const icon = button.querySelector('i');
    if (!icon) return;

    const originalClass = icon.className;
    icon.className = 'fas fa-spinner fa-spin';

    fetch(`/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok || !data.success) {
            throw new Error(data.message || 'Failed to update wishlist');
        }

        if (data.inWishlist) {
            icon.className = 'fas fa-heart text-red-500';
            button.classList.add('in-wishlist');
        } else {
            icon.className = 'far fa-heart';
            button.classList.remove('in-wishlist');
        }

        if (typeof data.wishlistCount !== 'undefined') {
            updateWishlistBadge(data.wishlistCount);
        }

        if (typeof showToast === 'function') {
            showToast(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Wishlist error:', error);
        icon.className = originalClass;
        if (typeof showToast === 'function') {
            showToast(error.message || 'Please login to use wishlist', 'error');
        } else {
            alert(error.message || 'Could not update wishlist');
        }
    });
}

function initWishlistButtons(root = document) {
    root.querySelectorAll('[data-wishlist-product]').forEach(button => {
        const productId = button.getAttribute('data-wishlist-product');
        if (!productId) return;

        fetch(`/wishlist/check/${productId}`, {
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            const icon = button.querySelector('i');
            if (!icon) return;
            if (data.inWishlist) {
                icon.className = 'fas fa-heart text-red-500';
                button.classList.add('in-wishlist');
            }
        })
        .catch(() => {});
    });
}

document.addEventListener('DOMContentLoaded', () => initWishlistButtons());
</script>
@endpush
@endauth
@endonce
