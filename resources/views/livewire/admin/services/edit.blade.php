{{-- <div class="container mt-4">
     <div class="container relative">
     <div class="sticky bg-gray-100 z-30 top-14">
        <x-commons.header>
            <x-wireui.button class="mr-3" icon="check" text="Guardar entrada" />
        </x-commons.header>
</div> --}}
<div class="container mt-4">
    <x-wireui.card title="{{ $service->id ? 'Editar servicio: ' : 'Crear Servicio ' }} {{ $service->id }}" separator>

        {{-- Tabs de navegación --}}
        {{-- <div class="border-gray-200 mb-4">
            <nav class="-mb-px flex justify-between space-x-8">
                <button wire:click="$set('tab', 'main')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'main' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Datos
                    principales</button>
                <button wire:click="$set('tab', 'details')"
                    class="hidden md:block px-3 py-2 font-medium text-sm {{ $tab === 'details' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Detalles
                    técnicos</button>
                <button wire:click="$set('tab', 'products')"
                    class="hidden md:block px-3 py-2 font-medium text-sm {{ $tab === 'products' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Productos</button>
                <button wire:click="$set('tab', 'payments')"
                    class="hidden lg:block px-3 py-2 font-medium text-sm {{ $tab === 'payments' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Pagos</button>
                <button wire:click="$set('tab', 'photos')"
                    class="hidden lg:block px-3 py-2 font-medium text-sm {{ $tab === 'photos' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Fotos</button>
                <button wire:click="$set('tab', 'notifications')"
                    class="hidden lg:block px-3 py-2 font-medium text-sm {{ $tab === 'notifications' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Notificaciones</button>

                <div class="block lg:hidden">
                    <select class="block pl-3 pr-10 py-1.5 text-xs sm:text-sm shadow-sm rounded-md border bg-white focus:ring-1 focus:outline-none border-slate-300 focus:ring-cyan-500 focus:border-cyan-500 w-full">
                        <option value="0">Más opciones</option>
                        <option value="1" wire:click="$set('tab', 'photos')">Fotos</option>
                        <option value="2" wire:click="$set('tab', 'notifications')">Notificaciones</option>
                        <option value="3" wire:click="$set('tab', 'notifications')">Pagos</option>
                        <option class="block md:hidden" value="4"><button wire:click="$set('tab', 'products')">Productos</button></option>
                        <option class="block md:hidden" value="5" wire:click="$set('tab', 'details')" >Detalles técnicos</option>
                    </select>
                </div> --}}

        <div class="border-gray-200 mb-4">
            <nav class="-mb-px flex flex-wrap gap-2">
                <button wire:click="$set('tab', 'create')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'create' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Datos iniciales
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'main\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'main' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Detalles técnicos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'details\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'details' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Componentes
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'products\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'products' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Productos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'payments\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'payments' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Pagos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'photos\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'photos' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Fotos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'notifications\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'notifications' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Notificaciones
                </button>
                <button wire:click="goToHistoriesTab" @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'histories' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Historial Cliente
                </button>
            </nav>
        </div>

        {{-- Contenido dinámico --}}
        <div>
            @if ($tab === 'create')
                @include('livewire.admin.services.tabs.create')
            @elseif ($tab === 'main')
                @include('livewire.admin.services.tabs.main')
            @elseif ($tab === 'details')
                @include('livewire.admin.services.tabs.details')
            @elseif ($tab === 'products')
                @include('livewire.admin.services.tabs.products')
            @elseif ($tab === 'payments')
                @include('livewire.admin.services.tabs.payments')
            @elseif ($tab === 'photos')
                @include('livewire.admin.services.tabs.photos')
            @elseif ($tab === 'notifications')
                @include('livewire.admin.services.tabs.notifications')
            @elseif ($tab === 'histories')
                @include('livewire.admin.services.tabs.histories')
            @endif
        </div>

    </x-wireui.card>
</div>
@push('js')
    <script>
        window.addEventListener('open-new-tab', event => {
            const url = event.detail.url;
            window.open(url, '_blank'); // Abre en nueva pestaña
        });
    </script>
@endpush
