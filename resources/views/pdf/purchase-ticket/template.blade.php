<!DOCTYPE html>
<html lang="es">
<body>
    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td class="w-full text-center">
                <img class="h-28" src="{{ getUrlLogo() }}" >
            </td>
        </tr>

        <tr>
            <td class="text-center pt-8">
                Nit: {{ $company->nit }}
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Dirección: {{ $company->direction }}
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Celular: {{ $company->phone }}
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Email: {{ $company->email }}
            </td>
        </tr>
    </table>

    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td class="pt-5">
                Fecha:
            </td>
            <td class="font-bold pt-5">
                {{ $purchase->created_at->format('d-m-Y  h:i') }}
            </td>
        </tr>
        <tr>
            <td>
                N° Pago
            </td>
            <td class="font-bold ">
                {{ $purchase->id }}
            </td>
        </tr>
        <tr>
            <td>
                Proveedor
            </td>
            <td class="font-bold">
                {{ $purchase->provider->name }}
            </td>
        </tr>
    </table>

    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <thead>
            <tr>
                <th width="50%" class="pt-5">
                    Producto
                </th>
                <th width="15%" class="pt-5 text-center">
                    Cant
                </th>
                <th width="35%" class="pt-5 text-right">
                    Valor
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->details as $item)
                <tr>
                    <td class="text-left">
                        {{ $item->product->name }}
                    </td>
                    <td class="text-center">
                        @if ($item->product->units)
                            {{ $item->amount }}-{{ $item->units }}
                        @else
                            {{ $item->amount }}
                        @endif
                    </td>
                    <td class="text-right">
                        @formatToCop($item->total)
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td width="50%" class="pt-5 font-bold">
                Total
            </td>
        </tr>
        <tr>
            <td width="50%" class="">
                @formatToCop($purchase->total)
            </td>
        </tr>
    </table>
</body>
</html>
