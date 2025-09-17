<div>
  <x-wireui.modal wire:model.defer="show" max-width="4xl">
    <x-wireui.card title="Impuestos" cardClasses="relative">

      <x-wireui.errors />

      <div>
        <x-wireui.native-select label="Impuesto" wire:model="selectedTax" optionKeyValue="true"
          placeholder="Seleccionar un impuesto" :options="$formatTaxRates" class="w-full" />
      </div>

      <div>
        @if (count($tax))
          <hr class="my-4">
          <div>

            <x-wireui.input label="Nombre" readonly wire:model.defer="tax.format_name" />

            <div class="grid {{ $tax['has_percentage'] ? 'grid-cols-1' : 'grid-cols-2' }} gap-3 mt-3">

              @if ($tax['has_percentage'])
                <x-wireui.input label="Impuesto en porcentaje" readonly wire:model.defer="tax.rate" />
              @endif

              @if (!$tax['has_percentage'])
                <x-wireui.input label="Impuesto en pesos" readonly wire:model="tax.rate" />
                <x-wireui.input label="Militros del producto" wire:model.defer="milliliter" />
              @endif

              <div class="col-span-full flex justify-end">
                <x-wireui.button wire:click="add" text="Agregar" load textLoad="Agregando.." />
              </div>

            </div>
          </div>
        @endif
      </div>

      <hr class="my-6">

      <div class="">
        <h1 class="font-medium">Impuestos agregados</h1>
        <div class="mt-2 border rounded divide-y">
          <ul class="flex bg-slate-200 rounded">
            <li class="text-center font-semibold w-full py-0.5">
              Impuesto
            </li>
            <li class="text-center font-semibold w-72 py-0.5">
              Porcentaje / Pesos
            </li>
            <li class="text-center font-semibold min-w-14 py-0.5">

            </li>
          </ul>
          @forelse($selectedTaxes as $key => $item)
            <ul class="flex text-sm">
              <li class="w-full py-0.5 text-center">
                {{ $item['format_name2'] }}
              </li>
              <li class="py-0.5 text-center w-72">
                {{ $item['has_percentage'] ? '%' : '$' }}
                {{ $item['rate'] }}
              </li>
              <li class="min-w-14 py-0.5 text-center">
                <button wire:click='remove({{ $key }})'>
                  <i class="ti ti-trash text-red-500 text-xl"></i>
                </button>
              </li>
            </ul>

          @empty
            <p class="text-center mt-2 text-sm">
              No se encontraron impuestos agregados
            </p>
          @endforelse
        </div>
      </div>

      <x-slot:footer>
        <div class="text-right space-x-3">
          <x-wireui.button x-on:click="show=false" text="Aceptar" />
        </div>
      </x-slot:footer>

      <x-loads.panel wire:loading text="Cargando..." />

    </x-wireui.card>
  </x-wireui.modal>
</div>
