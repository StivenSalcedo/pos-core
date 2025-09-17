<div>
  <x-wireui.card title="Configuración de factura">

    <x-wireui.errors />

    <div class="space-y-6">

      <x-wireui.native-select label="Tipo de factura"
        wire:model.defer="company.type_bill"
        optionKeyValue="true"
        placeholder="Seleccionar    "
        :options="['0' => 'Factura normal', '1' => 'Ticket']"
        class="w-full" />

      <div class="flex justify-between">
        <p class="text-sm text-slate-600">Activa o desactiva el uso del lector de código de barras</p>
        <x-buttons.switch wire:model.defer="company.barcode"
          active="Activado"
          inactive="Desactivado"
          width="w-24" />
      </div>

      <x-wireui.input label="Tamaño del ticket en centímetros"
        wire:model.defer="company.width_ticket"
        class="w-20 text-right"
        onlyNumbers />

      <x-wireui.input label="Porcentaje de propina"
        wire:model.defer="company.percentage_tip"
        class="w-20 text-right"
        onlyNumbers />

    </div>
    <x-slot:footer>
      <div class="text-right">
        <x-wireui.button wire:click="update"
          text="Actualizar"
          load
          textLoad="Actualizando..." />
      </div>
    </x-slot:footer>
  </x-wireui.card>
</div>
