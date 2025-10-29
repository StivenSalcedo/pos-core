 <div x-data="{ tab: 'histories' }" class="space-y-6">
 <x-commons.table-responsive>
       
       
        <table class="table">
            <thead>
                <tr>
                    <th >
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
                    <th left>
                        Fecha vencimiento
                    </th>
                    <th left>
                        Equipo
                    </th>
                    <th left>
                        Marca
                    </th>
                    <th left>
                        Modelo
                    </th>
                    <th left>
                        Estado
                    </th>
                    <th center>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $service)
                    <tr>
                        <td>{{ $service['id'] }}</td>
                        <td class="text-left">{{ $service['customer']['names'] ?? 'Sin cliente' }}</td>
                        <td class="text-left">
                            {{ $service['responsible']['name'] ?? 'No asignado' }}
                        </td>
                        <td class="text-left">
                            {{ \Carbon\Carbon::parse($service['date_entry'])->format('d/m/Y h:i:s a') }}
                        </td>
                        <td class="text-left">
                            {{ \Carbon\Carbon::parse($service['date_due'])->format('d/m/Y') }}
                        </td>
                        <td class="text-left">{{ $service['equipmentType']['name'] ?? 'N/A' }}</td>
                        <td class="text-left">{{ $service['brand']['name'] ?? 'N/A' }}</td>
                        <td class="text-left">{{ $service['model'] ?? '-' }}</td>
                        <td class="text-left">
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">
                                {{ $service['state']['name'] ?? 'Recibido' }}
                            </span>
                        </td>
                        <td actions class="text-left">
                            <x-buttons.edit wire:click="redirectToEdit({{ $service['id'] }})" title="Editar"/>
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
 </div>