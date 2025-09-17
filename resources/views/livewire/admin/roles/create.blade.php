<div>
    <x-wireui.modal wire:model.defer="open" >
        <x-wireui.card title="Crear rol">
            <div>

                <x-wireui.errors />

                <x-wireui.input wire:model.defer="name" icon="user-lock" placeholder="Nombre del rol"  />

                <div>
                    <h1 class="font-semibold mt-3">Selecciona los permisos que va a tener el rol</h1>
                    <div class="mt-1 pl-4">
                        <span class="font-semibold">Módulos</span>
                        <ul class="select-none pl-2 space-y-1 mt-1">
                            @foreach ($permissions as $item)
                                <li>
                                    <label class="cursor-pointer hover:font-semibold">
                                        <input type="checkbox" wire:model.defer="permissionsSelected.{{ $item }}" value="true" >
                                        {{ Str::ucfirst($item) }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>

            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button x-on:click="show=false" text="Cerrar" secondary />
                    <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando..." />
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
