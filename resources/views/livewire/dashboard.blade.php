<div class="px-2 pt-2 pb-10">
 

  <div class="flex justify-end items-end gap-x-4">

    <div class="flex gap-x-4">
      @if ($filterDate === 8)
      <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
      <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
      @endif
    </div>

    <div>
      <x-wireui.range-date wire:model="filterDate" />
    </div>
  </div>

  <p class="text-center text-xl text-red-600 font-semibold">Importante</p>

  <h1 class="text-center font-semibold">
    Los datos presentados en esta vista se están calculando basándose en las ventas realizadas del año en curso.
  </h1>

  <div class="flex flex-wrap justify-center gap-8 mt-10">

    <div class="w-80 h-36 flex flex-col items-center justify-center border rounded-2xl bg-indigo-700/90 text-white">
      <i class="ico icon-money text-7xl text-indigo-800"></i>
      <span class="-mt-3">Costos</span>
      <span class="font-semibold text-2xl">@formatToCop($costTotal)</span>
    </div>

    <div class="w-80 h-36 flex flex-col items-center justify-center border rounded-2xl bg-blue-700/90 text-white">
      <i class="ico icon-money text-7xl text-blue-800"></i>
      <span class="-mt-3">Ventas</span>
      <span class="font-semibold text-2xl">@formatToCop($saleTotal)</span>
    </div>

  </div>
 
</div>
