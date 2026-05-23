@extends('layouts.dashboard')

@section('title', 'Shipping Settings')
@section('page-title', 'Shipping Settings')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-teal-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <i class="fas fa-truck text-teal-700 mr-3"></i>Shipping Settings
            </h1>
            <p class="text-gray-600 mt-2">District base delivery charges and nationwide extra per KG rate (AR Market BD)</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-teal-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Global Extra Per KG Charge</h2>
            <p class="text-sm text-gray-600 mb-4">First 1kg is included in each district base charge. Additional weight: (total kg − 1) × this rate.</p>
            <form action="{{ route('admin.shipping-settings.update-global') }}" method="POST" class="flex flex-wrap items-end gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Extra Per KG (৳)</label>
                    <input type="number" name="extra_per_kg_charge" step="0.01" min="0" required
                           value="{{ old('extra_per_kg_charge', $settings->extra_per_kg_charge) }}"
                           class="w-40 px-4 py-2 border rounded-lg">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700">
                    Save Global Rate
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">District Base Delivery Charge</h2>
            <p class="text-sm text-gray-600 mb-6">Set base charge per district (includes first 1kg).</p>

            <form action="{{ route('admin.shipping-settings.districts-bulk') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="overflow-x-auto max-h-[600px] overflow-y-auto border rounded-lg">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Division</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">District</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Base Charge (৳)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($districts as $district)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-gray-600">{{ $district->division }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $district->district }}</td>
                                    <td class="px-4 py-2">
                                        <input type="hidden" name="districts[{{ $loop->index }}][id]" value="{{ $district->id }}">
                                        <input type="number" name="districts[{{ $loop->index }}][base_charge]" step="0.01" min="0" required
                                               value="{{ $district->base_charge }}"
                                               class="w-28 px-3 py-1.5 border rounded-lg">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700">
                        Save All District Charges
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
