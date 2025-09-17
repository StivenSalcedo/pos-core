@php
    $hasError = false;
    if ($name) { $hasError = $errors->has($name); }
@endphp

<div class="@if($disabled) opacity-60 @endif">

    @if ($label)
        <div class="flex mb-1">
            <x-dynamic-component component="wireui.label" :label="$label" :has-error="$hasError" :for="$id" />
        </div>
    @endif

    <div class="relative rounded-md shadow-sm">

        <textarea {{ $attributes->class([ $getInputClasses($hasError)])->merge(['autocomplete' => 'off', 'rows'=> 4]) }}>{{ $slot }}</textarea>

        @if (($hasError))
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none
                {{ $hasError ? 'text-negative-500' : 'text-secondary-400' }}">
                @if($hasError)
                    <i class="ico icon-error"></i>
                @endif
            </div>
        @endif
    </div>
</div>
