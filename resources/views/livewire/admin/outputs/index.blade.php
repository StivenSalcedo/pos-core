<div class="container">

    <x-commons.header>

        <x-wireui.range-date wire:model="filterDate" :options="[
            0 => 'Todos',
            1 => 'Hoy',
            2 => 'Esta semana',
            3 => 'Ultimos 7 días',
            4 => 'La semana pasada',
            5 => 'Hace 15 días',
            6 => 'Este mes',
            7 => 'El mes pasado',
            8 => 'Rango de fechas',
        ]" />

        <x-wireui.button wire:click="exportOutputs" icon="excel" success text="Exportar a excel" load
            textLoad="Exportando..." />

        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.outputs.create', 'openCreate')"
            text="Nuevo egreso" />

    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Egresos">
            <x-commons.tag tooltip="Total de egresos" label="Total" :value="formatToCop($total)" />
        </x-slot:top>

        <x-slot:header>
            @can('ver todas las sedes')
                <x-wireui.native-select optionKeyValue wire:model="terminal_id" :options="$terminals"
                    placeholder="Todas las sedes" width="8" />
            @endcan
            <x-wireui.search placeholder="Buscar..." />
            @if ($filterDate == 8)
                <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
            @endif
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Sede
                    </th>
                    <th left>
                        No Pago
                    </th>

                    <th left>
                        Fecha
                    </th>



                    <th left>
                        Responsable
                    </th>

                    <th left>
                        Motivo
                    </th>

                    <th left>
                        Valor
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($outputs as $item)
                    <tr class="" wire:key="output-{{ $item->id }}">
                        <td left>
                            {{ $item->terminal->name }}
                        </td>
                        <td left>
                            {{ $item->id }}
                        </td>

                        <td left>
                            {{ $item->date->format('d-m-Y') }}
                        </td>
                        <td left class="leading-none">
                            {{ $item->user->name }} <br>
                            <span class="text-xs font-bold leading-none">{{ $item->terminal->name }}</span>
                        </td>

                        <td left>
                            {{ Str::limit($item->reason, 70) }}
                        </td>

                        <td left>
                            @formatToCop($item->price)
                        </td>

                        <td actions>
                            <x-buttons.download href="{{ route('admin.outputs.show', $item->id) }}" target="_blank"
                                title="Descargar" />

                            @can('isAccounted', $item)
                                <x-buttons.edit wire:click="$emitTo('admin.outputs.edit', 'openEdit', {{ $item->id }})"
                                    title="Editar" />
                                <x-buttons.delete wire:click="$emit('deleteOutput', {{ $item->id }})"
                                    title="Eliminar" />
                            @else
                                <x-buttons.show wire:click="$emitTo('admin.outputs.show', 'openShow', {{ $item->id }})"
                                    title="Visualizar" />
                            @endcan
                            <x-buttons.icon primary
                                wire:click="$emitTo('admin.outputs.audit', 'open', {{ $item->id }})"
                                icon="clock" spinner="update" title="Ver auditorias"  />
                        </td>

                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    @if ($outputs->hasPages())
        <div class="p-3">
            {{ $outputs->links() }}
        </div>
    @endif

    <livewire:admin.outputs.create>
        <livewire:admin.outputs.edit>
            <livewire:admin.outputs.show>
                 <livewire:admin.outputs.audit>

</div>

@push('js')
    <script>
        document.addEventListener('livewire:load', function() {

            Livewire.on('deleteOutput', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires eliminar este pago?',
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
