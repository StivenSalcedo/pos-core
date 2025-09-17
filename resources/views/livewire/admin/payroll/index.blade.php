<div class="container">

    <x-commons.header >

        <x-wireui.range-date wire:model="filterDate" :options="[
            0 => 'Todos',
            1 => 'Hoy',
            2 => 'Esta semana',
            3 => 'Ultimos 7 días',
            4 => 'La semana pasada',
            5 => 'Hace 15 días',
            6 => 'Este mes',
            7 => 'El mes padado',
            8 => 'Rango de fechas']" />

        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.payroll.create', 'openCreate')"  text="Crear pago" />

    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Nominas">
            <x-commons.tag tooltip="Total de nomina" label="Total" :value="formatToCop($total)" />
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select wire:model.defer="filter" optionKeyValue :options="$filters" />
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
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
                        Identificación
                    </th>

                    <th left>
                        Nombre
                    </th>
                    <th left>
                        Celular
                    </th>
                    <th left>
                        Email
                    </th>
                    <th left>
                        Valor
                    </th>
                    <th >
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payroll as $item)
                    <tr wire:key="payroll{{$item->id}}">

                        <td left>
                            {{$item->id}}
                        </td>

                        <td left>
                            {{ $item->created_at->format('d-m-Y') }}
                        </td>

                        <td left>
                            {{ $item->user->name }}
                        </td>

                        <td left>
                            {{ $item->staff->no_identification }}
                        </td>

                        <td left>
                            {{ $item->staff->names }}
                        </td>

                        <td left>
                            {{ $item->staff->phone }}
                        </td>

                        <td left>
                            {{ $item->staff->email }}
                        </td>

                        <td left>
                            @formatToCop($item->price)
                        </td>

                        <td actions>
                            <x-buttons.download href="{{ route('admin.payroll.show', $item->id) }}" target="_blank" title="Descargar" />
                            <x-buttons.show wire:click="$emitTo('admin.payroll.show', 'openShow', {{ $item->id }})" title="Visualizar" />
                            <x-buttons.delete wire:click="$emit('deletePayroll', {{ $item->id }})" title="Eliminar" />
                        </td>

                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

    </x-commons.table-responsive>

    @if ($payroll->hasPages())
        <div class="p-3">
            {{ $payroll->links() }}
        </div>
    @endif

    <livewire:admin.payroll.create>
    <livewire:admin.payroll.show>
    <livewire:admin.staff.modal>

</div>

@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('deletePayroll', id => {
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


