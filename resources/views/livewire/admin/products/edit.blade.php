<div>
    <x-wireui.modal wire:model.defer="openEdit" max-width="6xl">
        <x-wireui.card title="Actualizar producto">

            <x-wireui.errors />

            <div>


                <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <x-wireui.native-select label="Sede" placeholder="Selecciona una sede"
                        wire:model.defer="product.terminal_id" optionKeyValue :options="$terminals" class="min-w-full" />

                    {{-- Categoria --}}
                    <div class="relative">
                        <x-wireui.native-select class="w-full" label="Categoría" placeholder="Selecciona una categoría"
                            wire:model.defer="product.category_id" optionKeyValue :options="$categories" />
                        {{-- Botón para crear nuevo --}}
                        <button class="absolute top-0 right-0" title="Crear nuevo categoría"
                            wire:click='$emitTo("admin.categories.index", "openCreate", "{{ $this->getName() }}")'>
                            <i class="ico icon-add text-blue-600 text-sm"></i>
                        </button>
                    </div>
                    <x-wireui.input label="Nombre" name="name" wire:model.defer="product.name"
                        placeholder="Nombre del producto" />
                    <x-wireui.input label="Proveedor" name="name" wire:model.defer="product.name"
                        placeholder="Nombre del proveedor" />
                    <x-wireui.input label="Código de barras" name="barcode" wire:model.defer="product.barcode"
                        placeholder="Código de barras" />
                    <x-wireui.input label="Referencia" name="reference" wire:model.defer="product.reference"
                        placeholder="Referencia del producto" />
                    <x-wireui.input label="Marca" name="name" wire:model.defer="product.name"
                        placeholder="Nombre de la marca" />

                    {{-- Impuestos --}}
                    <div class="relative">
                        <x-wireui.input class="w-full" label="Impuestos" :value="$tax_rates->implode('format_rate', ', ')" readonly />
                        {{-- Botón para crear nuevo --}}
                        <button class="absolute top-0 right-0" title="Crear nuevo impuesto" wire:click='openTaxRates'>
                            <i class="ico icon-add text-blue-600 text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

                    <x-wireui.input onlyNumbers label="Costo" name="cost" wire:model.defer="product.cost"
                        placeholder="Costo del producto" />
                    <x-wireui.input onlyNumbers label="Precio" name="price" wire:model.defer="product.price"
                        placeholder="Precio del producto" />
                    <x-wireui.input onlyNumbers label="Precio por mayor" name="price" wire:model.defer="product.price"
                        placeholder="Precio del producto" />
                    <x-wireui.input onlyNumbers label="Precio emprendedor" name="price"
                        wire:model.defer="product.price" placeholder="Precio del producto" />
                </div>

                @if ($is_inventory_enabled)
                    @if (!$product->has_inventory)

                        <div
                            class="grid {{ $product->has_presentations ? 'lg:grid-cols-1' : 'lg:grid-cols-4' }} md:grid-cols-2 gap-6 mt-6">

                            <x-wireui.input onlyNumbers label="Stock" name="stock" wire:model.defer="product.stock"
                                placeholder="Cantidad de stock" />

                            @if (!$product->has_presentations)
                                <x-wireui.input onlyNumbers label="Unidades" name="units" wire:model.defer="units"
                                    placeholder="Unidades" />

                                <x-wireui.input onlyNumbers label="Unidades por producto" name="quantity"
                                    wire:model.defer="product.quantity" placeholder="Cantidad" />
                                <div class="flex items-end">
                                    <x-wireui.button class="inline w-full h-10 text-center" icon="add"
                                        x-on:click="$wire.emitTo('admin.products.presentations', 'openPresentations', '{{ $this->getName() }}')"
                                        text="Agregar presentación" icon="add" spinner="update" />
                                </div>
                            @endif

                        </div>

                        <x-buttons.switch class="mt-6" wire:model="product.has_presentations"
                            active="Manejar presentaciones" inactive="No Manejar presentaciones" />

                    @endif
                @endif

            </div>

            @if (!$product->has_presentations)

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

            <div class="flex justify-center mt-6">
                <x-wireui.button primary text="Agregar fotografía" icon="add" spinner="update" />
            </div>

            {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div style="width: 200px" class="relative border rounded-lg overflow-hidden group">
                        <img src="" alt=""
                            class="object-cover h-40 w-full cursor-pointer">

                        <x-wireui.button sm icon="trash" red 
                            tooltip="Eliminar foto" />

                    </div>
                    <div class="col-span-4">
                        <p class="text-center text-gray-400">No hay imágenes registradas.</p>
                    </div>
            </div> --}}

            <div class="grid grid-cols-3 gap-6 border-t pt-6 mt-6">
                <x-buttons.switch wire:model="product.has_inventory" active="Llevar inventario"
                    inactive="No llevar inventario" />
                <div class="flex justify-center">
                    <x-buttons.switch wire:model="product.top" active="Destacado" inactive="No destacado" />
                </div>
                <div class="flex justify-end">
                    <x-buttons.switch wire:model="product.status" />
                </div>
            </div>

            <x-slot:footer>
                <div class="flex justify-center">
                    <div class="space-x-3">
                        <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                        <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando.." />
                    </div>
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
