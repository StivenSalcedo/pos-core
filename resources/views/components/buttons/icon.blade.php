@props(['icon', 'iconColor' => 'text-blue-600', 'title' => ''])

@if ($attributes->has('href'))
    <a {{ $attributes }} title="{{$title}}">
        <i class="ico icon-{{$icon}} {{ $iconColor }} text-xl"></i>
    </a>
@else
    <button {{ $attributes }} title="{{$title}}">
        <i class="ico icon-{{$icon}} {{ $iconColor }} text-xl"></i>
    </button>
@endif
