<div class="grid grid-cols-1 md:grid-cols-2 gap-5 product-shipping-charges-block">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Shipping — Inside Dhaka <span class="text-gray-400 font-normal">(Optional)</span>
        </label>
        <input type="number" name="shipping_charge_inside_dhaka" id="productShippingInsideDhaka"
               min="0" step="0.01" placeholder="e.g. 60"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <p class="text-xs text-gray-500 mt-1">Leave empty to hide on product page. Use 0 for free shipping inside Dhaka.</p>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Shipping — Outside Dhaka <span class="text-gray-400 font-normal">(Optional)</span>
        </label>
        <input type="number" name="shipping_charge_outside_dhaka" id="productShippingOutsideDhaka"
               min="0" step="0.01" placeholder="e.g. 120"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <p class="text-xs text-gray-500 mt-1">Leave empty to hide on product page. Use 0 for free shipping outside Dhaka.</p>
    </div>
</div>
