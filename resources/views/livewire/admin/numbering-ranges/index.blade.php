<div class="container">

    <x-commons.header>
        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.numbering-ranges.create', 'openCreate')"  text="Crear Rango" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Rangos de numeración">
        </x-slot:top>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Prefix
                    </th>
                    <th center>
                        Desde
                    </th>
                    <th center>
                        Hasta
                    </th>
                    <th center>
                        Actual
                    </th>
                    <th>
                        Resolución
                    </th>
                    <th>
                        Fecha de autorización
                    </th>
                    <th>
                        Vencimiento
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
                @forelse ($ranges as $item)
                    <tr wire:key="numbering-{{ $item->id }}">
                        <td left>
                            {{ $item->prefix }}
                        </td>
                        <td center>
                            {{ $item->from }}
                        </td>
                        <td center>
                            {{ $item->to }}
                        </td>
                        <td center>
                            {{ $item->current }}
                        </td>
                        <td>
                            {{ $item->resolution_number }}
                        </td>
                        <td>
                            {{ $item->date_authorization->format('d-m-Y') }}
                        </td>
                        <td>
                            {{ $item->expire->format('d-m-Y') }}
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" active="Activado" inactive="Desactivado" />
                        </td>
                        <td actions>
                            <x-buttons.edit wire:click="$emitTo('admin.numbering-ranges.edit', 'openEdit', {{ $item->id }})" />
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

    </x-commons.table-responsive>

    <livewire:admin.numbering-ranges.create>

    <livewire:admin.numbering-ranges.edit>

</div>
