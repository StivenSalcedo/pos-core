<div class="container">
    

    <x-commons.header>
        <x-wireui.button icon="file" x-on:click="$wire.emitTo('admin.services.create', 'openCreate');" text="Nuevo servicio" />
    </x-commons.header>
    <x-commons.table-responsive>
        <x-slot:top title="Servicios técnicos"></x-slot:top>
        <x-slot:header>
            <input wire:model.debounce.500ms="search" type="text" placeholder="Buscar por modelo o cliente..."
            class="border-gray-300 rounded-lg w-full md:w-1/3 focus:ring focus:ring-blue-200" />
            <select wire:model="selectedState" class="border-gray-300 rounded-lg">
                <option value="">Todos los estados</option>
                <option value="recibido">Recibido</option>
                @foreach (\App\Models\ServiceState::orderBy('order')->get() as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
                @endforeach
            </select>
            <select wire:model="perPage" class="border-gray-300 rounded-lg">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </select>
        </x-slot:header>
        <table class="table">
            <thead>
                <tr>
                    <th left>
                        #
                    </th>
                    <th left>
                        Cliente
                    </th>
                    <th left>
                        Responsable
                    </th>
                    <th left>
                        Fecha ingreso
                    </th>
                    <th>
                        Fecha vencimiento
                    </th>
                    <th>
                        Equipo
                    </th>
                    <th>
                        Marca
                    </th>
                    <th>
                        Modelo
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
                @forelse ($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td class="text-left">{{ $service->customer->names ?? 'Sin cliente' }}</td>
                        <td>
                            {{ $service->responsible?->name ?? 'No asignado' }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($service->date_entry)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($service->date_due)->format('d/m/Y') }}
                        </td>
                        <td>{{ $service->equipmentType->name ?? 'N/A' }}</td>
                        <td>{{ $service->brand->name ?? 'N/A' }}</td>
                        <td>{{ $service->model ?? '-' }}</td>
                        <td>
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                {{ $service->state->name ?? 'Recibido' }}
                            </span>
                        </td>
                        <td actions>
                            <x-buttons.edit wire:click="redirectToEdit({{ $service->id }})" title="Editar"/>
                            <x-buttons.delete wire:click="confirmDelete({{ $service->id }})" title="Eliminar"/>
                        </td>
                      
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">No hay servicios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-commons.table-responsive>

    <div class="mt-4">
        {{ $services->links() }}
    </div>

    <script>
        document.addEventListener('livewire:load', () => {
            window.addEventListener('confirm-delete', () => {
                Swal.fire({
                    title: '¿Eliminar servicio?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        Livewire.emit('deleteService');
                        Swal.fire('Eliminado!', 'El servicio ha sido eliminado.', 'success');
                    }
                });
            });

            window.addEventListener('notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: e.detail.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        });
    </script>
    <livewire:admin.services.create />
</div>
