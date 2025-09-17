<div x-data="{ show: false }"
  {{ $attributes->merge(['class' => 'fixed inset-0 overflow-y-auto px-4 py-6 z-50']) }}
  x-show="show">

  <div x-show="show"
    class="fixed inset-0 transform transition-all"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
  </div>

  <div x-show="show"
    class="mb-6 transform overflow-hidden transition-all"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

    {{ $slot }}

  </div>

</div>
