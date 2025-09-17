<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%">

        <table width="100%">
            <tr>
                <td class="pt-10" width="80%">
                    <img style="max-height: 100; max-width: 200px" src="{{ getUrlLogo() }}">
                </td>
                <td class="pt-10">
                    <table>
                        <tr>
                            <td class="text-center">
                                {{ $output->date->format('d-m-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold">
                                N° de Recibo
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold text-red-600">
                                {{ $output->id }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%">
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

        <table width="100%">
            <tr>
                <td class="pt-8" style="width: 70%">
                    <span class="font-bold">Responsable</span>
                    {{ $output->user->name }}
                </td>
            </tr>
            <tr>
                <td class="" style="width: 70%" >
                    <span class="font-bold">Motivo: </span>
                     {{ $output->reason }}
                </td>
            </tr>
        </table>

        <h1 class="pt-5">Detalle</h1>

        <table width="100%">
            <tr>
                <td class="pt-3">
                    <span class="font-bold">VALOR</span>
                </td>
            </tr>
            <tr>
                <td>
                    @formatToCop($output->price)
                </td>
            </tr>
            <tr>
                <td class="pt-4">
                    <span class="font-bold">DESCRIPCIÓN</span>
                </td>
            </tr>
            <tr>
                <td>
                    {{ $output->description }}
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
