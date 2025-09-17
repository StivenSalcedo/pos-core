<div class="text-gray-700 max-w-7xl mx-auto px-4 pb-14 pt-8">

    <div class="space-y-5">

        <x-wireui.card title="Información del proveedor">

            <div class="grid grid-cols-3 gap-3">
                <x-wireui.input label="N° Identificación" value="{{ $provider->no_identification }}" readonly />
                <x-wireui.input label="Nombre" value="{{ $provider->name }}" readonly />
                <x-wireui.input label="Teléfono" value="{{ $provider->phone }}" readonly />
            </div>

            <x-slot:footer>
                <div class="text-right ">
                    <button wire:click="$emitTo('admin.providers.modal', 'openModal')" class="bg-indigo-500 hover:bg-indigo-600 text-sm text-white px-4 py-2 rounded font-semibold font-inter inline-flex items-center">
                        <i class="ico icon-user mr-1 text-sm"></i>
                        Seleccionar
                    </button>
                </div>
            </x-slot:footer>
        </x-wireui.card>

        <x-wireui.card title="Información del producto">
            <div class="grid grid-cols-6 gap-3">
                <div class="col-span-2">
                    <x-wireui.input label="Referencia" value="{{ $product['reference'] }}" readonly class="bg-slate-50" />
                </div>

                <div class="col-span-2">
                    <x-wireui.input label="Nombre" value="{{ $product['name'] }}" readonly class="bg-slate-50" />
                </div>

                <div class="col-span-2 flex space-x-3">
                    <x-wireui.input label="Stock" :value="$product['stock_units']" class="text-center" readonly />
                    <x-wireui.input label="Nuevo stock" :value="$this->newStock" class="text-center" readonly />
                    @if ($product['has_presentations'] === 0)
                        <x-wireui.input label="U. por producto" :value="$product['quantity']" class="text-center" readonly />
                    @endif
                </div>

            </div>

            <div class="mt-4 flex justify-end gap-3">
                <x-wireui.input label="Precio de compra" wire:model.debounce.500ms="product.cost" class="text-right" onlyNumbers />
                <x-wireui.input label="Precio de venta" wire:model.defer="product.price" class="text-right" onlyNumbers />
                <x-wireui.input label="Cantidad" wire:model.debounce.500ms="product.amount" class="text-center" onlyNumbers/>
            </div>

            @if ($product['has_presentations'] === 0)
                <div class="flex justify-end gap-3 mt-4">
                    <x-wireui.input label="Precio de compra por unidad" wire:model.debounce.500ms="product.cost_unit" class="text-right" onlyNumbers />
                    <x-wireui.input label="Unidades" wire:model.debounce.500ms="product.new_units" class="text-center" onlyNumbers/>
                </div>
            @endif

            <div class="text-right mt-4">
                <span class="font-bold text-xl mr-2">@formatToCop($this->total)</span>
            </div>

            <x-slot:footer>
                <div class="text-right" x-data>

                    <x-wireui.button success x-on:click="$wire.emitTo('admin.products.modal', 'openModal')"  text="Seleccionar" />
                    
                    @if ($update)
                        <x-wireui.button wire:click="updateProduct" text="Actualizar" load textLoad="Actualizando" /> 
                        <x-wireui.button secondary wire:click="cancel" text="Cencelar" load textLoad="Cancelando" /> 
                    @else
                        @if($product['product_id'])
                            <x-wireui.button wire:click="addProduct" text="Agregar" load textLoad="Agregando" /> 
                        @endif
                    @endif

                </div>
            </x-slot:footer>
        </x-wireui.card>

        {{-- Tabla --}}
        <x-commons.table-responsive>
            <table class="table-sm">
                <thead>
                    <tr>
                        <th left>
                            Código de barras
                        </th>
                        <th left>
                            Referencia
                        </th>
                        <th left>
                            Nombre
                        </th>
                        <th>
                            Cantidad
                        </th>
                        <th>
                            Costo Unidad
                        </th>
                        <th>
                            Costo
                        </th>
                        <th>
                            Venta
                        </th>
                        <th>
                            Total
                        </th>
                        <th>
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($products as $key => $item)
                        <tr class="" wire:key="product-{{ $key }}">
                            <td left>
                                {{ $item['barcode'] }}
                            </td>
                            <td left>
                                {{ $item['reference'] }}
                            </td>
                            <td left>
                                {{ $item['name'] }}
                            </td>
                            <td>
                                @if (!$item['has_presentations'])
                                    {{ $item['amount'] . ' - ' . $item['new_units'] }}
                                @else
                                    {{ $item['amount'] }}
                                @endif
                                
                            </td>
                            <td>
                                @formatToCop($item['cost_unit'])
                            </td>
                            <td>
                                @formatToCop($item['cost'])
                            </td>
                            <td>
                                @formatToCop($item['price'])
                            </td>
                            <td>
                                @formatToCop($item['total'] )
                            </td>
                            <td actions>
                                <x-buttons.edit wire:click="edit({{$key}})" />
                                <x-buttons.delete wire:click="delete({{$key}})" />
                            </td>
                        </tr>
                    @empty
                        <x-commons.table-empty />
                    @endforelse
                <tbody>
            </table>
        </x-commons.table-responsive>

        <div class="text-right mt-4 pr-4">
            <h1 class="font-bold text-xl">TOTAL @formatToCop($products->sum('total'))</h1> 
        </div>

        <div class="text-right mt-4 pr-4">
            <x-wireui.button wire:click="store" text="Guardar" disabledTarget="product" load textLoad="Guardando" />
        </div>

    </div>

    <livewire:admin.providers.modal>
    <livewire:admin.products.modal>

</div>
