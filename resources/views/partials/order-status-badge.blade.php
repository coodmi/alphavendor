@php
use App\Helpers\OrderStatus;
$cfg = OrderStatus::all()[$status] ?? ['label' => ucfirst(str_replace('_',' ',$status)), 'color' => 'bg-gray-100 text-gray-600', 'icon' => 'fa-circle'];
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $cfg['color'] }}">
    <i class="fas {{ $cfg['icon'] }} text-xs"></i>
    {{ $cfg['label'] }}
</span>
