<div>

  <div class="flex justify-between">
    <h1 class="text-sm">Registros de apertura de caja</h1>
    <x-wireui.button wire:click="openModal" text="Ver" load icon="eye" />
  </div>

  <x-wireui.modal wire:model.defer="show" max-width="xl">
    <x-wireui.card title="Registros de apertura de caja">
      <x-commons.table-responsive class="max-h-96 overflow-y-auto">

        <table class="table-sm">
          <thead>
            <tr>
              <th left>
                ID
              </th>
              <th left>
                Fecha
              </th>
              <th left>
                Usuario
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse ($data as $key => $item)
              <tr wire:key="data-{{ $key }}">
                <td left>
                  {{ $item['user_id'] }}
                </td>
                <td left>
                  {{ $item['datetime'] }}
                </td>
                <td left>
                  {{ $item['user_name'] }}
                </td>
              </tr>
            @empty
              <x-commons.table-empty />
            @endforelse
          <tbody>
        </table>
      </x-commons.table-responsive>
      <x-slot:footer>
        <div class="flex justify-end">
          <x-wireui.button @click="show=false" text="Cerrar" />
        </div>
      </x-slot:footer>
    </x-wireui.card>
  </x-wireui.modal>

</div>
