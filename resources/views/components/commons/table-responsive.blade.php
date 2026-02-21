<div {{ $attributes }} class="bg-white border border-slate-200 shadow-sm rounded-lg">

    @if (isset($top))
        <div class="flex items-center justify-between py-1.5 px-2 bg-slate-100 border text-xs sm:text-sm md:text-tiny border-b shadow">
            <div>
                <span class="font-bold text-2xl">{{ $top->attributes['title'] }}</span>
            </div>
            <div class="divide-x-2">
                {{ $top }}
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        @if (isset($header))
            <div class="flex w-full items-end p-4 space-x-3">
                {{ $header  }}
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
