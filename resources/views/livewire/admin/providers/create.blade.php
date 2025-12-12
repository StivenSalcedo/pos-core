<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="3xl">
        <x-wireui.card title="Crear proveedor">

            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">
                <x-wireui.input onlyNumbers label="NIT" wire:model.defer="no_identification" placeholder="NIT" />
                <x-wireui.input label="Nombres" wire:model.defer="name" placeholder="Nombre del proveedor" />
                <x-wireui.input label="Dirección" wire:model.defer="direction" placeholder="Dirección del proveedor" />
                <x-wireui.input onlyNumbers label="Celular" wire:model.defer="phone" placeholder="Celular del proveedor" />
                <x-wireui.native-select optionKeyValue label="Tipo" wire:model.defer="type" class="w-full" optionKeyValue="true" placeholder="Seleccionar el tipo de proveedor" :options="$types" />
                <x-wireui.input label="Email" wire:model.defer="email" placeholder="Email del proveedor"  />
                <div class="col-span-full">
                    <x-wireui.textarea label="Descripción" wire:model.defer="description" placeholder="Agregue una descripción del proveedor" />
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
