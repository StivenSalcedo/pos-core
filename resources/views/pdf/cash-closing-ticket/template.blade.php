<!DOCTYPE html>
<html lang="es">

<body>
    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td class="w-full text-center">
                <img class="h-28" src="{{ getUrlLogo() }}" >
            </td>
        </tr>
    </table>

    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td class="pt-2">
                Fecha:
            </td>
            <td class="pt-2">
                {{ $cashClosing->created_at->format('d-m-Y  h:i') }}
            </td>
        </tr>
        <tr>
            <td class="pt-2">
                N° cierre de caja:
            </td>
            <td class="pt-2">
                {{ $cashClosing->id }}
            </td>
        </tr>
        <tr>
            <td class="pt-2">
                Responsable:
            </td>
            <td class="pt-2">
                {{ $cashClosing->user->name }}
            </td>
        </tr>
    </table>

    <hr class="mt-3">


    <table width="100%" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Efectivo
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->cash)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Tarjeta crédito
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->credit_card)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Tarjeta débito
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->debit_card)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Transferencia
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->transfer)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Total ventas
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->total_sales)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Egresos
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->outputs)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Propinas
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->tip)
            </td>
        </tr>


    </table>

    <hr class="mt-3">

    <table width="100%" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Base inicial
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->base)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 60%">
                Dinero esperado en caja
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->cash_register)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Dinero real en caja
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @formatToCop($cashClosing->price)
            </td>
        </tr>
        <tr>
            <td class="pt-4 font-bold" style="width: 50%">
                Total cierre
            </td>
            <td class="pt-4 font-bold text-right" style="width: 50%">
                @if ($cashClosing->price - $cashClosing->cash_register >= 0)
                    <p class="text-green-600">
                        @formatToCop($cashClosing->price - $cashClosing->cash_register)
                    </p>
                @else
                    <p class="text-red-600">
                        @formatToCop($cashClosing->price - $cashClosing->cash_register)
                    </p>

                @endif
            </td>
        </tr>

    </table>

    <hr class="mt-3">

    <div class="mt-3" style="font-size: 0.810rem; line-height: 1rem;">
        <h1 class="font-bold">Observaciones</h1>
        <p>{{ $cashClosing->observations }}</p>
    </div>
</body>

</html>
