<div class="container">

    <x-commons.header>
        <x-wireui.button wire:click="exportProducts" icon="excel" success text="Exportar a excel" load textLoad="Exportando..." />
        <x-wireui.button icon="excel" x-on:click="$wire.emitTo('admin.products.import', 'openImport')"  success text="Importar desde excel" />
        <x-wireui.button icon="inventory" x-on:click="$wire.emitTo('admin.products.create', 'openCreate')"  text="Crear producto" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Productos">
            <x-commons.tag tooltip="Costo del inventario" label="Costos" :value="formatToCop($totalCost)"/>
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." autofocus/>
            <x-wireui.native-select wire:model.defer="filter" label="Por:" optionKeyValue="true" :options="$filters" />
            <x-wireui.native-select optionKeyValue label="Sede" wire:model="terminal_id" :options="$terminals" placeholder="Todas" width="8" />
            <x-wireui.native-select optionKeyValue label="Estado" wire:model="state_id" :options="$status" placeholder="Todas" width="8" />
            <x-wireui.native-select optionKeyValue label="Categoria" wire:model="category_id" :options="$categories" placeholder="Todas" width="8" />
        </x-slot:header>

        <table class="table-sm">
            <thead >
                <tr>
                    <th left>
                        Código barras
                    </th>
                    <th left>
                        Nombre
                    </th>
                    <th left>
                        Categoria
                    </th>
                    <th left>
                        Sede
                    </th>
                    <th>
                        Impuestos(%)
                    </th>
                    <th left>
                        Costo
                    </th>
                    <th left>
                        Precio
                    </th>
                    <th>
                        Stock
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $item)
                    <tr wire:key="product-{{ $item->id }}">
                        <td left class="{{ !$item->top ? 'text-green-500 font-bold' : ''  }}">
                            {{ $item->barcode }}
                        </td>
                        <td left>
                            {{ $item->name }}
                        </td>
                         <td left class="{{ !$item->top ? 'text-green-500 font-bold' : ''  }}">
                            {{ $item->category?->name ?? 'Sin categoria' }}
                        </td>
                        <td left>
                        {{ $item->terminal?->name ?? 'Sin sede' }}
                        </td>
                        <td class="tooltip">
                          <button class="flex items-center justify-center text-sm text-blue-500">
                            <i class="ico icon-eye mr-1 text-base"></i>
                            ver
                          </button>
                          <ul class="tooltip-content">
                            @foreach ($item->taxRates as $taxRate)
                              <li>
                                {{ $taxRate->format_rate}}
                              </li>
                            @endforeach
                          </ul>
                        </td>
                        <td left>
                            @formatToCop($item->cost)
                        </td>
                        <td left>
                            @formatToCop($item->price)
                        </td>
                        <td>
                            {{ $item->stockUnitsLabel }}
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" inactive="desactivado" />
                        </td>
                        <td actions>
                            <x-buttons.edit wire:click="$emitTo('admin.products.edit', 'openEdit', {{ $item->id }})" title="Editar" />
                            <x-buttons.copy wire:click="$emitTo('admin.products.edit', 'openEdit', {{ $item->id }},true)" title="Clonar" />
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    @if ($products->hasPages())
        <div class="p-3">
            {{ $products->links() }}
        </div>
    @endif

    <livewire:admin.products.create>
    <livewire:admin.products.edit>
    <livewire:admin.products.presentations>
    <livewire:admin.products.import>
    <livewire:admin.categories.index>
    <livewire:admin.products.tax-rates>

</div>

@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('deleteProduct', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires eliminar este producto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.delete(id);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
@endpush


