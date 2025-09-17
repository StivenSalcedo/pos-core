<div class="container">

    <x-commons.header>
        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.users.create', 'openCreate')"  text="Crear usuario" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Usuarios">
        </x-slot:top>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Nombres
                    </th>
                    <th left>
                        Email
                    </th>
                    <th left>
                        Celular
                    </th>
                    <th>
                        Rol
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
                @forelse ($users as $item)
                    <tr wire:key="user-{{ $item->id }}">
                        <td left>
                            {{ $item->name }}
                        </td>
                        <td left>
                            {{ $item->email }}
                        </td>
                        <td left>
                            {{ $item->phone }}
                        </td>
                        <td>
                            {{ __($item->role) }}
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" />
                        </td>
                        <td actions>
                            <x-buttons.edit wire:click="$emitTo('admin.users.edit', 'openEdit', {{ $item->id }})" title="Editar"/>
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    <livewire:admin.users.create>

    <livewire:admin.users.edit>

</div>
