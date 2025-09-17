@props(['route', 'name', 'icon', 'active'])

@php
    $class = $active ? 'text-cyan-400' :
                        'text-gray-500'
@endphp

<div class="flex flex-col items-center group">

    <a href="{{ $route }}" class="inline-flex flex-col items-center  w-full px-4 ">
        <i class="ico icon-{{ $icon }} text-gray-500"></i>
        <span class="mt-1 whitespace-nowrap block text-sm group-hover:text-cyan-400 {{ $class }}"> {{ $name }} </span>

        @if ($active)
            <span class="rounded-full h-1 w-full bg-cyan-400"></span>
        @else
            <span class="rounded-full h-2 w-2 group-hover:bg-cyan-400"></span>
        @endif

    </a>

</div>
