<div class="container">

    <x-commons.header >
        <x-wireui.button wire:click="exportCustomers" icon="excel" success text="Exportar a excel" load textLoad="Exportando..." />
         <x-wireui.button icon="excel" x-on:click="$wire.emitTo('admin.customers.import', 'openImport')"  success text="Importar desde excel" />
        <x-wireui.button icon="user" x-on:click="$wire.emitTo('admin.customers.create', 'openCreate')"  text="Crear Cliente" />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Clientes">
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select wire:model.defer="filter" optionKeyValue :options="$filters" />
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Identificación
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
                    <th>
                        Estado
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $item)
                    <tr wire:key="customer-{{ $item->id }}">
                        <td left class="{{ !$item->top ? 'text-green-500 leading-none' : 'leading-none'  }}">
                            <span class="text-xs font-bold leading-none">{{ $item->identificationDocument->name}}</span>
                            <br>
                            <span class="leading-none">
                              {{$item->format_no_identification}}
                            </span>
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
                        <td>
                            <x-commons.status :status="$item->status" />
                        </td>
                        <td actions>
                            <x-buttons.edit wire:click="$emitTo('admin.customers.edit', 'openEdit', {{ $item->id }})" title="Editar"/>
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    @if ($customers->hasPages())
        <div class="p-3">
            {{ $customers->links() }}
        </div>
    @endif

    <livewire:admin.customers.create>

    <livewire:admin.customers.edit>

    <livewire:admin.customers.import>

</div>
