<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%" class="text-slate-700" style="color: rgb(30, 41, 59)">

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-10" width="80%">
                    <img style="max-height: 100; max-width: 200px" src="{{ getUrlLogo() }}">
                </td>
                <td class="pt-10">
                    <table>
                        <tr>
                            <td class="text-center">
                                {{ $purchase->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold">
                                N° de Pago
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold text-red-600">
                                {{ $purchase->id }}
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
                <td class="" style="width: 70%" >
                    <span class="font-bold">NIT: </span>
                    {{ $company->nit }}
                </td>
            </tr>
            <tr>
                <td class="" style="width: 70%" >
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

        <h1 class="pt-5 text-sm font-bold">INFORMACIÓN DEL PROVEEDOR</h1>

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <span class="font-bold">N° de Identificación:</span>
                    {{ $purchase->provider->no_identification }}
                </td>
            </tr>
            <tr>
                <td class="" style="width: 70%" >
                    <span class="font-bold">Nombre: </span>
                     {{ $purchase->provider->name }}
                </td>
            </tr>
            <tr>
                <td style="width: 70%">
                    <span class="font-bold">Celular: </span>
                    {{ $purchase->provider->phone }}
                </td>
            </tr>
        </table>

        <h1 class="pt-5 text-sm font-bold">PRODUCTOS COMPRADOS</h1>

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%" >
                    <table class="table" width="100%" >
                        <thead>
                            <tr>
                                <th align="center">
                                    Código
                                </th>
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
                                    Costo
                                </th>
                                <th align="center">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->details as $item)
                                <tr>
                                    <td align="left">
                                        {{ $item->product->barcode }}
                                    </td>
                                    <td align="left">
                                        {{ $item->product->reference }}
                                    </td>
                                    <td align="left">
                                        {{ $item->product->name }}
                                    </td>
                                    <td align="center">
                                        @if ($item->product->units)
                                            {{ $item->amount }}-{{ $item->units }}
                                        @else
                                            {{ $item->amount }}
                                        @endif
                                    </td>
                                    <td align="center">
                                        @formatToCop($item->cost)
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
                <td class="text-right pt-5 text-lg font-bold">
                    TOTAL @formatToCop($purchase->total)
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
