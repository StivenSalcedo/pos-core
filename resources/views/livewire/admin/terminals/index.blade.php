<div class="container">

    <x-commons.header>
        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.terminals.create', 'openCreate')"  text="Crear sede" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Sedes">
        </x-slot:top>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Nombre
                    </th>
                    <th left>
                        Rango de numeración
                    </th>
                    <th>
                        Usuarios asignados
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
                @forelse ($terminals as $item)
                    <tr wire:key="terminal-{{ $item->id }}">
                        <td left>
                            {{ $item->name }}
                        </td>
                        <td left>
                            {{ $item->numbering_range_name }}
                        </td>
                        <td>
                            {{ $item->users->count() }}
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" active="Activada" inactive="Desactivada"/>
                        </td>
                        <td actions>
                            <x-buttons.edit wire:click="$emitTo('admin.terminals.edit', 'openEdit', {{ $item->id }})" />
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    <livewire:admin.terminals.create>
    <livewire:admin.terminals.edit>
</div>
