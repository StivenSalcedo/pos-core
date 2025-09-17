@props(['label', 'value', 'tooltip'])
<div class="inline-flex flex-col text-center px-2 select-none cursor-pointer text-slate-600 hover:text-indigo-600" title="{{$tooltip}}">
    <span class="leading-4 font-semibold">
        {{ $label }}
    </span>
    <span class="leading-4">
        {{ $value }}
    </span>
</div>