<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="6xl">
        <x-wireui.card title="Crear producto">

            <x-wireui.errors />

            <div class="">

                <div class="grid grid-cols-2 gap-6">

                    <div class="flex space-x-2 items-end">
                        <div class="w-full">
                            <x-wireui.native-select label="Categoría" placeholder="Selecciona una categoría"
                                wire:model.defer="category_id" optionKeyValue :options="$categories" class="min-w-full" />
                        </div>
                        <button wire:click='$emitTo("admin.categories.index", "openCreate", "{{ $this->getName() }}")'
                            class="h-10 w-10 bg-blue-500 text-white rounded-lg" title="Crear categoría">
                            <i class="ico icon-add"></i>
                        </button>
                    </div>

                    <x-wireui.input label="Nombre" wire:model.defer="name" placeholder="Nombre" />

                    <x-wireui.native-select label="Sede" placeholder="Selecciona una Sede"
                        wire:model.defer="terminal_id" optionKeyValue :options="$terminals" class="min-w-full" />

                </div>

                <div class="grid grid-cols-2 gap-6 mt-6">

                    <x-wireui.input label="Código de barras" wire:model.defer="barcode"
                        placeholder="Código de barras" />
                    <x-wireui.input label="N° Referencia" wire:model.defer="reference"
                        placeholder="Número de referencia" />

                </div>
                {{-- Proveedor (buscador) --}}
                <div class="mt-6">
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                        {{-- Botón para crear nuevo proveedor --}}
                        <button title="Crear nuevo Proveedor"
                            x-on:click="setTimeout(() => {Livewire.emitTo('admin.providers.create', 'openCreate');}, 300);">
                            <i class="ico icon-add-user text-blue-600 text-xl"></i>
                        </button>
                    </div>
                    <x-wireui.input class="w-full" wire:model.debounce.500ms="searchProvider"
                        placeholder="Buscar proveedor por nombre o documento..." />

                    {{-- Resultados búsqueda --}}
                    @if ($providers && count($providers) > 0)
                        <div class="border mt-2 rounded-md shadow bg-white max-h-40 overflow-y-auto">
                            @foreach ($providers as $provider)
                                <div wire:click="selectProvider({{ $provider->id }})"
                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm">
                                    {{ $provider->name }}
                                    <span class="text-gray-500 text-xs">({{ $provider->no_identification }})</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Cliente seleccionado --}}
                    @if ($selectedProvider)
                        <div
                            class="bg-gray-50 border rounded-lg p-2 text-sm text-gray-700 flex items-center justify-between my-4">
                            <div>
                                <strong>{{ $selectedProvider['name'] ?? '' }}</strong>
                                <span class="text-gray-500 text-xs">
                                    ({{ $selectedProvider['no_identification'] ?? '' }})
                                </span>
                            </div>
                            <x-buttons.delete wire:click="clearProvider" title="Eliminar" />

                            {{-- <x-wireui.button flat sm icon="trash"  class="ml-2" /> --}}

                        </div>
                    @endif
                    @error('provider_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Marca (buscador) --}}
                <div class="relative">
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                        {{-- Botón para crear nueva Marca --}}
                        <button class="absolute top-0 right-0" title="Crear nueva marca"
                            wire:click='$emitTo("admin.brands.create", "openCreate", "{{ $this->getName() }}")'>
                            <i class="ico icon-add text-blue-600 text-sm"></i>
                        </button>

                    </div>
                    <x-wireui.input class="w-full" wire:model.debounce.500ms="searchBrand"
                        placeholder="Buscar Masrca por nombre..." />

                    {{-- Resultados búsqueda --}}
                    @if ($brands && count($brands) > 0)
                        <div class="border mt-2 rounded-md shadow bg-white max-h-40 overflow-y-auto">
                            @foreach ($brands as $brand)
                                <div wire:click="selectBrand({{ $brand->id }})"
                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm">
                                    {{ $brand->name }}

                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Cliente seleccionado --}}
                    @if ($selectedBrand)
                        <div
                            class="bg-gray-50 border rounded-lg p-2 text-sm text-gray-700 flex items-center justify-between my-4">
                            <div>
                                <strong>{{ $selectedBrand['name'] ?? '' }}</strong>

                            </div>
                            <x-buttons.delete wire:click="clearBrand" title="Eliminar" />



                        </div>
                    @endif
                    @error('brand_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-6 mt-6">
                    <div class="flex space-x-2 items-end">
                        <div class="flex-1">
                            <x-wireui.input label="Impuestos" :value="$tax_rates->implode('format_rate', ', ')" readonly class="w-full" />
                        </div>
                        <button wire:click='openTaxRates' class="h-10 w-10 bg-blue-500 text-white rounded-lg"
                            title="Agregar impuestos">
                            <i class="ico icon-add"></i>
                        </button>
                    </div>
                    <x-wireui.input onlyNumbers label="Costo" wire:model.defer="cost" placeholder="Costo" />
                    <x-wireui.input onlyNumbers label="Precio" wire:model.defer="price" placeholder="Precio" />
                    <x-wireui.input onlyNumbers label="Precio por mayor" name="wholesale_price"
                        wire:model.defer="wholesale_price" placeholder="Precio al por mayor" />
                    <x-wireui.input onlyNumbers label="Precio emprendedor" name="entrepreneur_price"
                        wire:model.defer="entrepreneur_price" placeholder="Precio para emprendedor" />
                </div>

                @if ($is_inventory_enabled)

                    <div class="grid grid-cols-3 gap-6 mt-6">

                        <x-buttons.switch label="Llevar inventario" wire:model="has_inventory" active="Sí"
                            inactive="No" />

                        @if (!$has_inventory)

                            <x-buttons.switch label="Manejar presentaciones" wire:model="has_presentations"
                                active="Sí" inactive="No" />

                            <div class="grid {{ $has_presentations ? 'grid-cols-1' : 'grid-cols-2' }} gap-6">
                                <x-wireui.input onlyNumbers label="Stock" wire:model.defer="stock"
                                    placeholder="Cantidad de stock" />
                                @if (!$has_presentations)
                                    <x-wireui.input onlyNumbers label="Unidades" wire:model.defer="units"
                                        placeholder="Unidades" />
                                @endif
                            </div>

                        @endif

                    </div>
                @endif

            </div>

            @if (!$has_presentations)

                <div class="mt-4 flex items-end justify-between">
                    <x-wireui.input onlyNumbers label="Unidades por producto" wire:model.defer="quantity"
                        placeholder="Cantidad" />
                    <div>
                        <x-wireui.button
                            x-on:click="$wire.emitTo('admin.products.presentations', 'openPresentations', '{{ $this->getName() }}')"
                            text="Agregar presentación" />
                    </div>
                </div>

                <x-commons.table-responsive class="mt-4 border">

                    <table class="table-sm">
                        <thead>
                            <tr>
                                <th left>
                                    Nombre
                                </th>
                                <th>
                                    Cantidad
                                </th>
                                <th left>
                                    Precio
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($presentations as $key => $item)
                                <tr wire:key="edit-presentation{{ $key }}">
                                    <td left>
                                        {{ $item['name'] }}
                                    </td>
                                    <td>
                                        {{ $item['quantity'] }}
                                    </td>
                                    <td left>
                                        @formatToCop($item['price'])
                                    </td>
                                    <td actions>
                                        <x-buttons.delete wire:click="removePresentation({{ $key }})" />
                                        <x-buttons.edit wire:click="editPresentation({{ $key }})" />
                                    </td>
                                </tr>
                            @empty
                                <x-commons.table-empty text="No se encontraron presentaciones agregadas" />
                            @endforelse
                        <tbody>
                    </table>
                </x-commons.table-responsive>

            @endif

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                    <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando.." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
  
</div>
