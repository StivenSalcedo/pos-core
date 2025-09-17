@props(['text'])

<div {{ $attributes->merge(['class' => 'absolute inset-0']) }} >
    <div class="bg-white bg-opacity-60 flex items-center justify-center w-full h-full relative z-40">
        <div class="flex flex-col items-center">
            <i class="ico icon-spinner text-blue-600 text-5xl animate-spin"></i>
            <span class="font-bold text-slate-600 text-lg mt-3">{{ $text }}</span>
        </div>
    </div>
</div>
