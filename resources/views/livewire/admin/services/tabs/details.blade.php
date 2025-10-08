<div>
  <div class="flex justify-between mb-3">
    <h3 class="text-lg font-semibold text-gray-700">Componentes del servicio</h3>
    <x-wireui.button sm icon="plus" primary wire:click="addComponentRow" text="Agregar componente" />
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full border text-sm text-gray-700">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-3 py-2 text-left">Componente</th>
          <th class="px-3 py-2 text-left">Marca</th>
          <th class="px-3 py-2 text-left">Referencia</th>
          <th class="px-3 py-2 text-left">Capacidad</th>
          <th class="px-3 py-2 text-left">Cantidad</th>
          <th class="px-3 py-2 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($service->details as $index => $detail)
          <tr class="border-b">
            <td class="px-3 py-2">{{ $detail->component->name ?? 'N/A' }}</td>
            <td class="px-3 py-2">{{ $detail->brand->name ?? 'Sin marca' }}</td>
            <td class="px-3 py-2">{{ $detail->reference }}</td>
            <td class="px-3 py-2">{{ $detail->capacity }}</td>
            <td class="px-3 py-2">{{ $detail->quantity }}</td>
            <td class="px-3 py-2 text-center">
              <x-wireui.button sm icon="trash" red wire:click="removeComponent({{ $detail->id }})" />
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-4 text-gray-400">No hay componentes registrados</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
