<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="3xl" >
        <x-wireui.card title="Crear rango de numeración">

            <div class="space-y-4 p-5">

                <x-wireui.errors />

                <div class="grid grid-cols-3 gap-6">
                    <x-wireui.input label="Fecha de autorización" wire:model.defer="date_authorization" onlyNumbers  type="date" />
                    <x-wireui.input label="Fecha de venciemiento" wire:model.defer="expire" onlyNumbers  type="date" />
                    <x-wireui.input label="Prefijo" wire:model.defer="prefix" class="uppercase"/>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <x-wireui.input label="Número de resolución" wire:model.defer="resolution_number" onlyNumbers />
                    <x-wireui.input label="Desde" wire:model.defer="from" onlyNumbers />
                    <x-wireui.input label="Hasta" wire:model.defer="to" onlyNumbers />
                </div>
                
                <div class="grid grid-cols-3 gap-6">
                    <x-wireui.input label="Actual" wire:model.defer="current" onlyNumbers />
                </div>

            </div>

            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
                    <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando" />
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
