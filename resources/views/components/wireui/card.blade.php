<div class="{{ $cardClasses }}">

    @if ($header)
        {{ $header }}
    @elseif ($title || $action)
        <div class="{{ $headerClasses }}">
            <h3 class="font-semibold whitespace-normal text-lg">{{ $title }}</h3>
            @if ($close)
                <a x-on:click="show=false" class="focus:outline-none text-gray-700 font-semibold text-4xl leading-none cursor-pointer select-none">&times;</a>
            @endif
        </div>
    @endif

    <div {{ $attributes->merge(['class' => "{$padding} grow"]) }}>
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="{{ $footerClasses }}">
            {{ $footer }}
        </div>
    @endif
</div>
