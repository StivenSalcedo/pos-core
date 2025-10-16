<div>
  <div class="flex justify-between mb-3">
    <h3 class="text-lg font-semibold text-gray-700">Productos utilizados</h3>
   <x-wireui.button
            primary
            sm icon="plus"
            text="Agregar producto"
            x-on:click="$wire.emitTo('admin.services.add-product', 'openAddProduct', {{ $service->id }})"
        />
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full border text-sm text-gray-700">
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
            <td class="px-3 py-2 text-right font-semibold">{{ number_format($item['total'], 0, ',', '.') }}</td>
            <td class="px-3 py-2 text-center">
              <x-wireui.button sm icon="trash" red wire:click="deleteProduct({{ $item['id'] }})" />
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-4 text-gray-400">No hay productos asociados</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
    <livewire:admin.services.add-product />
</div>
