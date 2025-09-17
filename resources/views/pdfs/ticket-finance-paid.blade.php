@push('html')
  <div x-data="alpineTicketFinancePaid()"
    class="print">
    <div x-show="show"
      class="absolute left-0 top-0 -z-40 w-full bg-white px-4 py-10 font-roboto !text-[12px]">

      <div class="flex justify-center">
        <img class="h-28"
          src="{{ getUrlLogo() }}">
      </div>

      {{-- Informacion de la empresa --}}
      <ul class="flex flex-col items-center leading-4">
        <li class="font-semibold">
          {{ session('config')->name }}
        </li>
        <li>
          NIT: {{ session('config')->nit }}
        </li>
        <li>
          Dirección: {{ session('config')->direction }}
        </li>
        <li>
          Celular: {{ session('config')->phone }}
        </li>
      </ul>

      <hr class="my-3 border border-slate-400">

      {{-- Informacion de la factura --}}
      <div class="overflow-hidden">
        <p class="text-right font-bold">Referencia de pago: <span x-text="payment.number"></span></p>
        <ul class="whitespace-nowrap leading-4">
          <li>
            <span class="inline-block w-14">Fecha</span>
            :
            <span class="font-medium"
              x-text="payment.format_created_at"></span>
          </li>
          <li>
            <span class="inline-block w-14">C.C / NIT</span>
            :
            <span class="font-medium"
              x-text="customer.identification"></span>
          </li>
          <li>
            <span class="inline-block w-14">Cliente</span>
            :
            <span class="font-medium"
              x-text="customer.names"></span>
          </li>
        </ul>
      </div>

      <hr class="my-3 border border-slate-400">

      {{-- Productos --}}
      <table class="w-full leading-3">
        <thead>
          <tr>
            <th width="70%"
              class="text-left font-medium">
              Producto o servicio
            </th>
            <th width="10%">
              Cant
            </th>
            <th width="20%"
              class="text-right font-medium">
              Valor
            </th>
          </tr>
        </thead>
        <tbody>
          <template x-for="item in products">
            <tr>
              <td class="text-left">
                <span x-text="strLimit(item.name, 60)"></span>
              </td>
              <td class="text-center">
                <span x-text="item.amount"></span>
              </td>
              <td class="text-right">
                <span x-text="formatToCop(item.total)"></span>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <h1 class="my-5 border-b-2 border-dotted border-slate-400"></h1>

      {{-- Totales --}}
      <ul class="leading-4">
        <li class="flex justify-end">
          <span>Total a pagar:</span>
          <span class="inline-block w-24 text-right font-medium"
            x-text="formatToCop(payment.total)"></span>
        </li>
      </ul>

      <hr class="my-3 border border-slate-400">

      {{-- Medios de pago --}}
      <ul class="leading-4">
        <li class="flex justify-end">
          <span>Forma de pago</span>
          <span class="inline-block w-24 text-right font-medium" x-text="payment.payment_method"></span>
        </li>
      </ul>

      {{-- footer --}}
      <div class="mt-8 text-xs">
        <p class="text-center leading-4">Elaborador por: HALLTEC</p>
        <p class="text-center leading-4">www.halltec.co NIT: 900825759-7</p>
      </div>

    </div>
  </div>
@endpush
