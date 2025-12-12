<div x-data="createCustomer">

    <x-wireui.modal wire:model.defer="openCreate" max-width="4xl">
        <x-wireui.card title="Crear cliente">
            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">

                <x-wireui.native-select label='Documento de identidad' name="identification_document_id"
                    x-model="identification_document_id" optionKeyValue="true" :options="$identificationDocuments" class="w-full" />

                <div class="flex gap-x-4">
                    <div class="flex-1">
                        <x-wireui.input label="N° Identificación" name="no_identification" x-model="no_identification"
                            x-on:keypress="onlyNumbers($event, allowOnlyNumbers())" class="w-full" />
                    </div>

                    <template x-if="identification_document_id === '6'">
                        <div class="w-20">
                            <x-wireui.input label="DV" x-model="dv" name='dv'
                                x-on:keypress="onlyNumbers($event)" />
                        </div>
                    </template>
                </div>

                <div x-show="identification_document_id == '6'" class="grid sm:grid-cols-2 gap-6 col-span-2">
                    <x-wireui.native-select wire:key='identification_document_id' label='Tipo de persona'
                        wire:model.defer="legal_organization" optionKeyValue="true" :options="$legalOrganizations" class="w-full" />

                    <x-wireui.native-select wire:key='tribute' label='Responsabilidad tributaria'
                        wire:model.defer="tribute" optionKeyValue="true" :options="$tributes" class="w-full" />

                </div>
                <div x-show="identification_document_id != '6'">
                    <x-wireui.input label="Nombres y apellidos" wire:model.defer="names" />
                </div>
                <div x-show="identification_document_id == '6'">
                    <x-wireui.input label="Nombre Empresa" wire:model.defer="names" />
                </div>

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

@push('js')
    <script lang="js">
        function createCustomer() {
            return {
                foreingDocuments: ['7', '8', '10'],
                dv: @entangle('dv').defer,
                identification_document_id: @entangle('identification_document_id').defer,
                no_identification: @entangle('no_identification').defer,

                init() {
                    this.$watch('identification_document_id', (value, oldValue) => {
                        this.calc()
                    })

                    this.$watch('no_identification', (value, oldValue) => {
                        this.calc()
                    })
                },

                calc() {
                    if (this.identification_document_id !== '6') return (this.dv = '')

                    let nit = this.no_identification
                    let isNitValid = nit >>> 0 === parseFloat(nit) ? !0 : !1

                    if (isNitValid) {
                        this.dv = calculateCheckDigit(nit)
                    }
                },

                allowOnlyNumbers() {
                    return this.foreingDocuments.includes(this.identification_document_id)
                },
            }
        }
    </script>
@endpush
