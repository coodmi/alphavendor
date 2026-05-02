@extends('layouts.dashboard')
@section('title', 'Advance Payment Settings')
@section('page-title', 'Advance Payment Settings')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Advance Payment Settings</h2>
            <p class="text-gray-500 text-sm mt-1">Configure the advance percentage for Wholesale & Import orders</p>
        </div>
        <a href="{{ route('admin.advance-payments.index') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
            <i class="fas fa-list"></i> View All Payments
        </a>
    </div>

    @if(session('success'))
    <div class="mb-5 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-green-600"></i>
        <span class="text-green-800 font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Live Preview Card --}}
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white mb-6 shadow-lg">
        <p class="text-blue-100 text-sm mb-1">Current Advance Requirement</p>
        <div class="flex items-end gap-3">
            <span class="text-5xl font-black" id="preview-pct">{{ $settings->advance_percentage }}</span>
            <span class="text-2xl font-bold mb-1">%</span>
        </div>
        <p class="text-blue-100 text-sm mt-2">
            Example: Order of <strong>৳100,000</strong> →
            Advance: <strong id="preview-advance">৳{{ number_format(100000 * $settings->advance_percentage / 100, 2) }}</strong> |
            Due: <strong id="preview-due">৳{{ number_format(100000 - (100000 * $settings->advance_percentage / 100), 2) }}</strong>
        </p>
    </div>

    <form action="{{ route('admin.advance-payments.settings.update') }}" method="POST"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-percentage text-blue-600"></i> Configuration
            </h3>
        </div>

        <div class="p-6 space-y-6">

            {{-- Advance Percentage --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">
                    Advance Percentage (%) <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" name="advance_percentage" id="advance_percentage"
                           value="{{ old('advance_percentage', $settings->advance_percentage) }}"
                           min="1" max="100" step="0.01" required
                           oninput="updatePreview(this.value)"
                           class="w-40 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-bold text-center @error('advance_percentage') border-red-400 @enderror">
                    <span class="text-2xl font-bold text-gray-400">%</span>
                    <span class="text-sm text-gray-500">of total order amount</span>
                </div>
                @error('advance_percentage')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Enter a value between 1 and 100</p>
            </div>

            {{-- Mandatory Toggle --}}
            <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <input type="checkbox" name="is_mandatory" id="is_mandatory" value="1"
                       {{ old('is_mandatory', $settings->is_mandatory) ? 'checked' : '' }}
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <div>
                    <label for="is_mandatory" class="text-sm font-bold text-gray-700 cursor-pointer">
                        Mandatory for Wholesale & Import Orders
                    </label>
                    <p class="text-xs text-gray-500 mt-0.5">When enabled, customers must pay the advance before the order is confirmed</p>
                </div>
            </div>

            {{-- Description shown to customer --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">
                    Message shown to Customer
                </label>
                <textarea name="description" rows="3"
                          placeholder="e.g. An advance payment is required to confirm your order."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none">{{ old('description', $settings->description) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">This message appears in the advance payment modal on the product page</p>
            </div>

        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                           text-white rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </form>

    {{-- Formula explanation --}}
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-5">
        <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <i class="fas fa-calculator text-gray-500"></i> Calculation Formula
        </h4>
        <div class="font-mono text-sm text-gray-600 space-y-1">
            <p>advance_amount = (total_order_amount × advance_percentage) / 100</p>
            <p>due_amount = total_order_amount − advance_amount</p>
        </div>
    </div>
</div>

<script>
function updatePreview(val) {
    const pct = parseFloat(val) || 0;
    const total = 100000;
    const advance = total * pct / 100;
    const due = total - advance;
    document.getElementById('preview-pct').textContent = pct;
    document.getElementById('preview-advance').textContent = '৳' + advance.toLocaleString('en-BD', {minimumFractionDigits:2});
    document.getElementById('preview-due').textContent = '৳' + due.toLocaleString('en-BD', {minimumFractionDigits:2});
}
</script>
@endsection
