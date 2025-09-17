@props(['label'=>'', 'active' => 'activado', 'inactive' => 'desactivado', 'width' => null])
<div>
    @if ($label)
        <label class="font-semibold text-sm">{{ $label }}</label>
    @endif
    <div x-data="{ status: @entangle($attributes->wire('model')),
                textActive: '{{ $active }}',
                textInactive: '{{ $inactive }}',
            }"
        {{ $attributes->merge(['class' => 'flex items-center']) }}>
        <div x-on:click="status = !status"
            :class="status ? 'bg-blue-600' : 'bg-gray-400' "
            class="w-8 sm:w-12 h-4 sm:h-5 rounded-full flex items-center px-1 cursor-pointer transition-colors duration-300">
            <span class="h-3 w-3 sm:h-4 sm:w-4 bg-white rounded-full duration-300 transform " :class="status ? 'translate-x-3.5 sm:translate-x-6' : '' "></span>
        </div>
        <span x-text="status ? textActive : textInactive" class="text-xs sm:text-sm font-semibold ml-1 {{ $width ? $width : '' }} "></span>
    </div>
</div>
