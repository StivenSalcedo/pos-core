<div class="container">

    <div class="pt-2">
        <x-wireui.button href="{{ route('admin.home') }}" icon="arrow-l" text="Volver" />
    </div>

    <div class="max-with max-w-4xl mx-auto">

        <x-commons.header>
            <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.roles.create', 'openCreate')"  text="Crear rol" />
        </x-commons.header>

        <x-commons.table-responsive>

            <x-slot:top title="Roles y permisos">
            </x-slot:top>

            <table class="table">
                <thead>
                    <tr>
                        <th left>
                            Nombre
                        </th>
                        <th>
                            Usuarios
                        </th>
                        <th>
                            Permisos
                        </th>
                        <th>
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $item)
                        <tr wire:key="customer{{ $item->id }}">
                            <td left>
                                {{ $item->name }}
                            </td>
                            <td>
                                {{ $item->users_count }}
                            </td>
                            <td>
                                {{ $item->permissions_count }}
                            </td>
                            <td actions>
                                @if ($item->name !== 'Administrador')
                                    <x-buttons.edit wire:click="$emitTo('admin.roles.edit', 'openEdit', {{ $item->id }})" />
                                    <x-buttons.delete wire:click="$emit('deleteRole', {{ $item->id }})" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <x-commons.table-empty />
                    @endforelse
                <tbody>
            </table>
        </x-commons.table-responsive>

    </div>

    <livewire:admin.roles.create>

    <livewire:admin.roles.edit>

</div>


@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('deleteRole', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires eliminar este cliente?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.destroy(id);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
@endpush
