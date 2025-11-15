@props(['route', 'name', 'icon', 'active'])

@php
    $class = $active ? 'text-sky-400 bg-gray-100 rounded-md shadow-inner shadow-slate-300' :
                        'text-gray-600'
@endphp

<div class="flex flex-col items-center justify-end cursor-pointer {{ $class }}">

    <a href="{{ $route }}" class="inline-flex items-center font-medium w-full px-2 h-9" title="{{$name}}">
        <i class="ico icon-{{ $icon }}"></i>
        {{-- @if (!request()->routeIs('admin.quick-sales.create'))
            <span class="whitespace-nowrap text-sm ml-4"> {{ $name }} </span>
        @endif --}}
        <span class="hidden md:block whitespace-nowrap text-sm ml-4"> {{ $name }} </span>
    </a>

</div>
