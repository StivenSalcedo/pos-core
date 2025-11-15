<div>
    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h3 class="font-medium whitespace-normal text-lg">Productos utilizados</h3>
        @if (!$service->isValidated)
            <div class="text-right">
                <x-wireui.button primary
                    x-on:click="$wire.emitTo('admin.services.add-product', 'openAddProduct', {{ $service->id }})"
                    text="Agregar producto" icon="add" spinner="update" />
            </div>
        @endif
    </div>

    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Producto</th>
                    <th class="px-3 py-2 text-right">Cantidad</th>
                    <th class="px-3 py-2 text-right">Valor unidad</th>
                    <th class="px-3 py-2 text-right">Descuento</th>
                    <th class="px-3 py-2 text-right">Total</th>
                    <th class="px-3 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $item)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $item['product']['name'] ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-right">{{ $item['quantity'] }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($item['discount'], 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right font-semibold">{{ number_format($item['total'], 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2 text-center">
                               @if (!$service->isValidated)
                            <x-buttons.delete wire:click="deleteProduct({{ $item['id'] }})" title="Eliminar" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-400">No hay resgistros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <livewire:admin.services.add-product />
</div>
