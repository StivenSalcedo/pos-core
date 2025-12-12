<div>
    <x-wireui.modal wire:model.defer="openEdit" max-width="3xl">
        <x-wireui.card title="Actualizar proveedor">

            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">
                <x-wireui.input onlyNumbers label="NIT" wire:model.defer="provider.no_identification" placeholder="NIT"  />
                <x-wireui.input label="Nombres" wire:model.defer="provider.name" placeholder="Nombre del proveedor" />
                <x-wireui.input label="Dirección" wire:model.defer="provider.direction" placeholder="Dirección del proveedor"  />
                <x-wireui.input onlyNumbers label="Celular" wire:model.defer="provider.phone" placeholder="Celular del proveedor"  />
                <x-wireui.native-select optionKeyValue label="Tipo" wire:model.defer="provider.type" class="w-full" optionKeyValue="true" placeholder="Seleccionar el tipo de proveedor" :options="$types" />
                <x-wireui.input label="Email" wire:model.defer="provider.email" placeholder="Email del proveedor" />
                <div class="col-span-full">
                    <x-wireui.textarea label="Descripción" wire:model.defer="provider.description" placeholder="Agregue una descripción del proveedor" />
                </div>
            </div>

            <x-slot:footer>
                <div class="flex justify-between items-center">
                    <x-buttons.switch wire:model="provider.status" />
                    <div class="space-x-3">
                        <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                        <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actuzalizando.." />
                    </div>
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
