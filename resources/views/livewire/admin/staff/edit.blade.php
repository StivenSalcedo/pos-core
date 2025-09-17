<div>
    <x-wireui.modal wire:model.defer="openEdit" >
        <x-wireui.card title="Actualizar empleado">

            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">
                <x-wireui.input onlyNumbers label="Identificación" wire:model.defer="staff.no_identification" placeholder="Cédula / NIT"  />
                <x-wireui.input label="Nombres" wire:model.defer="staff.names" placeholder="Nombre del cliente" />
                <x-wireui.input label="Dirección" wire:model.defer="staff.direction" placeholder="Dirección del cliente"  />
                <x-wireui.input onlyNumbers label="Celular" wire:model.defer="staff.phone" placeholder="Teléfono del cliente"  />
                <x-wireui.input label="Email" wire:model.defer="staff.email" placeholder="Email del cliente"  />
                <div class="col-span-full">
                    <x-wireui.textarea label="Descripción" wire:model.defer="staff.description" />
                </div>
            </div>

            <x-slot:footer>
                <div class="flex justify-between items-center">
                    <x-buttons.switch wire:model="staff.status" />
                    <div class="text-right space-x-3">
                        <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                        <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizado.." />
                    </div>
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
