
<{{ $tag }} @disabled($isDisabled) {{ $attributes->whereDoesntStartWith('wire:target') }} @if ($disabledTarget) wire:target='{{ $disabledTarget }}' @endif >

    @if ($load && !$href)
        {!! $getIconLoad($attributes->get('wire:target')) !!}
    @endif

    @if ($icon)
        <i class="ico icon-{{$icon}} mr-1" {{ $load ? 'wire:loading.remove' : '' }} ></i>
    @endif

    @if (!$href)
        <span wire:target="{{ $attributes->get('wire:target') }}" wire:loading> {{ $textLoad }} </span>
        <span wire:target="{{ $attributes->get('wire:target') }}" wire:loading.remove> {{ $text }} </span>
    @else
        {{$text}}
    @endif


</{{ $tag }}>
