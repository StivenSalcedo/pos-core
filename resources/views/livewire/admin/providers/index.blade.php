<div class="container">

    <x-commons.header>
        <x-wireui.button wire:click="exportProviders()" icon="excel" success text="Exportar a excel" load textLoad="Exportando..." />
        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.providers.create', 'openCreate')"  text="Crear proveedor" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Proveedores">
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select wire:model.defer="filter" optionKeyValue="true" :options="$filters" />
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        NIT
                    </th>
                    <th left>
                        Nombres
                    </th>
                    <th left>
                        Teléfono
                    </th>
                    <th left>
                        Dirección
                    </th>
                    <th>
                        Tipo
                    </th>
                    <th>
                        Email
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
                @forelse ($providers as $item)
                    <tr wire:key="provider{{ $item->id }}">
                        <td left>
                            {{$item->no_identification}}
                        </td>
                        <td left>
                            {{ $item->name }}
                        </td>
                        <td left>
                            {{ $item->phone }}
                        </td>
                        <td left>
                            {{ $item->direction }}
                        </td>
                        <td>
                            @if ($item->type)
                            {{$item->type->name}}
                            @endif
                        </td>
                        <td left>
                            {{ $item->email }}
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" />
                        </td>
                        <td>
                            <x-buttons.edit wire:click="$emitTo('admin.providers.edit', 'openEdit', {{ $item->id }})" title="Editar"/>
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

    </x-commons.table-responsive>

    @if ($providers->hasPages())
        <div class="p-3">
            {{ $providers->links() }}
        </div>
    @endif

    <livewire:admin.providers.create>
    <livewire:admin.providers.edit>

</div>


