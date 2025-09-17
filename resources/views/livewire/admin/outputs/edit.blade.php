<div>
    <x-wireui.modal wire:model.defer="openEdit" >
        <x-wireui.card title="Actualizar egreso">

            <x-wireui.errors />

            <div class="space-y-6">
                <x-wireui.input label="Fecha" wire:model.defer="date" onkeydown="return false" type="date"  />
                <x-wireui.native-select label="De donde quieres sacar el dinero" wire:model.defer="output.from"  optionKeyValue="true" placeholder="Selecciona una opción" :options="$cashRegisters"  class="w-full" />
                <x-wireui.input label="Motivo" wire:model.defer="output.reason" />
                <x-wireui.input onlyNumbers label="Valor" wire:model.defer="output.price" />
                <x-wireui.textarea label="Descripción" wire:model.defer="output.description" />
            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                    <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando.." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
