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
                {{ $output->date->format('d-m-Y') }}
            </td>
        </tr>
        <tr>
            <td>
                N° Pago
            </td>
            <td class="font-bold ">
                {{ $output->id }}
            </td>
        </tr>
        <tr>
            <td>
                Responsable
            </td>
            <td class="font-bold">
                {{ $output->user->name }}
            </td>
        </tr>
        <tr>
            <td>
                Motivo
            </td>
            <td class="font-bold">
                {{ $output->reason }}
            </td>
        </tr>
    </table>

    <table class="w-full" style="font-size: 0.810rem; line-height: 1rem;">
        <tr>
            <td width="50%" class="pt-5 font-bold">
                Descripción
            </td>
        </tr>
        <tr>
            <td width="50%" class="">
                {{ $output->description }}
            </td>
        </tr>
        <tr>
            <td width="50%" class="font-bold pt-3">
                Valor
            </td>
        </tr>
        <tr>
            <td width="50%" class="">
                @formatToCop($output->price)
            </td>
        </tr>
    </table>
</body>
</html>
