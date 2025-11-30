<div>
    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h3 class="font-medium whitespace-normal text-lg">Componentes del servicio</h3>
        <div class="text-right">
            <x-wireui.button primary
                x-on:click="$wire.emitTo('admin.services.add-component', 'openAddComponent', {{ $service->id }})"
                text="Agregar componente" icon="add" spinner="update" />
        </div>
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
                @forelse($details as $detail)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2">{{ $detail['component']['name'] ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $detail['brand']['name'] ?? 'Sin marca' }}</td>
                        <td class="px-3 py-2">{{ $detail['reference'] ?? 'SIN REFERENCIA' }}</td>
                        <td class="px-3 py-2">{{ $detail['capacity'] ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-right">{{ $detail['quantity'] ?? 1 }}</td>
                        <td class="px-3 py-2 text-center">
                            <x-buttons.edit wire:click="deleteDetail({{ $detail['id'] }})" title="Editar" />
                            <x-buttons.delete wire:click="deleteDetail({{ $detail['id'] }})" title="Eliminar" />                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-4">
                            No hay resgistros
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <livewire:admin.services.add-component />
</div>
