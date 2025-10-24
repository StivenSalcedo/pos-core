<div class="container relative">
    <div class="sticky bg-gray-100 z-30 top-14">
        <x-commons.header>
            <x-wireui.button class="mr-3" icon="check" text="Guardar entrada" />
        </x-commons.header>
    </div>

    <x-wireui.card title="Editar servicio #{{ $service->id }}" separator>

        {{-- Tabs de navegación --}}
        <div class="border-b border-gray-200 mb-4">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="$set('tab', 'main')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'main' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Datos
                    principales</button>
                <button wire:click="$set('tab', 'details')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'details' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Detalles
                    técnicos</button>
                <button wire:click="$set('tab', 'products')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'products' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Productos</button>
                <button wire:click="$set('tab', 'payments')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'payments' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Pagos</button>
                <button wire:click="$set('tab', 'photos')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'photos' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Fotos</button>
                <button wire:click="$set('tab', 'notifications')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'notifications' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">Notificaciones</button>
            </nav>
        </div>

        {{-- Contenido dinámico --}}
        <div>
            @if ($tab === 'main')
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
            @endif
        </div>

    </x-wireui.card>
</div>
