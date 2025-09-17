@props(['title' => 'Visualizar'])

@if ($attributes->has('href'))
    <a {{ $attributes }} title="{{$title}}">
        <i class="ico icon-eye text-blue-600 text-xl"></i>
    </a>
@else
    <button {{ $attributes }} title="{{$title}}">
        <i class="ico icon-eye text-blue-600 text-xl"></i>
    </button>
@endif
