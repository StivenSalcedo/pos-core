<div x-data>
    <x-wireui.modal wire:model.defer="openModal" max-width="2xl">
        <x-wireui.card title="Agregar producto al servicio">

            <x-wireui.errors />

            {{-- Campo de búsqueda --}}
            <x-wireui.input label="Buscar producto o escanear código"
                placeholder="Escriba el nombre o escanee el código de barras" wire:model.debounce.500ms="search" />


            {{-- Resultados dinámicos --}}
            @if ($products)
                <ul class="border rounded mt-2 bg-white shadow-sm">
                    @foreach ($products as $p)
                        <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                            wire:click="selectProduct({{ $p['id'] }})">
                            {{ $p['name'] }} — <span class="text-sm text-gray-500">Stock: {{ $p['stock'] }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Datos del producto seleccionado --}}
            @if ($product)
                <div class="grid sm:grid-cols-3 gap-4 mt-4">
                    <x-wireui.input label="Precio Unitario" type="number" wire:model="unit_price" />
                    <x-wireui.input label="Cantidad" type="number" min="1" wire:model="quantity" />
                    <x-wireui.native-select label="Descuento (%)" :options="$discountOptions" wire:model="discountPercent" />
                </div>
            @endif
            <div class="text-right mt-4">
                <p class="text-lg font-semibold text-gray-700">
                    Total: ${{ number_format($total, 2) }}
                </p>
            </div>


            <x-slot name="footer">
                <div class="text-right space-x-2">
                    <x-wireui.button secondary text="Cancelar" x-on:click="$wire.openModal = false" />
                    <x-wireui.button primary wire:click="save" text="Guardar" spinner="save" />
                </div>
            </x-slot>
        </x-wireui.card>
    </x-wireui.modal>
</div>
