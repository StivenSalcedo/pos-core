@php
    $hasError = !$errorless && $name && $errors->has($name);
@endphp

<div class="@if($disabled) opacity-60 @endif">

    @if ($label)
        <div class="flex mb-1">
            <x-dynamic-component component="wireui.label" :label="$label" :has-error="$hasError" :for="$id" />
        </div>
    @endif

    <div class="relative rounded-md @unless($shadowless) shadow-sm @endunless">
        @if ($prefix || $icon)
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none {{ $hasError ? 'text-red-500' : 'text-slate-400' }}">
                @if ($icon)
                    <i class="{{ $icon }}"></i>
                @elseif($prefix)
                    <span class="pl-1 flex items-center self-center">
                        {{ $prefix }}
                    </span>
                @endif
            </div>
        @endif

        <input {{ $attributes->class([ $getInputClasses($hasError), ])->merge([ 'type' => 'text', 'autocomplete' => 'off']) }}
            @if ($onlyNumbers) onkeypress='return onlyNumbers(event)'  @endif
        />

        @if ($hasError)
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none {{ $hasError ? 'text-red-500' : 'text-slate-400' }}">
                <i class="ico icon-error"></i>
            </div>
        @endif
    </div>

    @if (!$hasError && $hint)
        <label @if ($id) for="{{ $id }}" @endif class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            {{ $hint }}
        </label>
    @endif
</div>

