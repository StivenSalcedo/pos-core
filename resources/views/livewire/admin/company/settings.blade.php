<div>

    <x-wireui.card title="Información de la empresa" description="Aquí puedes actualizar el logo y la información de tu empresa">

        <x-wireui.errors />

        <div class="grid sm:grid-cols-2 gap-6">
            
            <div class="flex-1 col-span-full">
                <span class="mt-2 text-xs block">Tamaño máximo de la imagen debe ser de 500KB y con unas dimensiones que no superen (500px x 250px) </span>
            </div>
            <div class="col-span-full flex flex-col justify-center items-center">
                <div class="outline outline-offset-4 rounded outline-1 outline-slate-400 flex justify-center items-center w-full h-full overflow-hidden" style="max-width: 300px; max-height: 150px">
                    <img class="object-cover object-center h-full" src="{{ $this->getUrlLogo() }}" >
                </div>

                <div x-data="loadFile()" x-bind="loading" class="flex flex-col items-center justify-center mt-2 flex-1">

                    <div x-cloak x-show="isUploading" class="w-full bg-gray-200 h-2 mb-1 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-2" :style="`width: ${progress}%;`"></div>
                    </div>

                    <input type="file" x-ref="file" wire:model="preLogo" class="hidden">

                    <button x-on:click="$refs.file.click()" class=" px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-70">
                        Seleccionar logo
                    </button>

                </div>
            </div>

            @if ($this->updateOrCreate())
                <x-wireui.input wire:model.defer="company.nit" icon="user" placeholder="NIT de la empresa" />
                <x-wireui.input wire:model.defer="company.name" icon="city" placeholder="Nombre de la empresa" />
                <x-wireui.input wire:model.defer="company.direction" icon="direction" placeholder="Dirección de la empresa" />
                <x-wireui.input wire:model.defer="company.phone" icon="phone" placeholder="N° celular de la empresa" />
                <x-wireui.input wire:model.defer="company.email" icon="email" placeholder="Correo de la empresa" />
            @else
                <x-wireui.input wire:model.defer="nit" icon="user" placeholder="NIT de la empresa" />
                <x-wireui.input wire:model.defer="name" icon="city" placeholder="Nombre de la empresa" />
                <x-wireui.input wire:model.defer="direction" icon="direction" placeholder="Dirección de la empresa" />
                <x-wireui.input wire:model.defer="phone" icon="phone" placeholder="N° celular de la empresa" />
                <x-wireui.input wire:model.defer="email" icon="email" placeholder="Correo de la empresa" />
                <x-wireui.native-select wire:model.defer="type_bill"  optionKeyValue="true" placeholder="Tipo de factura" :options="['0' => 'Factura normal', '1' => 'Ticket']"  class="w-full" />
                <x-buttons.switch wire:model.defer="barcode" active="Pistola activada" inactive="Pistola desactivada"/>
            @endif
        </div>

        <x-slot:footer>
            <div class="text-right">
                @if ($this->updateOrCreate())
                    <div>
                        <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando..." />
                    </div>
                @else
                    <section>
                        <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando..." />
                    </section>
                @endif
            </div>
        </x-slot:footer>
    </x-wireui.card>
</div>
