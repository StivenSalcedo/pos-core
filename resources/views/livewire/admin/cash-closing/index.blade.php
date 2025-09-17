<div class="container">

  <x-commons.header>

    <x-wireui.range-date wire:model="filterDate"
      :options="[
          0 => 'Todos',
          1 => 'Hoy',
          2 => 'Esta semana',
          3 => 'Ultimos 7 días',
          4 => 'La semana pasada',
          5 => 'Hace 15 días',
          6 => 'Este mes',
          7 => 'El mes padado',
          8 => 'Rango de fechas',
      ]" />

    <x-wireui.button icon="excel"
      success
      wire:click='export'
      text="Exportar a excel" />

    <x-wireui.button icon="payment"
      x-on:click="$dispatch('open-modal')"
      text="Cerrar caja" />

  </x-commons.header>

  <x-commons.table-responsive>

    <x-slot:top
      title="Cierres de caja">

      <x-commons.tag tooltip="Total propinas"
        label="Propinas"
        value="{{ formatToCop($totales->tips) }}" />

      <x-commons.tag tooltip="Total de egresos"
        label="Egresos"
        value="{{ formatToCop($totales->outputs) }}" />

      <x-commons.tag tooltip="Total de ventas"
        label="Ventas"
        value="{{ formatToCop($totales->total_sales) }}" />

    </x-slot:top>

    <x-slot:header>

      <x-wireui.native-select wire:model="terminal_id"
        label="Terminales"
        :optionKeyValue="true"
        placeholder="Seleccionar"
        :options="$arrayTerminals"
        class="w-full" />

      <x-wireui.native-select wire:model="user_id"
        label="Usuarios"
        :optionKeyValue="true"
        placeholder="Selecionar"
        :options="$users"
        class="w-full" />

      @if ($filterDate == 8)
        <x-wireui.input label="Desde"
          wire:model="startDate"
          type="date"
          onkeydown="return false" />

        <x-wireui.input label="Hasta"
          wire:model="endDate"
          type="date"
          onkeydown="return false" />
      @endif
    </x-slot:header>

    <table class="table">
      <thead>
        <tr>
          <th left>
            Fecha
          </th>
          <th left>
            Terminal
          </th>
          <th left>
            Responsable
          </th>
          <th left>
            Ventas
          </th>
          <th left>
            Base
          </th>
          <th left>
            Efectivo de caja
          </th>
          <th left>
            Dinero real
          </th>
          <th>
            Total cierre
          </th>
          <th>
            Propinas
          </th>
          <th>
            Acciones
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse ($closings as $item)
          <tr wire:key="cash-closing-{{ $item->id }}">
            <td left>
              {{ $item->created_at->format('d/m/Y - g:i A') }}
            </td>
            <td left>
              {{ $item->terminal->name }}
            </td>
            <td left>
              {{ $item->user->name }}
            </td>
            <td left>
              @formatToCop($item->total_sales)
            </td>
            <td left>
              @formatToCop($item->base)
            </td>
            <td left>
              @formatToCop($item->cash_register)
            </td>
            <td left>
              @formatToCop($item->price)
            </td>
            @if ($item->price - $item->cash_register >= 0)
              <td class="text-green-600"
                left>
                @formatToCop($item->price - $item->cash_register)
              </td>
            @else
              <td class="text-red-600"
                left>
                @formatToCop($item->price - $item->cash_register)
              </td>
            @endif

            <td left>
              @formatToCop($item->tip)
            </td>
            <td actions>
              <x-buttons.icon icon="pdf"
                class="text-red-600"
                :href="route('admin.cash-closing.pdf', $item->id)"
                target="_blank"
                title="Descargar PDF"
                text="Cerrar caja" />
              <x-buttons.show wire:click="$emitTo('admin.cash-closing.show', 'openShow', {{ $item->id }})"
                text="Cerrar caja" title="Visualizar" />
            </td>
          </tr>
        @empty
          <x-commons.table-empty />
        @endforelse
      <tbody>
    </table>
  </x-commons.table-responsive>

  @if ($closings->hasPages())
    <div class="p-3">
      {{ $closings->links() }}
    </div>
  @endif

  <livewire:admin.cash-closing.create>
    <livewire:admin.cash-closing.show>

      <x-wireui.modal wire:model.defer="openModal"
        max-width="lg">

        <x-wireui.card title="Terminales">
          <div x-on:open-modal.window="show=true">
            <p class="font-semibold">Selecciona la terminal</p>
            <ul class="mt-2 divide-y-2 rounded border shadow">
              @foreach ($terminals as $item)
                <li wire:key='terminal-{{ $item }}'
                  x-on:click="show=false; $wire.emitTo('admin.cash-closing.create', 'openCreate', {{ $item->id }})"
                  class="cursor-pointer py-2 text-center hover:bg-slate-200 hover:font-bold hover:text-blue-600">
                  {{ $item->name }}
                </li>
              @endforeach
            </ul>
          </div>
          <x-slot:footer>
            <div class="text-right">
              <x-wireui.button secondary
                x-on:click="show=false"
                text="Cerrar" />
            </div>
          </x-slot:footer>
        </x-wireui.card>

      </x-wireui.modal>

</div>
