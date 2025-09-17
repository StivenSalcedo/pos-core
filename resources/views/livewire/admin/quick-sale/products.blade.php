<div x-data="alpineProducts()">

  <x-wireui.card padding="p-0">

    <div class="pt-2">

      <x-slot:header>

      </x-slot:header>

      <div class="flex space-x-2 pl-2">
        <div class="mb-1 px-1 pt-1">

          <x-commons.search id="searchProduct" placeholder="Buscar producto" x-ref="search" x-model="search"
            x-on:keyup.escape="$refs.search.blur();" class="w-72 duration-300" autocomplete="off" />

          <div class="mt-2 flex justify-end">

            <button x-on:click="setCategory(null)" class="rounded bg-indigo-600 px-2 py-0.5 text-sm text-white">
              Eliminar filtros
            </button>

          </div>

        </div>

        <div class="h-[5.5rem] overflow-hidden overflow-y-auto pr-2">
          <ul class="flex flex-wrap text-sm">

            <template x-for="(item, key) in categories" :key="'category-' + key">

              <li x-on:click="setCategory(key)" class="mr-2 mt-1.5 cursor-pointer rounded-full px-2"
                :class="key == category_id ? 'text-white bg-cyan-500' : 'bg-slate-300'">
                <span x-text="item"></span>
              </li>

            </template>

          </ul>
        </div>
      </div>

      <div class="h-2 w-full shadow-md shadow-slate-300"></div>

      <div class="h-96 overflow-hidden overflow-y-auto pb-2 pl-2 pt-2">
        <ul
          class="grid grid-cols-2 gap-2 rounded pr-2 text-sm text-slate-800 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
          <template x-for="(item, index) in filteredItems" :key="'product-' + item.id">

            <li x-on:click="setItem(item)"
              class="relative overflow-hidden rounded-md border border-slate-300 font-medium">

              <div class="flex h-full flex-col px-1 py-2"
                :class="item.has_stock ? 'hover:bg-cyan-500 hover:text-white cursor-pointer ' : ''">

                <div class="mt-auto select-none text-xs">
                  <span x-text="item.reference" class="font-medium text-blue-600"></span>
                  <p x-text="item.name" class="leading-3"></p>
                </div>

              </div>

              <div x-show='!item.has_stock'
                class="absolute inset-0 flex items-center justify-center bg-red-500 bg-opacity-60">
                <span class="font-bold text-white">
                  Sin stock
                </span>
              </div>

            </li>

          </template>

          <template x-if="!filteredItems.length">
            <li class="col-span-full py-2 text-center text-base">
              No se encontraron productos
            </li>
          </template>

        </ul>
      </div>
    </div>

  </x-wireui.card>

</div>
