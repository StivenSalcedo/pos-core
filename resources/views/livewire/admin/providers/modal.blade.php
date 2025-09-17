<div>
    <x-wireui.modal wire:model.defer="openModal" >
        <x-wireui.card title="Lista de proveedores">

            <x-commons.table-responsive>

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
                                Nombre
                            </th>
                            <th left>
                                Email
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($providers as $item)
                            <tr wire:click="selected({{ $item->id }})" wire:key="provider{{ $item->id }}">
                                <td left>
                                    {{$item->no_identification}}
                                </td>
                                <td left>
                                    {{ $item->name }}
                                </td>
                                <td left>
                                    {{ $item->phone }}
                                </td>
                            </tr>
                        @empty
                            <x-commons.table-empty />
                        @endforelse
                    <tbody>
                </table>
            </x-commons.table-responsive>
        </x-wireui.card>
    </x-wireui.modal>
</div>




