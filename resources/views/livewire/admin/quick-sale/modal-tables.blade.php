<x-commons.modal-alpine>
  <x-wireui.card title="Mesas"
    x-data="alpineModalTables()"
    cardClasses="max-w-3xl mx-auto"
    @click.away="show=false">

    <div class="grid grid-cols-5 gap-4">
      <template x-for="(item, index) in orders"
        :key="'tables' + index">
        <div @click="updateTable(item)"
          class="relative flex flex-col items-center justify-center overflow-hidden rounded border px-2 py-2 text-sm"
          :class="{
              'bg-white hover:bg-slate-100 cursor-pointer': item.is_available,
              'bg-slate-200': !item
                  .is_available,
              '!bg-cyan-400 !cursor-default': item.id === order.id
          }">
          <span x-text="item.name"></span>
          <img src="{{ Storage::url('images/system/table.png') }}"
            class="h-14 object-cover object-center">

          <span x-show="order.id === item.id" class="font-bold text-white">Mesa actual</span>
          <span x-show="!item.is_available && order.id !== item.id ">Mesa ocupada</span>

        </div>
      </template>
    </div>
    <x-slot:footer>
      <div class="text-right">
        <x-wireui.button @click="show=false" text="Cerrar" secondary />
      </div>
    </x-slot:footer>
  </x-wireui.card>
</x-commons.modal-alpine>
