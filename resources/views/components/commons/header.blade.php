@props(['title'])

<header {{ $attributes }} class="sm:flex justify-between py-3 items-end" x-data>
    <div>
        @isset($title)
            <h1 class="text-2xl sm:text-3xl font-bold leading-none">{{ $title }}</h1>
        @endisset
    </div>
    <div class="flex items-end justify-end space-x-4">
        {{ $slot }}
    </div>
</header>
