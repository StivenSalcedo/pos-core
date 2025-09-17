<div>
    <x-wireui.modal wire:model.defer="openPresentations" max-width="md">
        <x-wireui.card title="Crear presentación">
            <div class="p-5">
                <x-wireui.errors />
                <div class="space-y-4">
                    <x-wireui.input label="Nombre" wire:model.defer="name" />
                    <x-wireui.input label="Cantidad" wire:model.defer="quantity" onlyNumbers/>
                    <x-wireui.input label="Precio" wire:model.defer="price" />
                </div>
            </div>

            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button secondary x-on:click="show=false" text="Cancelar" />
                    <x-wireui.button wire:click="addPresentation" text="Agregar" load textLoad="Agregando" />
                </div>
            </x-slot:footer>

        </x-wireui.card>
    </x-wireui.modal>
</div>
