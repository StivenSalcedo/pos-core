<div class="container">
    <x-commons.header>
        <x-wireui.button icon="file" href="{{ route('admin.services.create') }}" text="Nuevo servicio" />
    </x-commons.header>
    <x-commons.table-responsive>
        <x-slot:top title="Servicios técnicos"></x-slot:top>
        <x-slot:header>
            <input wire:model.debounce.500ms="search" type="text" placeholder="Buscar por modelo o cliente..."
                class="border-gray-300 rounded-lg w-full md:w-1/3 focus:ring focus:ring-blue-200" />
      <select wire:model="selectedState" class="border-gray-300 rounded-lg">
                <option value="">Todos los estados</option>
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
                        Factura
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
                            {{ \Carbon\Carbon::parse($service['date_entry'])->format('d/m/Y h:i:s a') }}
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

                        @if (App\Services\FactusConfigurationService::isApiEnabled(true) &&
                                count($service->products) > 0 &&
                                count($service->payments) > 0)
                            <td class="text-center">
                                <div class="flex justify-center">
                                    @if ($service->isValidated)
                                        <x-icons.factus class="h-6 w-6 text-indigo-800" title="Validada" />
                                    @else
                                        <button wire:click='validateElectronicBill({{ $service->id }})'>
                                            <x-icons.factus class="h-6 w-6 text-red-500"
                                                title="Pendiente por validar" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td class="text-center">&nbsp;</td>
                        @endif

                        <td actions>
                            <x-buttons.download wire:click="printReceipt({{ $service->id }})" title="Descargar" />
                            <x-buttons.edit wire:click="redirectToEdit({{ $service->id }})" title="Editar" />
                            <!--  <x-buttons.delete wire:click="confirmDelete({{ $service->id }})" title="Eliminar" />-->
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
    <div class="flex flex-wrap lg:flex-nowrap">
         @foreach (\App\Models\ServiceState::orderBy('order')->limit(4)->get() as $state)
            <x-wireui.button wire:click="updateData({{ $state->id }})" secondary class="w-full lg:w-3/5 ml-0 lg:ml-4 mt-6 {{($this->selectedState==$state->id)?'bg-green-600':'bg-slate-900'}}"
                text="{{ $state->name }} : {{ $servicesGrouped->get($state->id)?->count() ?? 0 }}" />
        @endforeach
    </div>

    <x-loads.panel-fixed text="Validando factura..." class="no-print z-[999]" wire:loading
        wire:target='validateElectronicBill' />
    <div class="mt-4">
        {{ $services->links() }}
    </div>
    @include('pdfs.ticket-service')
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

</div>
