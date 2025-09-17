@props(['icon', 'label'])

<li class="hover:bg-slate-200">
    <{{ $tag }}  {{ $attributes }}>
        @if ($icon)
            <i class="ico icon-{{$icon}} mr-1 w-6"></i>
        @endif
        {{ $label }}
    </{{ $tag }}>
</li>
