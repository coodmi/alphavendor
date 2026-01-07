@extends('layouts.dashboard')

@section('title', 'Commission Settings')
@section('page-title', 'Commission Settings')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Add New Commission Setting -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Add New Commission Rate</h2>
        <form action="{{ route('admin.commissions.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                    <select name="type" id="commission_type" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-orange-500"
                            onchange="toggleCommissionFields()">
                        <option value="global">Global</option>
                        <option value="category">Category</option>
                        <option value="product">Product</option>
                    </select>
                </div>

                <div id="category_field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="product_field" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select name="product_id" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%) *</label>
                    <input type="number" name="commission_rate" required min="0" max="100" step="0.01"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                        <i class="fas fa-plus mr-2"></i>Add Commission
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Current Commission Settings -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Current Commission Rates</h2>
            <p class="text-sm text-gray-600 mt-1">Priority: Product-specific > Category-specific > Global</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($commissions as $commission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    @if($commission->type === 'global') bg-blue-100 text-blue-800
                                    @elseif($commission->type === 'category') bg-purple-100 text-purple-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($commission->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($commission->type === 'global')
                                    <span class="text-gray-700">All Products</span>
                                @elseif($commission->type === 'category' && $commission->category)
                                    <span class="text-gray-700">{{ $commission->category->name }}</span>
                                @elseif($commission->type === 'product' && $commission->product)
                                    <span class="text-gray-700">{{ $commission->product->name }}</span>
                                @else
                                    <span class="text-red-500">Not Found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.commissions.update', $commission->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="commission_rate" value="{{ $commission->commission_rate }}"
                                           min="0" max="100" step="0.01"
                                           class="w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                                           onchange="this.form.submit()">
                                    <span class="text-gray-600">%</span>
                                    <input type="hidden" name="is_active" value="{{ $commission->is_active ? 1 : 0 }}">
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.commissions.update', $commission->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="commission_rate" value="{{ $commission->commission_rate }}">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1"
                                               {{ $commission->is_active ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                                    </label>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.commissions.destroy', $commission->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this commission setting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No commission settings found. Add one above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commission Calculator -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Commission Calculator</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sale Amount ($)</label>
                <input type="number" id="calc_amount" min="0" step="0.01" value="100"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       oninput="calculateCommission()">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%)</label>
                <input type="number" id="calc_rate" min="0" max="100" step="0.01" value="10"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       oninput="calculateCommission()">
            </div>
            <div class="bg-orange-50 p-4 rounded">
                <p class="text-sm text-gray-600">Platform Commission</p>
                <p class="text-2xl font-bold text-orange-500" id="calc_commission">$10.00</p>
                <p class="text-sm text-gray-600 mt-2">Vendor Receives: <span class="font-semibold" id="calc_vendor">$90.00</span></p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCommissionFields() {
    const type = document.getElementById('commission_type').value;
    document.getElementById('category_field').style.display = type === 'category' ? 'block' : 'none';
    document.getElementById('product_field').style.display = type === 'product' ? 'block' : 'none';
}

function calculateCommission() {
    const amount = parseFloat(document.getElementById('calc_amount').value) || 0;
    const rate = parseFloat(document.getElementById('calc_rate').value) || 0;
    const commission = (amount * rate) / 100;
    const vendor = amount - commission;

    document.getElementById('calc_commission').textContent = '$' + commission.toFixed(2);
    document.getElementById('calc_vendor').textContent = '$' + vendor.toFixed(2);
}
</script>
@endsection
