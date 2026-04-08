@extends('layouts.dashboard')

@section('title', 'Offers Management')
@section('page-title', 'Offers')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Offers Management</h2>
            <p style="color: #7f8c8d;">Create and manage special offers for products</p>
        </div>
        <button onclick="openAddModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);">
            <i class="fas fa-plus"></i> Add New Offer
        </button>
    </div>
</div>

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<!-- Offers Grid -->
@if($offers->count() > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
    @foreach($offers as $offer)
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; border-left: 4px solid {{ $offer->badge_color }};">
        <div style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <h3 style="font-size: 20px; color: #2c3e50; margin-bottom: 5px; display: flex; align-items: center; gap: 10px;">
                        @if($offer->icon)
                        <i class="{{ $offer->icon }}" style="color: {{ $offer->badge_color }};"></i>
                        @endif
                        {{ $offer->name }}
                    </h3>
                    @if($offer->badge_text)
                    <span style="display: inline-block; padding: 4px 12px; background: {{ $offer->badge_color }}; color: white; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ $offer->badge_text }}
                    </span>
                    @endif
                </div>
                <div style="display: flex; gap: 8px;">
                    <button onclick="editOffer({{ $offer->id }})" style="background: #3498db; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.offers.destroy', $offer->id) }}" style="display: inline;" onsubmit="return confirm('Delete this offer?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #e74c3c; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            @if($offer->description)
            <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 15px;">{{ $offer->description }}</p>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <div style="background: #f8f9fa; padding: 10px; border-radius: 6px;">
                    <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Status</div>
                    <div style="font-weight: 600; color: {{ $offer->isCurrentlyActive() ? '#10b981' : '#e74c3c' }};">
                        {{ $offer->isCurrentlyActive() ? 'Active' : 'Inactive' }}
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 10px; border-radius: 6px;">
                    <div style="font-size: 12px; color: #7f8c8d; margin-bottom: 4px;">Products</div>
                    <div style="font-weight: 600; color: #2c3e50;">{{ $offer->products()->count() }}</div>
                </div>
            </div>

            @if($offer->start_date || $offer->end_date)
            <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 6px; font-size: 13px;">
                <i class="fas fa-calendar"></i>
                @if($offer->start_date)
                    <strong>From:</strong> {{ $offer->start_date->format('M d, Y') }}
                @endif
                @if($offer->end_date)
                    <strong>To:</strong> {{ $offer->end_date->format('M d, Y') }}
                @endif
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 60px 20px; text-align: center;">
    <i class="fas fa-tags" style="font-size: 64px; color: #d1d5db; margin-bottom: 16px;"></i>
    <h3 style="font-size: 20px; color: #6b7280; margin-bottom: 8px;">No Offers Yet</h3>
    <p style="color: #9ca3af; margin-bottom: 20px;">Create your first offer to promote products</p>
    <button onclick="openAddModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
        <i class="fas fa-plus"></i> Add First Offer
    </button>
</div>
@endif

<!-- Add/Edit Modal -->
<div id="offerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modalTitle" style="font-size: 20px; color: #2c3e50; margin: 0;">Add New Offer</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="offerForm" method="POST" style="padding: 25px;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Offer Name *</label>
                <input type="text" name="name" id="offerName" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                <textarea name="description" id="offerDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Badge Text</label>
                    <input type="text" name="badge_text" id="offerBadgeText" placeholder="e.g., 50% OFF" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Badge Color</label>
                    <input type="color" name="badge_color" id="offerBadgeColor" value="#e74c3c" style="width: 100%; height: 45px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Icon (Font Awesome class)</label>
                <input type="text" name="icon" id="offerIcon" placeholder="e.g., fas fa-fire" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <small style="color: #7f8c8d;">Visit <a href="https://fontawesome.com/icons" target="_blank" style="color: #3498db;">Font Awesome</a> for icon classes</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Start Date</label>
                    <input type="date" name="start_date" id="offerStartDate" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">End Date</label>
                    <input type="date" name="end_date" id="offerEndDate" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Sort Order</label>
                <input type="number" name="sort_order" id="offerSortOrder" value="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="offerIsActive" value="1" checked style="margin-right: 8px; width: 18px; height: 18px;">
                    <span style="color: #2c3e50;">Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                    <i class="fas fa-save"></i> Save Offer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const offers = @json($offers);

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Offer';
    document.getElementById('offerForm').action = '{{ route('admin.offers.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('offerForm').reset();
    document.getElementById('offerBadgeColor').value = '#e74c3c';
    document.getElementById('offerIsActive').checked = true;
    document.getElementById('offerModal').style.display = 'flex';
}

function editOffer(id) {
    const offer = offers.find(o => o.id === id);
    if (!offer) return;

    document.getElementById('modalTitle').textContent = 'Edit Offer';
    document.getElementById('offerForm').action = `/admin/offers/${id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('offerName').value = offer.name;
    document.getElementById('offerDescription').value = offer.description || '';
    document.getElementById('offerBadgeText').value = offer.badge_text || '';
    document.getElementById('offerBadgeColor').value = offer.badge_color || '#e74c3c';
    document.getElementById('offerIcon').value = offer.icon || '';
    document.getElementById('offerStartDate').value = offer.start_date || '';
    document.getElementById('offerEndDate').value = offer.end_date || '';
    document.getElementById('offerSortOrder').value = offer.sort_order || 0;
    document.getElementById('offerIsActive').checked = offer.is_active;
    document.getElementById('offerModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('offerModal').style.display = 'none';
}

// Close modal on outside click
document.getElementById('offerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
