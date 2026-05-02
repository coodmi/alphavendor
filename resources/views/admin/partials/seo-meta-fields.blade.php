{{-- SEO Meta Fields Partial --}}
{{-- Usage: @include('admin.partials.seo-meta-fields', ['meta' => $page]) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-700 to-slate-800">
        <h3 class="font-bold text-white flex items-center gap-2 text-base">
            <i class="fas fa-search"></i> SEO Meta Tags
            <span class="ml-2 text-xs font-normal text-slate-300 bg-slate-600 px-2 py-0.5 rounded-full">Affects search engine results</span>
        </h3>
    </div>
    <div class="p-6 space-y-5">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                Meta Title
                <span class="ml-1 text-xs font-normal text-gray-400">(recommended: 50–60 characters)</span>
            </label>
            <input type="text" name="meta_title"
                   value="{{ old('meta_title', $meta->meta_title ?? '') }}"
                   maxlength="255"
                   placeholder="e.g. Terms & Conditions | YourSite"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm"
                   oninput="updateCounter(this, 'meta-title-count', 60)">
            <div class="flex justify-between mt-1">
                <p class="text-xs text-gray-400">Leave blank to use the page title</p>
                <span id="meta-title-count" class="text-xs text-gray-400">0 / 60</span>
            </div>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1.5">
                Meta Description
                <span class="ml-1 text-xs font-normal text-gray-400">(recommended: 150–160 characters)</span>
            </label>
            <textarea name="meta_description" rows="3"
                      maxlength="500"
                      placeholder="Brief description of this page for search engines..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm resize-none"
                      oninput="updateCounter(this, 'meta-desc-count', 160)">{{ old('meta_description', $meta->meta_description ?? '') }}</textarea>
            <div class="flex justify-between mt-1">
                <p class="text-xs text-gray-400">Shown in Google search results below the title</p>
                <span id="meta-desc-count" class="text-xs text-gray-400">0 / 160</span>
            </div>
        </div>
    </div>
</div>

<script>
function updateCounter(el, counterId, max) {
    const len = el.value.length;
    const el2 = document.getElementById(counterId);
    if (!el2) return;
    el2.textContent = len + ' / ' + max;
    el2.className = 'text-xs ' + (len > max ? 'text-red-500 font-semibold' : len > max * 0.85 ? 'text-yellow-500' : 'text-gray-400');
}
// Init counters on load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[oninput^="updateCounter"]').forEach(el => el.dispatchEvent(new Event('input')));
});
</script>
