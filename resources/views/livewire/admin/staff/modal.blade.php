<div>
    <x-wireui.modal wire:model.defer="openModal" >
        <x-wireui.card title="Lista de empleados">

            @if ($staff->hasPages())
                <div class="pb-2">
                    {{ $staff->links() }}
                </div>
            @endif

            <x-commons.table-responsive>

                <x-slot:header>
                    <x-wireui.search placeholder="Buscar..." />
                    <x-wireui.native-select wire:model.defer="filter" optionKeyValue="true" :options="$filters" />
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
                                Email
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $item)
                            <tr wire:click="selected({{ $item->id }})" wire:key="staff{{ $item->id }}">
                                <td left>
                                    {{$item->no_identification}}
                                </td>
                                <td left>
                                    {{ $item->names }}
                                </td>
                                <td left>
                                    {{ $item->email }}
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




