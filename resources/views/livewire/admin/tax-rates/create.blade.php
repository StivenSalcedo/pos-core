<div>
  <x-wireui.modal wire:model.defer="openModal" max-width="md">

    <x-wireui.card title="Crear impuesto">

      <x-wireui.errors />

      <div class="grid gap-6 sm:grid-cols-1">

        <x-wireui.native-select wire:model.defer="tribute_id" :optionKeyValue="true" placeholder="Seleccionar tributo"
          :options="$tributes" class="w-full" />

        <x-wireui.input label="Nombre" wire:model.defer="name" />

        <x-buttons.switch-2 label="Valor Impuesto" wire:model="has_percentage" active="Porcentage" inactive="Pesos" />

        @if ($has_percentage)

        <x-wireui.input onlyNumbers label="Porcentaje" wire:model.defer="rate" />

        @else

        <x-wireui.input onlyNumbers label="Valor en pesos" wire:model.defer="rate" />

        @endif


      </div>

      <x-slot:footer>
        <div class="space-x-3 text-right">

          <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />

          <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando.." />

        </div>
      </x-slot:footer>

    </x-wireui.card>

  </x-wireui.modal>
</div>
