<div x-data>
    <x-wireui.modal wire:model.defer="openModal" max-width="2xl">
        <x-wireui.card title="Agregar componente al servicio">
            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-4">
                <x-wireui.native-select 
                    label="Componente"
                    placeholder="Seleccione un componente"
                    :options="$components"
                    wire:model.defer="form.component_id"
                     optionKeyValue="true"
                />
                 

                <x-wireui.native-select 
                    label="Marca"
                    placeholder="Seleccione una marca (opcional)"
                    :options="$brands"
                    wire:model.defer="form.brand_id"
                     optionKeyValue="true"
                />

                <x-wireui.input 
                    label="Referencia" 
                    wire:model.defer="form.reference"
                />

                <x-wireui.input 
                    label="Capacidad" 
                    wire:model.defer="form.capacity"
                />

                <x-wireui.input 
                    label="Cantidad" 
                    type="number" 
                    min="1"
                    wire:model.defer="form.quantity"
                />
            </div>

            <x-slot name="footer">
                <div class="text-right space-x-2">
                    <x-wireui.button secondary x-on:click="$wire.openModal = false" text="Cerrar" />
                    <x-wireui.button wire:click="save" text="Agregar" primary />

                    
                </div>
            </x-slot>
        </x-wireui.card>
    </x-wireui.modal>
</div>