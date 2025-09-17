<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%" >

        <table width="100%">
            <tr>
                <td class="pt-10" width="80%">
                    <img style="max-height: 100; max-width: 200px" src="{{ getUrlLogo() }}">
                </td>
                <td class="pt-10">
                    <table>
                        <tr>
                            <td class="text-center">
                                {{ $cashClosing->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold">
                                N° Cierre de caja
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold text-red-600">
                                {{ $cashClosing->id }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" class="mt-4">
            <tr>
                <td width="15%">
                    Responsable:
                </td>
                <td class="font-bold">
                    {{ $cashClosing->user->name }}
                </td>
            </tr>
        </table>

        <h1 class="font-bold mt-8">Detalle</h1>
        
        <hr>

        <table width="100%">
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Efectivo
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    @formatToCop($cashClosing->cash)
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Tarjeta crédito
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    @formatToCop($cashClosing->credit_card)
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Tarjeta débito
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    @formatToCop($cashClosing->debit_card)
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Transferencia
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    @formatToCop($cashClosing->transfer)
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Total ventas
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    @formatToCop($cashClosing->total_sales)
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Retiros de efectivo
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    @formatToCop($cashClosing->outputs)
                </td>
            </tr>
           
        </table>

        <hr class="mt-3">

        <table width="100%">
            <tr>
                <td class="pt-4 text-lg font-bold" style="width: 50%">
                    Dinero esperado en caja
                </td>
                <td class="pt-4 text-xl font-bold text-right" style="width: 50%">
                    @formatToCop($cashClosing->cash_register)
                </td>
            </tr>
            <tr>
                <td class="pt-4 text-lg font-bold" style="width: 50%">
                    Base inicial
                </td>
                <td class="pt-4 text-lg font-bold text-right" style="width: 50%">
                    @formatToCop($cashClosing->base)
                </td>
            </tr>
            <tr>
                <td class="pt-4 text-lg font-bold" style="width: 50%">
                    Dinero real en caja
                </td>
                <td class="pt-4 text-lg font-bold text-right" style="width: 50%">
                    @formatToCop($cashClosing->price)
                </td>
            </tr>
        </table>

        <hr class="mt-3">

        <table width="100%">
            <tr>
                <td class="pt-3">
                    <h1 class="font-bold">Observaciones</h1>
                    <p>{{ $cashClosing->observations }}</p>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
