<div >
    <x-wireui.modal wire:model.defer="openEdit" max-width="3xl">
        <x-wireui.card title="Actualizar cliente">

            <div>
                <x-wireui.errors />
            </div>

            <div class="grid sm:grid-cols-2 gap-6">

                <x-wireui.native-select label='Documento de identidad' name="customer.identification_document_id"
                    wire:model="customer.identification_document_id" optionKeyValue="true" :options="$identificationDocuments" class="w-full" />

                <div class="flex gap-x-4">
                    <div class="flex-1">
                        <x-wireui.input label="N° Identificación" name="customer.no_identification"
                            wire:model.defer="customer.no_identification"
                            x-on:input="$event.target.value = $event.target.value.replace(/\D+/g, '')" />
                    </div>


                    <div class="w-20">
                        @if ($customer->identification_document_id == '6')
                            <x-wireui.input label="DV" wire:model.defer="customer.dv" name='customer.dv'
                                x-on:input="$event.target.value = $event.target.value.replace(/\D+/g, '')" />
                        @endif
                    </div>

                </div>

                <div class="grid sm:grid-cols-2 gap-6 col-span-2">
                    @if ($customer->identification_document_id == '6')
                        <x-wireui.native-select label='Tipo de persona' wire:model.defer="customer.legal_organization"
                            optionKeyValue="true" :options="$legalOrganizations" class="w-full" />
                        <x-wireui.native-select label='Responsabilidad tributaria' wire:model.defer="customer.tribute"
                            optionKeyValue="true" :options="$tributes" class="w-full" />
                    @endif
                </div>
                @if ($customer->identification_document_id != '6')
                    <x-wireui.input label="Nombres y apellidos" wire:model.defer="customer.names" />
                @endif
                @if ($customer->identification_document_id == '6')
                    <x-wireui.input label="Nombre empresa" wire:model.defer="customer.names" />
                @endif

                <x-wireui.input label="Dirección" wire:model.defer="customer.direction" />
                <x-wireui.input label="Barrio" wire:model.defer="customer.neighborhood" />
                <x-wireui.input label="Celular" wire:model.defer="customer.phone" />

                <x-wireui.input label="Email" wire:model.defer="customer.email" />

                <div wire:key='destacar'>
                    <x-buttons.switch label="Destacar" wire:model="customer.top" active="sí" inactive="no" />
                </div>

                <div wire:key='estado'>
                    <x-buttons.switch label="Estado" wire:model="customer.status" active="activo" inactive="Inactivo" />
                </div>

            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                    <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actuzalizando.." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>