@extends('layouts.dashboard')
@section('title', 'Delivery Penalty Rules')
@section('page-title', 'Delivery Penalty Rules')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Delivery Penalty Rules</h2>
            <p class="text-gray-500 text-sm mt-1">Order Confirmed হওয়ার পর নির্দিষ্ট দিনের মধ্যে Delivered না হলে seller এর wallet থেকে জরিমানা কাটা হবে</p>
        </div>
        <form action="{{ route('admin.delivery-penalty.run-now') }}" method="POST"
              onsubmit="return confirm('এখনই penalty check চালাবেন?')">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-sm font-semibold transition">
                <i class="fas fa-play"></i> Run Check Now
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_penalties'] }}</p>
                <p class="text-sm text-gray-500">মোট জরিমানা প্রয়োগ</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="fas fa-taka-sign text-orange-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($stats['total_amount'], 0) }}</p>
                <p class="text-sm text-gray-500">মোট জরিমানার পরিমাণ</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_orders'] }}</p>
                <p class="text-sm text-gray-500">Pending (penalty হয়নি)</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Add New Rule --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-4">
                <i class="fas fa-plus-circle text-indigo-500 mr-2"></i>নতুন Rule যোগ করুন
            </h3>
            <form action="{{ route('admin.delivery-penalty.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            কত দিন পর <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="days_threshold" min="1" max="365"
                                   placeholder="যেমন: 3"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-14">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">দিন</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            জরিমানার পরিমাণ <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-medium">৳</span>
                            <input type="number" name="penalty_amount" min="1" step="0.01"
                                   placeholder="যেমন: 50"
                                   class="w-full border border-gray-300 rounded-xl pl-8 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">বিবরণ (ঐচ্ছিক)</label>
                    <input type="text" name="description" placeholder="যেমন: ৩ দিনের বেশি দেরিতে ডেলিভারি"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="is_active_new" checked
                           class="rounded border-gray-300 text-indigo-600">
                    <label for="is_active_new" class="text-sm text-gray-700">সক্রিয় রাখুন</label>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-plus mr-1"></i> Rule যোগ করুন
                </button>
            </form>
        </div>

        {{-- Existing Rules --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-4">
                <i class="fas fa-list text-indigo-500 mr-2"></i>বর্তমান Rules
            </h3>
            @if($rules->count())
            <div class="space-y-3">
                @foreach($rules as $rule)
                <div class="border border-gray-200 rounded-xl p-4 {{ $rule->is_active ? '' : 'opacity-50' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg font-bold text-gray-800">{{ $rule->days_threshold }} দিন</span>
                                <span class="text-gray-400">→</span>
                                <span class="text-lg font-bold text-red-600">৳{{ number_format($rule->penalty_amount, 0) }}</span>
                                @if(!$rule->is_active)
                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">নিষ্ক্রিয়</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full">সক্রিয়</span>
                                @endif
                            </div>
                            @if($rule->description)
                            <p class="text-xs text-gray-500">{{ $rule->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            {{-- Edit button --}}
                            <button onclick="openEdit({{ $rule->id }}, {{ $rule->days_threshold }}, {{ $rule->penalty_amount }}, '{{ addslashes($rule->description ?? '') }}', {{ $rule->is_active ? 'true' : 'false' }})"
                                    class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            {{-- Toggle --}}
                            <form action="{{ route('admin.delivery-penalty.toggle', $rule) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="p-1.5 {{ $rule->is_active ? 'text-yellow-500 hover:bg-yellow-50' : 'text-green-500 hover:bg-green-50' }} rounded-lg transition"
                                        title="{{ $rule->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                    <i class="fas {{ $rule->is_active ? 'fa-pause' : 'fa-play' }} text-sm"></i>
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form action="{{ route('admin.delivery-penalty.destroy', $rule) }}" method="POST"
                                  onsubmit="return confirm('এই rule মুছে ফেলবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-10 text-gray-400">
                <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
                <p class="text-sm">কোনো rule নেই। বাম দিক থেকে যোগ করুন।</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent Penalties --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">সাম্প্রতিক জরিমানা</h3>
        </div>
        @if($recentPenalties->count())
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Transaction</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Seller</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">পরিমাণ</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">তারিখ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentPenalties as $tx)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-xs font-mono text-gray-500">{{ $tx->transaction_number }}</td>
                    <td class="px-6 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ $tx->vendor->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $tx->vendor->role ?? '' }}</p>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">
                        {{ $tx->order->order_number ?? '—' }}
                    </td>
                    <td class="px-6 py-3">
                        <span class="font-bold text-red-600">৳{{ number_format($tx->amount, 2) }}</span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500">{{ $tx->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-10 text-gray-400">
            <p class="text-sm">এখনো কোনো জরিমানা প্রয়োগ হয়নি।</p>
        </div>
        @endif
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 text-lg">Rule সম্পাদনা করুন</h3>
            <button onclick="closeEdit()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">কত দিন পর</label>
                    <input type="number" name="days_threshold" id="edit_days" min="1"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">জরিমানা (৳)</label>
                    <input type="number" name="penalty_amount" id="edit_amount" min="1" step="0.01"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">বিবরণ</label>
                <input type="text" name="description" id="edit_desc"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="edit_active"
                       class="rounded border-gray-300 text-indigo-600">
                <label for="edit_active" class="text-sm text-gray-700">সক্রিয়</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEdit()"
                        class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                    বাতিল
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                    আপডেট করুন
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, days, amount, desc, active) {
    document.getElementById('editForm').action = `/admin/delivery-penalty/${id}`;
    document.getElementById('edit_days').value   = days;
    document.getElementById('edit_amount').value = amount;
    document.getElementById('edit_desc').value   = desc;
    document.getElementById('edit_active').checked = active;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}
function closeEdit() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
</script>
@endsection
