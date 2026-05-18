@extends('layouts.dashboard')
@section('title', 'Send Reminder')
@section('page-title', 'Send Reminder')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reminders.index') }}"
           class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Send Reminder</h2>
            <p class="text-gray-500 text-sm">Compose and send a notice to sellers</p>
        </div>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm space-y-1">
        @foreach($errors->all() as $error)
            <p><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="{{ route('admin.reminders.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. Product Listing Policy Update"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-400 @enderror">
        </div>

        {{-- Message --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Message <span class="text-red-500">*</span>
            </label>
            <textarea name="message" rows="5"
                      placeholder="Write your reminder message here..."
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Max 5000 characters</p>
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Notice Type <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach(['info' => ['bg-blue-50 border-blue-300 text-blue-700', 'fa-info-circle', 'Info'],
                           'warning' => ['bg-yellow-50 border-yellow-300 text-yellow-700', 'fa-exclamation-triangle', 'Warning'],
                           'error' => ['bg-red-50 border-red-300 text-red-700', 'fa-times-circle', 'Error'],
                           'success' => ['bg-green-50 border-green-300 text-green-700', 'fa-check-circle', 'Success']] as $val => $cfg)
                <label class="type-card cursor-pointer border-2 rounded-xl p-3 text-center transition
                    {{ old('type', 'warning') === $val ? $cfg[0] . ' border-2' : 'border-gray-200 hover:border-gray-300' }}"
                    data-type="{{ $val }}" data-classes="{{ $cfg[0] }}">
                    <input type="radio" name="type" value="{{ $val }}" class="sr-only"
                           {{ old('type', 'warning') === $val ? 'checked' : '' }}>
                    <i class="fas {{ $cfg[1] }} text-xl mb-1 block"></i>
                    <span class="text-xs font-semibold">{{ $cfg[2] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Recipients --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Send To <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                @foreach(['all' => ['fa-users', 'All Sellers', 'Everyone (retailer, wholesaler, exporter, importer)'],
                           'role' => ['fa-user-tag', 'By Role', 'Select a specific seller role'],
                           'specific' => ['fa-user', 'Specific Seller', 'Choose one or more sellers']] as $val => $cfg)
                <label class="recipient-card cursor-pointer border-2 rounded-xl p-4 transition
                    {{ old('recipient_type', 'specific') === $val ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}"
                    data-recipient="{{ $val }}">
                    <input type="radio" name="recipient_type" value="{{ $val }}" class="sr-only"
                           {{ old('recipient_type', 'specific') === $val ? 'checked' : '' }}>
                    <div class="flex items-start gap-3">
                        <i class="fas {{ $cfg[0] }} text-indigo-500 mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $cfg[1] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $cfg[2] }}</p>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            {{-- Role selector --}}
            <div id="roleSelector" class="{{ old('recipient_type') === 'role' ? '' : 'hidden' }}">
                <label class="block text-xs font-medium text-gray-500 mb-1">Select Role</label>
                <select name="recipient_role"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Choose Role --</option>
                    <option value="retailer"   {{ old('recipient_role') === 'retailer'   ? 'selected' : '' }}>Retailer</option>
                    <option value="wholesaler" {{ old('recipient_role') === 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                    <option value="exporter"   {{ old('recipient_role') === 'exporter'   ? 'selected' : '' }}>Exporter</option>
                    <option value="importer"   {{ old('recipient_role') === 'importer'   ? 'selected' : '' }}>Importer</option>
                </select>
            </div>

            {{-- Specific seller selector --}}
            <div id="specificSelector" class="{{ old('recipient_type', 'specific') === 'specific' ? '' : 'hidden' }}">
                <label class="block text-xs font-medium text-gray-500 mb-1">Select Seller(s)</label>
                <div class="relative">
                    <input type="text" id="sellerSearch" placeholder="Search by name or email..."
                           class="w-full border border-gray-300 rounded-t-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="border border-t-0 border-gray-300 rounded-b-xl max-h-56 overflow-y-auto">
                    @foreach($sellers as $seller)
                    <label class="seller-item flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0"
                           data-name="{{ strtolower($seller->name) }}" data-email="{{ strtolower($seller->email) }}">
                        <input type="checkbox" name="recipient_ids[]" value="{{ $seller->id }}"
                               class="rounded border-gray-300 text-indigo-600"
                               {{ in_array($seller->id, old('recipient_ids', [])) ? 'checked' : '' }}>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $seller->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $seller->email }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $seller->role === 'retailer' ? 'bg-teal-100 text-teal-700' :
                               ($seller->role === 'wholesaler' ? 'bg-blue-100 text-blue-700' :
                               ($seller->role === 'exporter' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700')) }}">
                            {{ ucfirst($seller->role) }}
                        </span>
                    </label>
                    @endforeach
                    @if($sellers->isEmpty())
                    <p class="text-center text-gray-400 text-sm py-6">No sellers found</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.reminders.index') }}"
               class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                <i class="fas fa-paper-plane"></i> Send Reminder
            </button>
        </div>
    </form>
</div>

<script>
// Type card selection
document.querySelectorAll('.type-card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('.type-card').forEach(c => {
            c.className = c.className.replace(/bg-\S+|border-\S+|text-\S+/g, '').trim();
            c.classList.add('border-gray-200', 'hover:border-gray-300');
        });
        const classes = card.dataset.classes.split(' ');
        card.classList.remove('border-gray-200', 'hover:border-gray-300');
        classes.forEach(cls => card.classList.add(cls));
        card.querySelector('input').checked = true;
    });
});

// Recipient type selection
document.querySelectorAll('.recipient-card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('.recipient-card').forEach(c => {
            c.classList.remove('border-indigo-500', 'bg-indigo-50');
            c.classList.add('border-gray-200');
        });
        card.classList.add('border-indigo-500', 'bg-indigo-50');
        card.classList.remove('border-gray-200');
        card.querySelector('input').checked = true;

        const type = card.dataset.recipient;
        document.getElementById('roleSelector').classList.toggle('hidden', type !== 'role');
        document.getElementById('specificSelector').classList.toggle('hidden', type !== 'specific');
    });
});

// Seller search filter
document.getElementById('sellerSearch').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.seller-item').forEach(item => {
        const match = item.dataset.name.includes(q) || item.dataset.email.includes(q);
        item.style.display = match ? '' : 'none';
    });
});
</script>
@endsection
