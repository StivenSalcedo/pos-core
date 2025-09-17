<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%" class="text-slate-700" style="color: rgb(30, 41, 59)">

        @if (!$page)
            <table width="100%" style="color: rgb(30, 41, 59)">
                <tr>
                    <td class="pt-10" width="20%">
                        <img style="max-height: 100; max-width: 200px" src="{{ getUrlLogo() }}">
                    </td>

                    <td class="pt-10 text-center font-bold text-xl" width="60%">
                        REPORTE DE VENTAS DIARIAS
                    </td>

                    <td class="pt-10 text-center" width="20%">
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
                        <span class="font-bold">Empresa: </span>
                        {{ $company->name }}
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

            <table width="100%" style="color: rgb(30, 41, 59)">
                <tr>
                    <td class="pt-6">
                        {{ $page }} Reporte de día {{ $startDate }} hasta {{ $endDate }}
                    </td>
                </tr>
            </table>
        @endif

        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th align="center">
                                    Desde
                                </th>
                                <th align="center">
                                    Hasta
                                </th>
                                <th align="center">
                                    Subtotal
                                </th>
                                <th align="center">
                                    Descuento
                                </th>
                                <th align="center">
                                    IVA
                                </th>
                                <th align="center">
                                    INC
                                </th>
                                <th align="center">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailySales as $item)
                                <tr>
                                    <td align="center" style="min-height:20px; height:20px;">
                                        {{ $item['from'] }}
                                    </td>
                                    <td align="center" style="min-height:20px; height:20px;">
                                        {{ $item['to'] }}
                                    </td>
                                    <td align="right" style="min-height:20px; height:20px;">
                                        @formatToCop($item['subtotal_amount'])
                                    </td>
                                    <td align="right" style="min-height:20px; height:20px;">
                                        @formatToCop($item['discount_amount'])
                                    </td>
                                    <td align="right" style="min-height:20px; height:20px;">
                                        @formatToCop($item['iva_amount'])
                                    </td>
                                    <td align="right" style="min-height:20px; height:20px;">
                                        @formatToCop($item['inc_amount'])
                                    </td>
                                    <td align="right" style="min-height:20px; height:20px;">
                                        @formatToCop($item['total_amount'])
                                    </td>
                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
