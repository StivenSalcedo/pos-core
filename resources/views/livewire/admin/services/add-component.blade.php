<div x-data>
    <x-wireui.modal wire:model.defer="openModal" max-width="2xl">
        <x-wireui.card title="Agregar componente al servicio">
            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-4">
                {{-- Componente --}}
                <div class="relative">
                    <x-wireui.native-select class="w-full" label="Componente" placeholder="Seleccione un componente" :options="$components"
                        wire:model.defer="form.component_id" optionKeyValue="true" />
                    <button class="absolute top-0 right-0" title="Crear nuevo componente"
                        wire:click='$emitTo("admin.components.create", "openCreate", "{{ $this->getName() }}")'>
                        <i class="ico icon-add text-blue-600 text-sm"></i>
                    </button>
                </div>
                {{-- Marca --}}
                <div class="relative">
                    <x-wireui.native-select class="w-full" label="Marca" placeholder="Seleccione una marca (opcional)"
                        :options="$brands" wire:model.defer="form.brand_id" optionKeyValue="true" />
                    <button class="absolute top-0 right-0" title="Crear nueva marca"
                        wire:click='$emitTo("admin.brands.create", "openCreate", "{{ $this->getName() }}")'>
                        <i class="ico icon-add text-blue-600 text-sm"></i>
                    </button>
                </div>
                <x-wireui.input label="Referencia" wire:model.defer="form.reference" />

                <x-wireui.input label="Capacidad" wire:model.defer="form.capacity" />

                <x-wireui.input label="Cantidad" type="number" min="1" wire:model.defer="form.quantity" />
            </div>

            <x-slot name="footer">
                <div class="text-right space-x-2">
                    <x-wireui.button secondary x-on:click="$wire.openModal = false" text="Cerrar" />
                    <x-wireui.button wire:click="save" text="Agregar" primary />


                </div>
            </x-slot>
        </x-wireui.card>
    </x-wireui.modal>
    <livewire:admin.components.create />
    <livewire:admin.brands.create />
</div>
