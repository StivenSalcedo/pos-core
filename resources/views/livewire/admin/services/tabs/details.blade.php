<div>
  <div class="flex justify-between mb-3">
    <h3 class="text-lg font-semibold text-gray-700">Componentes del servicio</h3>

    {{-- Botón opcional para añadir un nuevo componente manualmente --}}
    <x-wireui.button sm icon="plus" primary wire:click="addComponentRow" text="Agregar componente" />
  </div>

  {{-- Tabla de componentes --}}
  <div class="overflow-x-auto border rounded-lg">
    <table class="min-w-full text-sm text-gray-700">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-3 py-2 text-left">#</th>
          <th class="px-3 py-2 text-left">Componente</th>
          <th class="px-3 py-2 text-left">Marca</th>
          <th class="px-3 py-2 text-left">Referencia</th>
          <th class="px-3 py-2 text-left">Capacidad</th>
          <th class="px-3 py-2 text-right">Cantidad</th>
          <th class="px-3 py-2 text-center">Acciones</th>
        </tr>
      </thead>

      <tbody>
        @forelse($service->details as $index => $detail)
          <tr class="border-b hover:bg-gray-50">
            <td class="px-3 py-2">{{ $index + 1 }}</td>
            <td class="px-3 py-2">{{ $detail->component->name ?? 'N/A' }}</td>
            <td class="px-3 py-2">{{ $detail->brand->name ?? 'Sin marca' }}</td>
            <td class="px-3 py-2">{{ $detail->reference ?? 'SIN REFERENCIA' }}</td>
            <td class="px-3 py-2">{{ $detail->capacity ?? 'N/A' }}</td>
            <td class="px-3 py-2 text-right">{{ $detail->quantity ?? 1 }}</td>
            <td class="px-3 py-2 text-center">
              <x-wireui.button
                sm
                icon="trash"
                red
                wire:click="removeComponent({{ $detail->id }})"
                tooltip="Eliminar componente"
              />
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-gray-400 py-4">
              No hay componentes registrados en este servicio.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
