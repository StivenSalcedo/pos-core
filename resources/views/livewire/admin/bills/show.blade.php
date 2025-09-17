<div class="text-gray-700 max-w-7xl mx-auto px-4 pb-14 pt-8">

  <div class="space-y-5">

    <x-wireui.card title="Información de la factura">
      <div class="grid grid-cols-3 gap-3">
        <x-wireui.input label="Fecha" value="{{ $bill->created_at->format('d-m-Y H:m:s') }}" readonly />
        <x-wireui.input label="N° de Factura" value="{{ $bill->number }}" readonly />
        <x-wireui.input label="Medio de pago" value="{{ $bill->paymentmethod->name }}" readonly />
      </div>
      <div class="grid grid-cols-3 gap-3 mt-6">
        <x-wireui.input label="Total" value="{{ formatToCop($bill->total) }}" readonly />
        <x-wireui.input label="Efectivo" value="{{ formatToCop($bill->cash) }}" readonly />
        <x-wireui.input label="Cambio" value="{{ formatToCop($bill->change) }}" readonly />
      </div>
      <x-slot:footer>
        <div class="flex justify-between">
          <div>
            @if ($bill->finance)
              <label class="font-semibold block text-sm">Estado financiación</label>
              <x-commons.status :status="$bill->finance->status" inactive="Pendiente" active="Pagada" />
            @endif
          </div>
          <div>
            @if ($bill->number)
              <x-wireui.button href="{{ route('admin.bills.download', $bill) }}" text="Descargar" />
            @endif
          </div>
        </div>
      </x-slot:footer>
    </x-wireui.card>

    <x-wireui.card title="Información del cliente">
      <div class="grid grid-cols-3 gap-3">
        <x-wireui.input label="N° Identificación" value="{{ $bill->customer->no_identification }}" readonly />
        <x-wireui.input label="Nombre" value="{{ $bill->customer->names }}" readonly />
        <x-wireui.input label="Teléfono" value="{{ $bill->customer->phone }}" readonly />
      </div>
    </x-wireui.card>

    <div class="bg-white border border-slate-200 shadow-sm scroll overflow-x-auto mt-5">
      <table class="w-full ">
        <thead class="bg-gray-100 font-semibold uppercase text-xs text-slate-500 font-inter border border-slate-200">
          <tr>
            <th class="py-3 px-4 text-left">
              Referencia
            </th>
            <th class="py-3 px-4 text-left">
              Nombre
            </th>
            <th class="py-3 px-4 text-center">
              Cantidad
            </th>
            <th class="py-3 px-4 text-center">
              Descuento
            </th>
            <th class="py-3 px-4 text-center">
              V. Unidad
            </th>
            <th class="py-3 px-4 text-center">
              Total
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($bill->details as  $item)
            <tr class="" wire:key="product-{{ $item['product_id'] }}">
              <td class="py-4 px-4 text-blue-500 font-semibold">
                {{ $item->product->reference }}
              </td>
              <td class="py-4 px-4 whitespace-nowrap text-slate-600">
                {{ $item->name }}
              </td>
              <td class="py-4 px-4  whitespace-nowrap text-slate-600 font-semibold text-center">
                {{ $item->amount }}
              </td>
              <td class="py-4 px-4  whitespace-nowrap text-slate-600 font-semibold text-center">
                @formatToCop($item->discount)
              </td>
              <td class="py-4 px-4  whitespace-nowrap text-slate-600 font-semibold text-center">
                @formatToCop($item->price)
              </td>
              <td class="py-4 px-4 whitespace-nowrap text-slate-600 font-semibold text-center">
                @formatToCop($item->total)
              </td>
            </tr>
          @empty
            <x-commons.table-empty />
          @endforelse
        <tbody>
      </table>
    </div>

    <x-wireui.card title="Observaciones">
      {{ $bill->observation }}
    </x-wireui.card>

    <div class="text-right mt-4 pr-4 space-y-1">
      <div class="font-bold text-xl">
        <span>VALOR BRUTO</span>
        <span class="w-28 inline-block text-left">@formatToCop($bill->subtotal)</span>
      </div>
      <div class="font-bold text-xl">
        <span>DESCUENTO</span>
        <span class="w-28 inline-block text-left">@formatToCop($bill->discount)</span>
      </div>
      @foreach ($bill->documentTaxes as $tax)
        <div class="font-bold text-xl">
          <span>{{ $tax->tribute_name }}</span>
          <span class="w-28 inline-block text-left">@formatToCop($tax->tax_amount)</span>
        </div>
      @endforeach
      <div class="font-bold text-xl">
        <span>TOTAL</span>
        <span class="w-28 inline-block text-left">@formatToCop($bill->total)</span>
      </div>
    </div>
  </div>
</div>
