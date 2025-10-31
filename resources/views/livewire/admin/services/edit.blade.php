{{-- <div class="container mt-4">
     <div class="container relative">
     <div class="sticky bg-gray-100 z-30 top-14">
        <x-commons.header>
            <x-wireui.button class="mr-3" icon="check" text="Guardar entrada" />
        </x-commons.header>
</div> --}}
<div class="container relative">
    <div class="sticky bg-gray-100 z-30 top-14">
        <x-commons.header>
            {{-- <x-wireui.button class="mr-3" icon="check" text="Guardar entrada" /> --}}

            {{-- dropdown Notification --}}
            <x-dropdown align="left" width="full">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center border border-transparent leading-6 font-medium rounded-md text-white transition ease-in-out duration-150 text-xs sm:text-sm px-4 py-1 sm:py-1.5 bg-indigo-500 hover:bg-indigo-600 hover:ring-indigo-500 disabled:opacity-60"
                        title="Perfil">
                        <div class="flex items-center">
                            <i class="ico icon-bell mr-2"></i>
                            <div>Notificaciones</div>
                            <div class="button-dropdown"></div>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link class="flex items-center"  target="_blank">
                        Email
                    </x-dropdown-link>
                    <x-dropdown-link class="flex items-center">
                        WhatsApp
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>

            {{-- dropdown print --}}
            {{-- <x-dropdown align="left" width="full">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center border border-transparent leading-6 font-medium rounded-md text-white transition ease-in-out duration-150 text-xs sm:text-sm px-4 py-1 sm:py-1.5 bg-indigo-500 hover:bg-indigo-600 hover:ring-indigo-500 disabled:opacity-60"
                        title="Perfil">
                        <div class="flex items-center">
                            <i class="ico icon-pdf mr-2"></i>
                            <div>Impresión</div>
                            <div class="button-dropdown"></div>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link class="flex items-center" :href="route('admin.service-detail.pdf', $service->id)" target="_blank">
                        Imprimir entrada
                    </x-dropdown-link>
                    <x-dropdown-link class="flex items-center" @click="$dispatch('print-ticket', {{  $service->id }})" target="_blank">
                        Imprimir recibo
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown> --}}
        </x-commons.header>
    </div>
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
            <nav class="-mb-px flex justify-between gap-2">
                <button wire:click="$set('tab', 'create')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'create' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Datos iniciales
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'main\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="hidden sm:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'main' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Detalles técnicos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'details\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="hidden lg:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'details' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Componentes
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'products\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="hidden lg:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'products' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Productos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'payments\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="hidden lg:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'payments' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Pagos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'photos\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="hidden xl:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'photos' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Fotos
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'notifications\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="hidden xl:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'notifications' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Notificaciones
                </button>
                <button wire:click="goToHistoriesTab" @if (!$service->id) disabled @endif
                    class="hidden xl:block px-3 py-2 font-medium text-sm 
        {{ $tab === 'histories' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Historial cliente
                </button>

                {{-- dropdown menu services --}}
                <div class="block xl:hidden">
                    <x-dropdown align="left" width="full">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center border border-transparent leading-6 font-medium rounded-md text-white transition ease-in-out duration-150 text-xs sm:text-sm px-4 py-1 sm:py-1.5 bg-gray-500 hover:bg-gray-600 hover:ring-gray-500 disabled:opacity-60"
                                title="Perfil">
                                <div class="flex items-center">
                                    <div>Más opciones</div>
                                    <div class="button-dropdown"></div>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link >
                                Historial cliente
                            </x-dropdown-link>
                            <x-dropdown-link>
                                Notificaciones
                            </x-dropdown-link>
                            <x-dropdown-link>
                                Fotos
                            </x-dropdown-link>
                            <x-dropdown-link class="block lg:hidden">
                                Pagos
                            </x-dropdown-link>
                            <x-dropdown-link class="block lg:hidden">
                                Productos
                            </x-dropdown-link>
                            <x-dropdown-link class="block lg:hidden">
                                Componentes
                            </x-dropdown-link>
                            <x-dropdown-link class="block sm:hidden">
                                Detalles técnicos
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

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
        @include('pdfs.ticket-service')
     
    </x-wireui.card>
    <div class="grid sm:grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
        <div
            class="w-full h-36 flex flex-col items-center justify-center border rounded-2xl bg-indigo-700/90 text-white">
            <i class="ico icon-money text-7xl text-indigo-800"></i>
            <span class="-mt-3">Subtotal</span>
            <span class="font-semibold text-2xl">$4000</span>
        </div>
        <div class="w-full h-36 flex flex-col items-center justify-center border rounded-2xl bg-blue-700/90 text-white">
            <i class="ico icon-money text-7xl text-blue-800"></i>
            <span class="-mt-3">Descuentos</span>
            <span class="font-semibold text-2xl">$4000</span>
        </div>
        <div
            class="w-full h-36 flex flex-col items-center justify-center border rounded-2xl bg-indigo-700/90 text-white">
            <i class="ico icon-money text-7xl text-indigo-800"></i>
            <span class="-mt-3">Abonos</span>
            <span class="font-semibold text-2xl">$4000</span>
        </div>
        <div class="w-full h-36 flex flex-col items-center justify-center border rounded-2xl bg-blue-700/90 text-white">
            <i class="ico icon-money text-7xl text-blue-800"></i>
            <span class="-mt-3">Saldos</span>
            <span class="font-semibold text-2xl">$4000</span>
        </div>
    </div>
</div>
@push('js')
    <script>
        window.addEventListener('open-new-tab', event => {
            const url = event.detail.url;
            window.open(url, '_blank'); // Abre en nueva pestaña
        });
    </script>
@endpush
