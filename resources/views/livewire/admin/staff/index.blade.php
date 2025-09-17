<div class="container">

    <x-commons.header>
        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.staff.create', 'openCreate')"  text="Crear empleado" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Empleados">
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select wire:model.defer="filter" optionKeyValue :options="$filters" />
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Cédula / NIT
                    </th>
                    <th left>
                        Nombres
                    </th>
                    <th left>
                        Celular
                    </th>
                    <th left>
                        Email
                    </th>
                    <th left>
                        Dirección
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
                @forelse ($staff as $item)
                    <tr wire:key="staff{{ $item->id }}">
                        <td left>
                            {{$item->no_identification}}
                        </td>
                        <td left>
                            {{ $item->names }}
                        </td>
                        <td left>
                            {{ $item->phone }}
                        </td>
                        <td left>
                            {{ $item->email }}
                        </td>
                        <td left>
                            {{ $item->direction }}
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" />
                        </td>
                        <td>
                            <x-buttons.edit wire:click="$emitTo('admin.staff.edit', 'openEdit', {{ $item->id }})" title="Editar" />
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

    </x-commons.table-responsive>

    @if ($staff->hasPages())
        <div class="p-3">
            {{ $staff->links() }}
        </div>
    @endif

    <livewire:admin.staff.create>

    <livewire:admin.staff.edit>

</div>

@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('deleteStaff', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires eliminar este empleado?',
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

