<!DOCTYPE html>
<html lang="es">

<body>
  <table class="w-full" style="font-size: 0.810rem">
    <tr>
      <td class="w-full text-center">
        <img class="h-28" src="{{ getUrlLogo() }}">
      </td>
    </tr>

    <tr>
      <td class="text-center pt-4 font-bold text-xl">
        {{ $company->name }}
      </td>
    </tr>

    <tr>
      <td class="text-center pt-2 leading-5">
        NIT: {{ $company->nit }} <br>
        Dirección: {{ $company->direction }} <br>
        Celular: {{ $company->phone }}
      </td>
    </tr>

    @if ($range->resolution_number)
      <tr>
        <td class="text-center leading-5">
          Resolución DIAN <span class="font-bold"> {{ $range->resolution_number }} </span> <br>
          Autorizada el <span class="font-bold"> {{ $range->date_authorization->format('d-m-Y') }} </span> <br>
          Prefijo <span class="font-bold"> {{ $range->prefix }} </span> del <span class="font-bold"> {{ $range->from }}
          </span> al <span class="font-bold"> {{ $range->to }} </span> <br>
          @if (false)
            Responsables de IVA
          @endif
        </td>
      </tr>
    @endif

  </table>

  <hr class="my-3">

  <h1 class="text-right font-bold text-sm">Factura de venta : {{ $bill->number }}</h1>

  <table class="w-full mt-1" style="font-size: 0.810rem; line-height: 1rem;">
    <tr>
      <td>
        Fecha
      </td>
      <td class="font-bold">
        : {{ $bill->created_at->format('d-m-Y  h:i') }}
      </td>
    <tr>
      <td>
        Cajero
      </td>
      <td class="font-bold">
        : {{ $bill->user->name }}
      </td>
    </tr>
    </tr>
    <tr>
      <td>
        C.C / NIT
      </td>
      <td class="font-bold ">
        : {{ $bill->customer->no_identification }}
      </td>
    </tr>
    <tr>
      <td>
        Cliente
      </td>
      <td class="font-bold">
        : {{ $bill->customer->names }}
      </td>
    </tr>
  </table>

  <hr class="my-3">

  <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
    <thead>
      <tr>
        <th width="60%">
          Producto
        </th>
        <th width="15%" class="text-center">
          Cant
        </th>
        <th width="25%" class="text-right">
          Valor
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($bill->details as $item)
        <tr>
          <td class="text-left">
            {{ Str::limit($item->name, '60') }}
          </td>
          <td class="text-center">
            {{ $item->amount }}
          </td>
          <td class="text-right">
            @formatToCop($item->price)
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <h1 class="border-b-2 border-dotted mt-3"></h1>

  <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
    <tr>
      <td width="70%" class="pt-5 text-right">
        Subtotal :
      </td>
      <td class="pt-5 font-bold text-right">
        @formatToCop($bill->subtotal)
      </td>
    </tr>
    <tr>
      <td class="text-right">
        Servicio voluntario :
      </td>
      <td class="font-bold text-right">
        @formatToCop($bill->tip)
      </td>
    </tr>
    <tr>
      <td class="text-right">
        Descuento :
      </td>
      <td class="font-bold text-right">
        @formatToCop($bill->discount)
      </td>
    </tr>
    @foreach ($bill->documentTaxes as $tax)
      <tr>
        <td class="text-right">
         {{ $tax->tribute_name}} :
        </td>
        <td class="font-bold text-right">
          @formatToCop($tax->tax_amount)
        </td>
      </tr>
    @endforeach
    <tr>
      <td class="text-right">
        Total a pagar :
      </td>
      <td class="font-bold text-right">
        @formatToCop($bill->final_total)
      </td>
    </tr>
  </table>

  <hr class="my-3">

  <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
    <tr>
      <td width="70%" class="pt-5 font-bold text-right">
        Forma de pago
      </td>
      <td class="pt-5 font-bold text-right">

      </td>
    </tr>
    <tr>
      <td class="text-right">
        {{ $bill->paymentMethod->name }} :
      </td>
      <td class="font-bold text-right">
        @formatToCop($bill->cash)
      </td>
    </tr>
    <tr>
      <td class="text-right">
        Cambio :
      </td>
      <td class="font-bold text-right">
        @formatToCop($bill->change)
      </td>
    </tr>
  </table>
</body>

</html>
