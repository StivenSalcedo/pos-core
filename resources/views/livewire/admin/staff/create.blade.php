<div>
    <x-wireui.modal wire:model.defer="openCreate" maxWidth="3xl">
        <x-wireui.card title="Crear empleado">

            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">
                <x-wireui.input onlyNumbers label="Identificación" wire:model.defer="no_identification" placeholder="Cédula / NIT"  />
                <x-wireui.input label="Nombres" wire:model.defer="names" placeholder="Nombre del empleado" />
                <x-wireui.input label="Dirección" wire:model.defer="direction" placeholder="Dirección del empleado"  />
                <x-wireui.input onlyNumbers label="Celular" wire:model.defer="phone" placeholder="Celular del empleado"  />
                <x-wireui.input label="Email" wire:model.defer="email" placeholder="Email del empleado"  />

                <div class="col-span-full">
                    <x-wireui.textarea label="Descripción" wire:model.defer="description" />
                </div>
            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                    <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando.." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
