<div class="relative z-0 max-w-min min-w-min" >
    <input wire:model.defer="search"
            wire:keydown.enter="render"
            type="search"
            autocomplete="off"
            {{ $attributes->merge(['class' => 'bg-purple-white shadow rounded border border-gray-200 pl-3 pr-10 py-1.5 sm:py-2 focus:ring-1 focus:ring-gray-600 focus:ring-opacity-40 outline-none focus:border-transparent text-sm sm:text-tiny w-60 sm:w-72 md:w-96']) }}>

    <div class="absolute top-0 right-0 h-full flex w-10 items-center pl-1">
        <i class="ico icon-search text-xl"></i>
    </div>
</div>
