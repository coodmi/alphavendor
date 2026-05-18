@if(isset($attributes) && $attributes->count() > 0)
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-3">Product Attributes</label>
    <p class="text-xs text-gray-500 mb-3">Select multiple colors and sizes where available.</p>
    <div class="space-y-4 border border-gray-200 rounded-xl p-4 bg-gray-50">
        @foreach($attributes as $attr)
        <div class="bg-white rounded-lg border border-gray-100 p-3">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                {{ $attr->name }}
                @if($attr->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
            </label>

            @if($attr->type === 'select' && $attr->options)
            <div id="select-picker-{{ $attr->id }}">
                <div class="flex flex-wrap gap-1.5">
                    @foreach($attr->options as $opt)
                    <button type="button"
                            onclick="toggleSelectOption({{ $attr->id }}, '{{ addslashes($opt) }}')"
                            id="opt-{{ $attr->id }}-{{ Str::slug($opt) }}"
                            class="px-3 py-1 text-xs font-medium border border-gray-300 rounded-full bg-white hover:border-indigo-500 hover:text-indigo-600 transition-all select-option-btn">
                        {{ $opt }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="attributes[{{ $attr->id }}]"
                       id="selectValue-{{ $attr->id }}" class="attr-field" data-attr-id="{{ $attr->id }}">
            </div>

            @elseif($attr->type === 'color')
            <div id="color-picker-{{ $attr->id }}">
                <div class="flex flex-wrap gap-2 mb-2" id="swatches-{{ $attr->id }}"></div>
                <div class="flex items-center gap-2">
                    <input type="color" id="colorInput-{{ $attr->id }}" value="#000000"
                           class="h-9 w-12 border border-gray-300 rounded-lg cursor-pointer">
                    <button type="button" onclick="addColor({{ $attr->id }})"
                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                        + Add Color
                    </button>
                </div>
                <input type="hidden" name="attributes[{{ $attr->id }}]"
                       id="colorValue-{{ $attr->id }}" class="attr-field" data-attr-id="{{ $attr->id }}">
            </div>

            @elseif($attr->type === 'number')
            <input type="number" name="attributes[{{ $attr->id }}]"
                   placeholder="Enter {{ $attr->name }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none attr-field"
                   data-attr-id="{{ $attr->id }}"
                   {{ $attr->is_required ? 'required' : '' }}>

            @else
            <div id="text-picker-{{ $attr->id }}">
                <div class="flex flex-wrap gap-1.5 mb-2" id="text-tags-{{ $attr->id }}"></div>
                <div class="flex gap-2">
                    <input type="text" id="textInput-{{ $attr->id }}"
                           placeholder="Type and press Enter or +"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none"
                           onkeydown="if(event.key==='Enter'){event.preventDefault();addTextTag({{ $attr->id }});}">
                    <button type="button" onclick="addTextTag({{ $attr->id }})"
                            class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">+</button>
                </div>
                <input type="hidden" name="attributes[{{ $attr->id }}]"
                       id="textValue-{{ $attr->id }}" class="attr-field" data-attr-id="{{ $attr->id }}">
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
