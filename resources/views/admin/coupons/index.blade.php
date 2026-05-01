@extends('layouts.dashboard')

@section('title', 'Coupon Management')
@section('page-title', 'Coupons')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Total Coupons</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_coupons'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-ticket-alt text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Active Coupons</p>
                <h3 class="text-3xl font-bold">{{ $stats['active_coupons'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm mb-1">Expired</p>
                <h3 class="text-3xl font-bold">{{ $stats['expired_coupons'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Total Usage</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_usage'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Coupons Table -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Coupon Management</h2>
            <p class="text-gray-500 mt-1">Create and manage discount coupons</p>
        </div>
        <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all flex items-center gap-2 shadow-lg">
            <i class="fas fa-plus"></i> Add Coupon
        </button>
    </div>

    @if($coupons->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Value</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Usage</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Valid Period</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($coupons as $coupon)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded">{{ $coupon->code }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $coupon->type === 'percentage' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($coupon->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    {{ currency_symbol() }}{{ number_format($coupon->value, 2) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-gray-700">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">
                                @if($coupon->start_date)
                                    <div>From: {{ $coupon->start_date->format('M d, Y') }}</div>
                                @endif
                                @if($coupon->end_date)
                                    <div>To: {{ $coupon->end_date->format('M d, Y') }}</div>
                                @endif
                                @if(!$coupon->start_date && !$coupon->end_date)
                                    <span class="text-gray-500">No expiry</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" {{ $coupon->is_active ? 'checked' : '' }} onchange="toggleCoupon({{ $coupon->id }}, this)">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='editCoupon(@json($coupon))' class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteCoupon({{ $coupon->id }}, '{{ $coupon->code }}')" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $coupons->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-ticket-alt text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-500 text-lg">No coupons found</p>
            <p class="text-gray-400 text-sm mt-1">Click "Add Coupon" to create your first coupon</p>
        </div>
    @endif
</div>

<!-- Add/Edit Modal -->
<div id="couponModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Coupon</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="couponForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code *</label>
                    <input type="text" name="code" id="couponCode" required placeholder="e.g., SAVE20" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 uppercase">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="type" id="couponType" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                    <input type="number" name="value" id="couponValue" required step="0.01" min="0" placeholder="e.g., 20" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Purchase</label>
                    <input type="number" name="min_purchase" id="couponMinPurchase" step="0.01" min="0" placeholder="Optional" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Discount</label>
                    <input type="number" name="max_discount" id="couponMaxDiscount" step="0.01" min="0" placeholder="Optional" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                    <input type="number" name="usage_limit" id="couponUsageLimit" min="1" placeholder="Unlimited" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Per User Limit</label>
                    <input type="number" name="per_user_limit" id="couponPerUserLimit" min="1" placeholder="Unlimited" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="datetime-local" name="start_date" id="couponStartDate" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="datetime-local" name="end_date" id="couponEndDate" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="couponDescription" rows="3" placeholder="Optional description" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <!-- Product/Category Restriction -->
            <div class="mt-5 p-4 bg-purple-50 rounded-lg border border-purple-100">
                <p class="text-sm font-semibold text-purple-700 mb-3"><i class="fas fa-tag mr-1"></i> Restrict to Specific Product or Category (optional)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specific Product</label>
                        <select name="product_id" id="couponProductId" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="">All Products</option>
                            @foreach(\App\Models\Product::where('status','active')->orderBy('name')->get() as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specific Category</label>
                        <select name="category_id" id="couponCategoryId" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::whereNull('vendor_id')->where('is_active',true)->orderBy('name')->get() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Leave both empty to apply to all products. If both are set, product takes priority.</p>
            </div>

            <div class="mt-5">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="couponStatus" value="1" checked class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                    <span class="ml-3 text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end mt-6">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Coupon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Coupon</h3>
            <p class="text-gray-500">Are you sure you want to delete coupon "<span id="deleteCouponCode" class="font-semibold text-gray-700"></span>"?</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let editingCouponId = null;

function openAddModal() {
    editingCouponId = null;
    document.getElementById('modalTitle').textContent = 'Add Coupon';
    document.getElementById('couponForm').action = '{{ route('admin.coupons.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('couponForm').reset();
    document.getElementById('couponStatus').checked = true;
    document.getElementById('couponModal').classList.remove('hidden');
}

function editCoupon(coupon) {
    editingCouponId = coupon.id;
    document.getElementById('modalTitle').textContent = 'Edit Coupon';
    document.getElementById('couponForm').action = `/admin/coupons/${coupon.id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('couponCode').value = coupon.code;
    document.getElementById('couponType').value = coupon.type;
    document.getElementById('couponValue').value = coupon.value;
    document.getElementById('couponMinPurchase').value = coupon.min_purchase || '';
    document.getElementById('couponMaxDiscount').value = coupon.max_discount || '';
    document.getElementById('couponUsageLimit').value = coupon.usage_limit || '';
    document.getElementById('couponPerUserLimit').value = coupon.per_user_limit || '';
    document.getElementById('couponStartDate').value = coupon.start_date ? coupon.start_date.replace(' ', 'T').substring(0, 16) : '';
    document.getElementById('couponEndDate').value = coupon.end_date ? coupon.end_date.replace(' ', 'T').substring(0, 16) : '';
    document.getElementById('couponDescription').value = coupon.description || '';
    document.getElementById('couponStatus').checked = coupon.is_active;
    
    document.getElementById('couponModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('couponModal').classList.add('hidden');
}

function deleteCoupon(id, code) {
    document.getElementById('deleteCouponCode').textContent = code;
    document.getElementById('deleteForm').action = `/admin/coupons/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

async function toggleCoupon(id, checkbox) {
    try {
        const response = await fetch(`/admin/coupons/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            checkbox.checked = !checkbox.checked;
            showToast('Failed to update status', 'error');
        }
    } catch (error) {
        checkbox.checked = !checkbox.checked;
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} z-50`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection
