<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="xl" closeModal="false">

        <x-wireui.card title="Componentes">

            <div class="mb-3">

                <div class="flex space-x-3">

                    <div class="w-full">
                        <x-wireui.input placeholder="Nombre del componente" wire:model.defer="name" />
                    </div>

                    @if ($update)

                        <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando" />

                        <x-wireui.button wire:click="cancel" text="Cancelar" danger load textLoad="Actualizando" />

                    @else

                        <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando" />

                    @endif

                </div>

                <x-wireui.error for="name" />

            </div>

            <x-commons.table-responsive>

                <table class="table-sm">
                    <thead >
                        <tr>
                            <th left>
                                Nombre
                            </th>
                            <th>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($components as $item)
                            <tr wire:key="component_id-{{ $item->id }}" @class(['', 'text-cyan-500 font-bold' => $item->id == $component_id])>

                                <td left>
                                    {{ $item->name }}
                                </td>

                                <td actions>
                                    <x-buttons.edit wire:click='edit({{ $item->id }})' />
                                </td>

                            </tr>
                        @empty
                            <x-commons.table-empty />
                        @endforelse
                    <tbody>
                </table>
            </x-commons.table-responsive>

            @if ($components->hasPages())
                <div class="p-3">
                    {{ $components->links() }}
                </div>
            @endif

            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button x-on:click="show=false" text="Cerrar" secondary />
                </div>
            </x-slot:footer>

        </x-wireui.card>

    </x-wireui.modal>
</div>
