<div>
    <x-wireui.modal wire:model.defer="openCreate" >
        <x-wireui.card title="Agregar egreso">

            <x-wireui.errors />

            <div class="space-y-6">
                <x-wireui.input label="Fecha" onkeydown="return false" wire:model.defer="date" type="date"  />
                <x-wireui.input label="Motivo" wire:model.defer="reason" />
                <x-wireui.input onlyNumbers label="Valor" wire:model.defer="price" />
                <x-wireui.textarea label="Descripción" wire:model.defer="description" />
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
