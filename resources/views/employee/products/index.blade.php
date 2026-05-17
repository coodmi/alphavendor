@extends('layouts.dashboard')
@section('title', 'Products')
@section('page-title', 'Products Management')
@section('sidebar-menu')
    @include('dashboards.partials.employee-sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Products</h2>
            <p class="text-gray-500 mt-1">Manage all products</p>
        </div>
        @if(auth()->user()->hasPermission('products.add'))
        <a href="{{ route('admin.products') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl font-semibold hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus"></i> Add Product
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Vendor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        @if(auth()->user()->hasAnyPermission(['products.edit','products.delete','products.approve']))
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ str_starts_with($product->image,'http') ? $product->image : asset('storage/'.$product->image) }}"
                                         class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">{{ Str::limit($product->name, 40) }}</div>
                                    <div class="text-xs text-gray-400">SKU: {{ $product->sku ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->vendor->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-teal-600">{{ currency($product->price) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->stock }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        @if(auth()->user()->hasAnyPermission(['products.edit','products.delete','products.approve']))
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(auth()->user()->hasPermission('products.edit'))
                                <a href="{{ route('admin.products') }}?edit={{ $product->id }}"
                                   class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('products.delete'))
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-box text-4xl mb-3"></i>
                            <p>No products found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
