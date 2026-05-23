<div class="grid grid-cols-1 md:grid-cols-3 gap-5 product-import-delivery-block">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Country <span class="text-red-500">*</span>
        </label>
        <input type="text" name="import_country" id="productImportCountry" required maxlength="100"
               placeholder="e.g. China"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Product Weight (kg) <span class="text-red-500">*</span>
        </label>
        <input type="number" name="weight_kg" id="productWeightKg" min="0.001" step="0.001" required
               placeholder="e.g. 3"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Bangladesh Import Cost (৳) <span class="text-red-500">*</span>
        </label>
        <input type="number" name="bangladesh_import_cost" id="productBangladeshImportCost" min="0" step="0.01" required
               placeholder="e.g. 900"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
        <p class="text-xs text-gray-500 mt-1">Fixed cost to bring product to Bangladesh (once per product per order).</p>
    </div>
</div>
