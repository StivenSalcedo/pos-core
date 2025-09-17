@props(['title' => 'Descargar'])

@if ($attributes->has('href'))
    <a {{ $attributes }} title="{{$title}}">
        <i class="ico icon-download text-blue-600 text-xl"></i>
    </a>
@else
    <button {{ $attributes }} title="{{$title}}">
        <i class="ico icon-download text-blue-600 text-xl"></i>
    </button>
@endif

