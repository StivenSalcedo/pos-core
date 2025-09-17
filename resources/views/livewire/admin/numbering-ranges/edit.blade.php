<div>
    <x-wireui.modal wire:model.defer="openEdit" max-width="3xl" >
        <x-wireui.card title="Editar rango de numeración">

            <div class="space-y-4 p-5">

                <x-wireui.errors />

                <div class="grid grid-cols-3 gap-6">
                    <x-wireui.input label="Fecha de autorización" wire:model.defer="date_authorization" onlyNumbers  type="date" />
                    <x-wireui.input label="Fecha de venciemiento" wire:model.defer="expire" onlyNumbers  type="date" />
                    <x-wireui.input label="Prefijo" wire:model.defer="range.prefix" class="uppercase"/>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <x-wireui.input label="Número de resolución" wire:model.defer="range.resolution_number" onlyNumbers />
                    <x-wireui.input label="Desde" wire:model.defer="range.from" onlyNumbers />
                    <x-wireui.input label="Hasta" wire:model.defer="range.to" onlyNumbers />
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <x-wireui.input label="Actual" wire:model.defer="range.current" onlyNumbers />
                </div>
            </div>

            <x-slot:footer>
                <div class="flex justify-between">
                    <x-buttons.switch wire:model.defer="range.status" active="Activado" inactive="Desactivado"/>
                    <div>
                        <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
                        <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando" />
                    </div>
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
