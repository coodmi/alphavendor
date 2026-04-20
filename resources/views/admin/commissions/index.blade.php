@extends('layouts.dashboard')

@section('title', 'Commission Settings')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Commission Settings</h1>
        <p class="text-gray-100 mt-2">Manage category-based and COD commission rates</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- COD Commission Settings -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">COD Commission Settings</h2>
        <p class="text-gray-100 mb-4">Set commission rate for Cash on Delivery orders</p>
        
        <form action="{{ route('admin.commissions.cod.update') }}" method="POST" class="max-w-md">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-white font-medium mb-2">COD Commission Rate (%)</label>
                <input type="number" 
                       name="commission_rate" 
                       step="0.01" 
                       min="0" 
                       max="100"
                       value="{{ $codCommission->commission_rate ?? 0 }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                       required>
                <p class="text-sm text-gray-200 mt-1">Applied to: (Order Amount - Delivery Charge) × Rate</p>
            </div>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ ($codCommission && $codCommission->is_active) ? 'checked' : '' }}
                           class="mr-2">
                    <span class="text-white">Enable COD Commission</span>
                </label>
            </div>
            
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Update COD Commission
            </button>
        </form>
    </div>


    <!-- Category-Based Commission Settings -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Category-Based Commission Rates</h2>
        <p class="text-gray-100 mb-6">Set different commission rates for each seller type per category</p>
        
        <!-- Add/Update Commission Form -->
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-4">Add/Update Commission Rates</h3>
            <form action="{{ route('admin.commissions.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-white font-medium mb-2">Category</label>
                        <select name="category_id" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-white font-medium mb-2">Retail Seller (%)</label>
                        <input type="number" 
                               name="retailer_rate" 
                               step="0.01" 
                               min="0" 
                               max="100"
                               class="w-full px-4 py-2 border rounded-lg"
                               placeholder="8.00"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-white font-medium mb-2">Wholesale Seller (%)</label>
                        <input type="number" 
                               name="wholesaler_rate" 
                               step="0.01" 
                               min="0" 
                               max="100"
                               class="w-full px-4 py-2 border rounded-lg"
                               placeholder="5.00"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-white font-medium mb-2">Importer (%)</label>
                        <input type="number" 
                               name="importer_rate" 
                               step="0.01" 
                               min="0" 
                               max="100"
                               class="w-full px-4 py-2 border rounded-lg"
                               placeholder="6.00"
                               required>
                    </div>
                </div>
                
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Save Commission Rates
                </button>
            </form>
        </div>
        
        <!-- Existing Commission Rates Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">Retail Seller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">Wholesale Seller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">Importer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($commissions as $categoryId => $categoryCommissions)
                        @php
                            $category = $categories->firstWhere('id', $categoryId);
                            $retailer = $categoryCommissions->firstWhere('seller_type', 'retailer');
                            $wholesaler = $categoryCommissions->firstWhere('seller_type', 'wholesaler');
                            $importer = $categoryCommissions->firstWhere('seller_type', 'importer');
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-white">{{ $category->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-white">{{ $retailer->commission_rate ?? 'N/A' }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-white">{{ $wholesaler->commission_rate ?? 'N/A' }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-white">{{ $importer->commission_rate ?? 'N/A' }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($retailer && $retailer->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-white">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="editCategory({{ $categoryId }}, '{{ $category->name ?? '' }}', {{ $retailer->commission_rate ?? 0 }}, {{ $wholesaler->commission_rate ?? 0 }}, {{ $importer->commission_rate ?? 0 }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-200">
                                No commission rates configured yet. Add your first commission rate above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function editCategory(categoryId, categoryName, retailerRate, wholesalerRate, importerRate) {
    // Pre-fill the form with existing values
    document.querySelector('select[name="category_id"]').value = categoryId;
    document.querySelector('input[name="retailer_rate"]').value = retailerRate;
    document.querySelector('input[name="wholesaler_rate"]').value = wholesalerRate;
    document.querySelector('input[name="importer_rate"]').value = importerRate;
    
    // Scroll to form
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
}
</script>
@endsection
