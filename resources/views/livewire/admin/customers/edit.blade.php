<div x-data="editCustomer()">
  <x-wireui.modal wire:model.defer="openEdit" max-width="3xl">
    <x-wireui.card title="Actualizar cliente">

      <div>
        <x-wireui.errors />
      </div>

      <div class="grid sm:grid-cols-2 gap-6">

        <x-wireui.native-select label='Documento de identidad' name="customer.identification_document_id"
          x-model="identification_document_id" optionKeyValue="true" :options="$identificationDocuments"
          class="w-full" />

        <div class="flex gap-x-4">
          <div class="flex-1">
            <x-wireui.input label="N° Identificación" name="customer.no_identification"
              x-model.defer="no_identification" x-on:keypress="onlyNumbers($event, allowOnlyNumbers())" />
          </div>

          <template x-if="identification_document_id == '6'">
            <div class="w-20">
              <x-wireui.input label="DV" x-model="dv" name='customer.dv' x-on:keypress="onlyNumbers($event)" />
            </div>
          </template>
        </div>

        <div x-show="identification_document_id == '6'" class="grid sm:grid-cols-2 gap-6 col-span-2">
          <x-wireui.native-select label='Tipo de persona' wire:model.defer="customer.legal_organization"
            optionKeyValue="true" :options="$legalOrganizations" class="w-full" />
          <x-wireui.native-select label='Responsabilidad tributaria' wire:model.defer="customer.tribute"
            optionKeyValue="true" :options="$tributes" class="w-full" />
        </div>

        <x-wireui.input label="Nombres y apellidos" wire:model.defer="customer.names" />

        <x-wireui.input label="Dirección" wire:model.defer="customer.direction" />

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

@push('js')
<script lang="js">
  function editCustomer() {
    return {
      foreingDocuments: ['7', '8', '10'],
      dv: @entangle('customer.dv').defer,
      identification_document_id: @entangle('customer.identification_document_id').defer,
      no_identification: @entangle('customer.no_identification').defer,

      init(){
        this.$watch('identification_document_id', (value, oldValue) => {
          this.calc()
        })

        this.$watch('no_identification', (value, oldValue) => {
          this.calc()
        })
      },

      calc(){
        if (this.identification_document_id != '6') return (this.dv = '')

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
