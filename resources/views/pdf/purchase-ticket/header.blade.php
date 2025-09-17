<table width="100%">
    <tr>
        <td style="width: 25%; text-align: left;" class="align-top">
            <img  style="width: 136px; height: auto;" src="{{ Storage::url('logos/logoc.png')}}" alt="logo">
        </td>
        <td style="width: 50%; padding: 0 1rem;" class="text-center vertical-align-top">

            <table>
                <tr>
                    <td class="text-center">
                        <h1 class="font-bold leading-none">FACTURA ELECTRONICA DE VENTA</h1>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <div class="text-sm leading-none">

                            NIT:{{ $company->nit }}
                            <br>
                            DIRECCION:{{ $company->direction }}

                            Telefono {{ $company->phone }} -

                            E-mail: {{ $company->email }}<br>

                        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 25%;">
            <table>
                <tr>
                    <td class="text-center text-red-500 font-bold text-sm">
                        {{ $numberingRange->prefix }} - {{ $number }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center pt-1">
                        <img style="width: 150px;" src="{{$imageQr}}">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
