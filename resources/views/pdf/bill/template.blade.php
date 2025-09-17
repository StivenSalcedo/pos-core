<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%" class="text-slate-700" style="color: rgb(30, 41, 59)">

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-10" width="30%">
                    <img style="max-height: 100; max-width: 200px" src="{{ getUrlLogo() }}">
                </td>

                @if ($range->resolution_number)
                    <td class="text-center leading-5" width="40%">

                        Resolución DIAN <span class="font-bold"> {{ $range->resolution_number }} </span> <br>
                        Autorizada el <span class="font-bold"> {{ $range->expire->format('d-m-Y') }} </span> <br>
                        Prefijo <span class="font-bold"> {{ $range->prefix }} </span> del <span class="font-bold"> {{
                            $range->from }} </span> al <span class="font-bold"> {{ $range->to }} </span> <br>
                        @if (false)
                        Responsables de IVA
                        @endif

                    </td>
                @endif

                <td class="pt-10 text-center" width="30%">
                    <table>
                        <tr>
                            <td class="text-center">
                                {{ $bill->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold">
                                N° de Factura
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold text-red-600">
                                {{ $bill->number }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-8 text-2xl font-bold" style="width: 70%">
                    {{ $company->name }}
                </td>
            </tr>
            <tr>
                <td class="" style="width: 70%">
                    <span class="font-bold">NIT: </span>
                    {{ $company->nit }}
                </td>
            </tr>
            <tr>
                <td class="" style="width: 70%">
                    <span class="font-bold">Dirección: </span>
                    {{ $company->direction }}
                </td>
            </tr>
            <tr>
                <td style="width: 70%">
                    <span class="font-bold">Celular: </span>
                    {{ $company->phone }}
                </td>
            </tr>
            <tr>
                <td style="width: 70%">
                    <span class="font-bold">Email: </span>
                    {{ $company->email }}
                </td>
            </tr>

        </table>

        <h1 class="pt-5 text-sm font-bold">INFORMACIÓN DEL CLIENTE</h1>

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <span class="font-bold">N° de Identificación:</span>
                    {{ $bill->customer->no_identification }}
                </td>
            </tr>
            <tr>
                <td class="" style="width: 70%">
                    <span class="font-bold">Nombre: </span>
                    {{ $bill->customer->names }}
                </td>
            </tr>
            <tr>
                <td style="width: 70%">
                    <span class="font-bold">Celular: </span>
                    {{ $bill->customer->phone }}
                </td>
            </tr>
        </table>

        <h1 class="pt-5 text-sm font-bold">PRODUCTOS</h1>

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th align="center">
                                    Referencia
                                </th>
                                <th align="center">
                                    Producto
                                </th>
                                <th align="center">
                                    Cantidad
                                </th>
                                <th align="center">
                                    Descuento
                                </th>
                                <th align="center">
                                    V. Unidad
                                </th>
                                <th align="center">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bill->details as $item)
                            <tr>
                                <td align="center">
                                    {{ $item->product->reference }}
                                </td>
                                <td align="center">
                                    {{ $item->name }}
                                </td>
                                <td align="center">
                                    {{ $item->amount }}
                                </td>
                                <td align="center">
                                    @formatToCop($item->discount)
                                </td>
                                <td align="center">
                                    @formatToCop($item->price)
                                </td>
                                <td align="center">
                                    @formatToCop($item->total)
                                </td>
                            </tr>
                            @endforeach
                        <tbody>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="text-right">
                    <table>
                        <tr>
                            <td width="45%" class="text-right pt-5 text-base font-bold">
                                SUBTOTAL
                            </td>
                            <td width="45%" class="pt-5 text-base font-bold">
                                @formatToCop($bill->subtotal)
                            </td>
                        </tr>
                        <tr>
                            <td width="45%" class="text-right pt-5 text-base font-bold">
                                DESCUENTO
                            </td>
                            <td width="45%" class=" pt-5 text-base font-bold">
                                @formatToCop($bill->duscount)
                            </td>
                        </tr>
                        <tr>
                            <td width="45%" class="text-right pt-5 text-base font-bold">
                                IMPUESTO(IVA)
                            </td>
                            <td width="45%" class=" pt-5 text-base font-bold">
                                @formatToCop($bill->tax)
                            </td>
                        </tr>
                        <tr>
                            <td width="45%" class="text-right pt-5 text-base font-bold">
                                TOTAL
                            </td>
                            <td width="45%" class="pt-5 text-base font-bold">
                                @formatToCop($bill->total)
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
