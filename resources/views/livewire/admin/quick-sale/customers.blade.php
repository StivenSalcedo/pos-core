<div>
  <x-wireui.modal wire:model.defer="openModal"
    max-width="xl">

    <div x-data="alpineCustomers()">

      <x-wireui.card title="Clientes">

        <x-commons.table-responsive class="overflow-hidden">

          <div class="mb-3 p-1">

            <x-commons.search placeholder="Buscar cliente"
              x-ref="search"
              x-model="search"
              x-on:focus="focus=true"
              x-on:keyup.escape="$refs.search.blur(); focus=false"
              x-on:keyup.down="nextItem()"
              x-on:keyup.up="previewItem();"
              x-on:keyup.enter="selectItem()"
              class="w-96 p-1 duration-300"
              autocomplete="off" />

          </div>

          <table class="table-sm border">
            <thead>
              <tr>
                <th left>
                  Identificacion
                </th>
                <th left>
                  Nombre
                </th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(item, index) in filteredItems">

                <tr class="font-medium"
                  @click="setItem(item)"
                  class="cursor-pointer"
                  :class="index === current ? 'bg-cyan-500 text-white' : ''">
                  <td left
                    width="150px">
                    <span x-text="item.no_identification"></span>
                  </td>
                  <td left>
                    <span x-text="item.names"></span>
                  </td>
                </tr>

              </template>
            <tbody>
          </table>

        </x-commons.table-responsive>

        <x-slot:footer>
          <div class="flex justify-end">

            <x-wireui.button x-on:click="show=false"
              text="Cerrar"
              secondary />

          </div>
        </x-slot:footer>

      </x-wireui.card>

    </div>

  </x-wireui.modal>
</div>
