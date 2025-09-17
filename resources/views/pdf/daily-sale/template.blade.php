<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%" class="text-slate-700" style="color: rgb(30, 41, 59)">

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
                    {{ $dailySale->name }}
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
                    Reporte de día {{ $dailySale->format_creation_date }}
                </td>
            </tr>
        </table>

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
                            <tr>
                                <td align="center">
                                    {{ $dailySale->from }}
                                </td>
                                <td align="center">
                                    {{ $dailySale->to }}
                                </td>
                                <td align="right">
                                    @formatToCop($dailySale->subtotal_amount)
                                </td>
                                <td align="right">
                                    @formatToCop($dailySale->discount_amount)
                                </td>
                                <td align="right">
                                    @formatToCop($dailySale->iva_amount)
                                </td>
                                <td align="right">
                                    @formatToCop($dailySale->inc_amount)
                                </td>
                                <td align="right">
                                    @formatToCop($dailySale->total_amount)
                                </td>
                            </tr>
                        <tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
