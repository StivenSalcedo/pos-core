<div x-data="alpinePresentations()">
  <div x-show="show_presentations"
    @click.self="show_presentations = false"
    class="fixed inset-0 z-30 flex items-center justify-center bg-slate-800 bg-opacity-40">

    <x-wireui.card title="Presentaciones"
      cardClasses="max-w-lg"
      padding="p-0">
      <div>
        <ul class="divide-y rounded bg-white">
          <template x-for="(item, index) in presentations">
            <li class="cursor-pointer px-4 py-2 text-center font-semibold text-slate-800 hover:bg-slate-100"
              x-on:click="setPresentation(item)">
              <span x-text="item.name"></span>
              -
              <span x-text="formatToCop(item.price)"
                class="text-green-600"></span>
            </li>
          </template>
        </ul>
      </div>

      <x-slot:footer>

        <div class="flex justify-end">
          <x-wireui.button x-on:click='show_presentations=false'
            text="Cerrar"
            secondary />
        </div>

      </x-slot:footer>

    </x-wireui.card>

  </div>
</div>
