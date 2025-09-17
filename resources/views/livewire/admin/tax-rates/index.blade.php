<div class="container">

  <x-commons.header>
    <x-wireui.button icon="user" @click="$wire.emitTo('admin.tax-rates.create', 'open-modal')" text="Crear Impuesto" />
  </x-commons.header>

  <x-commons.table-responsive>

    <x-slot:top title="Impuestos">
    </x-slot:top>

    <table class="table">
      <thead>
        <tr>
          <th left>
            Nombre
          </th>
          <th>
            Impuesto
          </th>
          <th>
            Pesos/Porcentaje
          </th>
          <th>
            Tarifa
          </th>
          <th>
            estado
          </th>
          <th>
            acciones
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse ($taxRates as $item)
          <tr wire:key="tax-rate-{{ $item->id }}">
            <td left>
              {{ $item->name }}
            </td>
            <td>
              {{ $item->tribute->name }}
            </td>
            <td>
              {{ $item->type }}
            </td>
            <td>
              {{ $item->symbolWithRate }}
            </td>
            <td>
              <x-commons.status :status="$item->status" />
            </td>
            <td actions>
              @if ($item->id !== 1 && $item->id !== 9)
                <x-buttons.edit wire:click="$emitTo('admin.tax-rates.edit', 'open-modal', {{ $item->id }})" />
              @endif
            </td>
          </tr>
        @empty
          <x-commons.table-empty />
        @endforelse
      <tbody>
    </table>

  </x-commons.table-responsive>

  <livewire:admin.tax-rates.create>

    <livewire:admin.tax-rates.edit>

</div>
