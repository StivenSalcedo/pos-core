<div>
    <x-wireui.card title="Editar servicio #{{ $service->id }}" separator>

        {{-- Tabs de navegación --}}
        <div class="border-b border-gray-200 mb-4">
            <nav class="-mb-px flex flex-wrap gap-2">
                <button wire:click="$set('tab', 'create')"
                    class="px-3 py-2 font-medium text-sm {{ $tab === 'create' ? 'border-b-2 border-primary-500 text-primary-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Datos principales
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'main\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'main' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Detalles
                </button>

                <button wire:click="{{ $service->id ? '$set(\'tab\', \'details\')' : '' }}"
                    @if (!$service->id) disabled @endif
                    class="px-3 py-2 font-medium text-sm 
        {{ $tab === 'details' ? 'border-b-2 border-primary-500 text-primary-600' : ($service->id ? 'text-gray-500 hover:text-gray-700' : 'text-gray-400 cursor-not-allowed') }}">
                    Detalles técnicos
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
            @endif
        </div>

    </x-wireui.card>
</div>
