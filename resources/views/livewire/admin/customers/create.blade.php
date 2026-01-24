<div>

    <x-wireui.modal wire:model.defer="openCreate" max-width="4xl">
        <x-wireui.card title="Crear cliente">
            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">

                <x-wireui.native-select label='Documento de identidad' name="identification_document_id"
                    wire:model="identification_document_id" optionKeyValue="true" :options="$identificationDocuments" class="w-full" />

                <div class="flex gap-x-4">
                    <div class="flex-1">
                        <x-wireui.input label="N° Identificación" name="no_identification"
                            wire:model.defer="no_identification"
                            x-on:input="$event.target.value = $event.target.value.replace(/\D+/g, '')" class="w-full" />
                    </div>


                    <div class="w-20">
                        @if ($identification_document_id == '6')
                            <x-wireui.input maxlength="1" label="DV" name='dv' wire:model.defer="dv"
                                x-on:input="$event.target.value = $event.target.value.replace(/\D+/g, '')" />
                        @endif
                    </div>

                </div>

                <div class="grid sm:grid-cols-2 gap-6 col-span-2">
                    @if ($identification_document_id == '6')
                        <x-wireui.native-select wire:key='identification_document_id' label='Tipo de persona'
                            wire:model.defer="legal_organization" optionKeyValue="true" :options="$legalOrganizations"
                            class="w-full" />

                        <x-wireui.native-select wire:key='tribute' label='Responsabilidad tributaria'
                            wire:model.defer="tribute" optionKeyValue="true" :options="$tributes" class="w-full" />
                    @endif
                </div>
                @if ($identification_document_id != '6')
                    <x-wireui.input label="Nombres y apellidos" wire:model.defer="names" />
                @endif
                @if ($identification_document_id == '6')
                    <x-wireui.input label="Nombre Empresa" wire:model.defer="names" />
                @endif

                <x-wireui.input label="Dirección" wire:model.defer="direction" />
                <x-wireui.input label="Barrio" wire:model.defer="neighborhood" />




                <x-wireui.input label="Celular" wire:model.defer="phone" />

                <x-wireui.input label="Email" wire:model.defer="email" />

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
