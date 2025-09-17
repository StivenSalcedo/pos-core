<div class="">
    <x-wireui.modal wire:model.defer="openModal" >
        <x-wireui.card title="Lista de productos" cardClasses="relative overflow-hidden">

            <x-commons.table-responsive class="overflow-hidden">

                <x-slot:header>
                    <x-wireui.search placeholder="Buscar..." style="max-width: 16rem"/>
                    <x-wireui.native-select wire:model.defer="filter" optionKeyValue="true" :options="$filters" />
                </x-slot:header>

                <table class="table">
                    <thead >
                        <tr>
                            <th left>
                                Referencia
                            </th>
                            <th left>
                                Nombre
                            </th>
                            <th>
                                Stock
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $item)
                            <tr wire:click="selected({{ $item->id }})" wire:key="customer{{ $item->id }}" >
                                <td left>
                                    {{$item->reference}}
                                </td>
                                <td left>
                                    {{ $item->name }}
                                </td>
                                <td>
                                    {{ $item->stockUnitsLabel }}
                                </td>
                            </tr>
                        @empty
                            <x-commons.table-empty />
                        @endforelse
                    <tbody>
                </table>
            </x-commons.table-responsive>

            <x-loads.panel text="Cargando..." wire:loading />

        </x-wireui.card>
    </x-wireui.modal>


</div>




