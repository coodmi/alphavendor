@extends('layouts.dashboard')
@section('title', 'Review Details')
@section('page-title', 'Review Details')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Review Details</h2>
        <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
            ← Back to Reviews
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">

        {{-- Status + Meta --}}
        <div class="px-6 py-4 flex flex-wrap gap-3 items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    {{ $review->status === 'pending'  ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $review->status === 'approved' ? 'bg-green-100 text-green-800'  : '' }}
                    {{ $review->status === 'rejected' ? 'bg-red-100 text-red-800'      : '' }}
                    {{ $review->status === 'reported' ? 'bg-orange-100 text-orange-800': '' }}">
                    {{ ucfirst($review->status) }}
                </span>
                <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="flex gap-2">
                @if($review->status === 'pending')
                    <button onclick="approveReview()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">Approve</button>
                    <button onclick="rejectReview()"  class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">Reject</button>
                @endif
                <button onclick="deleteReview()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">Delete</button>
            </div>
        </div>

        {{-- Product + Customer --}}
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 mb-1 font-medium">Product</p>
                <p class="font-semibold text-gray-800">{{ $review->product->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500 mb-1 font-medium">Customer</p>
                <p class="font-semibold text-gray-800">{{ $review->user->name ?? 'N/A' }}</p>
                <p class="text-gray-500 text-xs">{{ $review->user->email ?? '' }}</p>
            </div>
        </div>

        {{-- Edit Review Content --}}
        <div class="px-6 py-5">
            <h3 class="text-base font-bold text-gray-700 mb-4">Review Content <span class="text-xs text-indigo-600 font-normal">(Admin can edit)</span></h3>
            <form id="editReviewForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Rating</label>
                    <div class="flex gap-1" id="adminRatingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setAdminRating({{ $i }})"
                                class="text-3xl transition admin-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                data-val="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" id="adminRatingInput" value="{{ $review->rating }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Title</label>
                    <input type="text" id="editTitle" value="{{ $review->title }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Comment</label>
                    <textarea id="editComment" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none text-sm resize-none">{{ $review->comment }}</textarea>
                </div>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                    Save Changes
                </button>
            </form>
        </div>

        {{-- Admin Reply --}}
        <div class="px-6 py-5">
            <h3 class="text-base font-bold text-gray-700 mb-4">Admin Reply</h3>
            @if($review->admin_response)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 text-sm text-gray-700">
                    <p>{{ $review->admin_response }}</p>
                    <p class="text-xs text-gray-400 mt-2">Replied {{ $review->admin_responded_at->format('M d, Y H:i') }}</p>
                </div>
            @endif
            <form id="responseForm" class="space-y-3">
                @csrf
                <textarea id="adminResponse" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none text-sm resize-none"
                    placeholder="Write your reply to this review...">{{ $review->admin_response }}</textarea>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
                    {{ $review->admin_response ? 'Update Reply' : 'Post Reply' }}
                </button>
            </form>
        </div>

        {{-- Vendor Reply (read-only for admin) --}}
        @if($review->vendor_reply)
        <div class="px-6 py-5">
            <h3 class="text-base font-bold text-gray-700 mb-3">Seller Reply</h3>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700">
                <p>{{ $review->vendor_reply }}</p>
                <p class="text-xs text-gray-400 mt-2">Replied {{ $review->vendor_replied_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

function setAdminRating(val) {
    document.getElementById('adminRatingInput').value = val;
    document.querySelectorAll('.admin-star').forEach(s => {
        s.classList.toggle('text-yellow-400', parseInt(s.dataset.val) <= val);
        s.classList.toggle('text-gray-300',   parseInt(s.dataset.val) >  val);
    });
}

document.getElementById('editReviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(`/admin/reviews/{{ $review->id }}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            _method: 'PUT',
            rating:  document.getElementById('adminRatingInput').value,
            title:   document.getElementById('editTitle').value,
            comment: document.getElementById('editComment').value,
        })
    }).then(r => r.json()).then(d => {
        if(d.success) { showToast('Review updated!', 'success'); }
        else { showToast(d.message || 'Error', 'error'); }
    }).catch(() => showToast('Request failed', 'error'));
});

document.getElementById('responseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Saving...';
    fetch(`/admin/reviews/{{ $review->id }}/respond`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ admin_response: document.getElementById('adminResponse').value })
    }).then(r => r.json()).then(d => {
        btn.disabled = false;
        btn.textContent = 'Update Reply';
        if(d.success) { showToast('Reply saved!', 'success'); }
        else { showToast(d.message || 'Error saving reply', 'error'); }
    }).catch(() => { btn.disabled = false; showToast('Request failed', 'error'); });
});

function approveReview() {
    if(!confirm('Approve this review?')) return;
    fetch(`/admin/reviews/{{ $review->id }}/approve`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} })
    .then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function rejectReview() {
    if(!confirm('Reject this review?')) return;
    fetch(`/admin/reviews/{{ $review->id }}/reject`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} })
    .then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
}
function deleteReview() {
    if(!confirm('Delete this review? This cannot be undone.')) return;
    fetch(`/admin/reviews/{{ $review->id }}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} })
    .then(r=>r.json()).then(d=>{ if(d.success) window.location.href='{{ route("admin.reviews.index") }}'; });
}

function showToast(msg, type) {
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;padding:12px 20px;border-radius:8px;font-weight:600;color:#fff;background:${type==='success'?'#16a34a':'#dc2626'};box-shadow:0 4px 12px rgba(0,0,0,.15);`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
</script>
@endsection
