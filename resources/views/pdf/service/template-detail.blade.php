<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%">

        <table width="100%">
            <tr>
                <td width="50%">
                    <img class="h-28" src="{{ getUrlLogo() }}">
                </td>
                <td class="text-right" width="50%">
                    <table class="">
                        <tr>
                            <td class="text-right font-bold">
                                {{$company->name}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                N° Nit - {{$company->nit}} - No responsable de IVA
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                {{$company->nit}} | {{$company->phone}}
                            </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
        </table>

        <div class="border-solid border-2 border-sky-500 mt-6">
            <table width="100%">
                <tr>
                    <td width="50%">
                        <table>
                            <tr>
                                <td class="font-bold">
                                    {{ $service->customer->names }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    N° Cedula:{{ $service->customer->no_identification }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dirección:{{ $service->customer->direction }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Teléfono:{{ $service->customer->phone }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-right" width="50%">
                        <table>
                            <tr>
                                <td class="font-bold text-right">
                                    Orden de servicio {{ $service->id }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <small>Fecha expedición:
                                        {{ \Carbon\Carbon::parse($service->date_entry)->format('d/m/Y h:i:s a') }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <small>Fecha vencimiento:
                                        {{ \Carbon\Carbon::parse($service->date_due)->format('d/m/Y') }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    Lista precio
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <table class="mt-6" width="100%">
            <tr>
                <td class="font-bold" width="50%">
                    DETALLE DEL EQUIPO
                </td>
                <td class="font-bold" width="50%" align="right">
                    {{ $service->equipmentType->name }} - {{ $service->brand->name }} - {{ $service->model ?? 'N/A' }}
                </td>
            </tr>
        </table>


        <table border="2" cellpadding="10" width="100%" style="border:1px solid black;border-collapse:collapse;">
            <tr>
                <td style="border:1px solid black;" width="50%">
                    Estado de servicio:{{ $service->state->name }}
                </td>
                <td style="border:1px solid black;" width="50%">
                    Fecha estimada de entrega:  {{ \Carbon\Carbon::parse($service->date_due)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border:1px solid black;" width="50%">
                    Problema reportado: {{ $service->problem_description ?? '' }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border:1px solid black;" width="50%">
                    Diagnostico: {{ $service->diagnosis ?? '' }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border:1px solid black;" width="50%">
                    Accesorios: {{ $service->accessories ?? '' }}
                </td>
            </tr>
        </table>

        <table class="mt-6" width="100%">
            <tr>
                <td class="font-bold" width="50%">
                    DETALLE TÉCNICO DEL EQUIPO
                </td>
            </tr>
        </table>


        <table border="2" width="100%" style="border:1px solid black;border-collapse:collapse;">
            <thead>
                <tr>
                    <th align="center" width="25%" style="border:1px solid black;">Componente</th>
                    <th align="center" width="25%" style="border:1px solid black;">Marca</th>
                    <th align="center" width="25%" style="border:1px solid black;">Referencia</th>
                    <th align="center" width="25%" style="border:1px solid black;">Capacidad</th>
                    <th align="center" width="25%" style="border:1px solid black;">Unidades</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($service->details as $item)
                    <tr>
                        <td align="center" style="border:1px solid black;">
                            {{ $item->component->name ?? 'N/A' }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                            {{ $item->brand->name ?? 'Sin marca' }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                            {{ $item->reference ?? 'SIN REFERENCIA' }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                            {{ $item->capacity ?? 'N/A' }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                            {{ $item->quantity ?? 1 }}
                        </td>
                    </tr>
                @endforeach
            <tbody>
        </table>

        <table class="mt-6" width="100%">
            <tr>
                <td class="font-bold" width="50%">
                    PRODUCTOS/SERVICIOS
                </td>
            </tr>
        </table>


        <table border="2" width="100%" style="border:1px solid black;border-collapse:collapse;">
            <thead>
                <tr>
                    <th align="center" width="10%" style="border:1px solid black;">Código</th>
                    <th align="center" width="30%" style="border:1px solid black;">Producto/servicio</th>
                    <th align="center" width="15%" style="border:1px solid black;">Cantidad</th>
                    <th align="center" width="15%" style="border:1px solid black;">Valor unidad</th>
                    <th align="center" width="15%" style="border:1px solid black;">Descuento</th>
                    <th align="center" width="15%" style="border:1px solid black;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($service->products as $item)
                    <tr>
                        <td align="center" style="border:1px solid black;">
                            <small>{{ $item->product->barcode ?? 'N/A' }}</small>
                        </td>
                        <td align="center" style="border:1px solid black;">
                            <small>{{ $item->product->name ?? 'N/A' }}</small>
                        </td>
                        <td align="center" style="border:1px solid black;">
                            {{ $item->quantity }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                           $ {{ number_format($item['unit_price'], 0, ',', '.') }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                           $ {{ number_format($item['discount'], 0, ',', '.') }}
                        </td>
                        <td align="center" style="border:1px solid black;">
                           $ {{ number_format($item['total'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            <tbody>
        </table>

        <table class="mt-6" width="100%">
            <tr>
                <td class="font-bold" width="50%">
                    PAGOS
                </td>
            </tr>
        </table>


        <table border="2" width="100%" style="border:1px solid black;border-collapse:collapse;">
            <thead>
                <tr>
                    <th align="center" width="25%" style="border:1px solid black;">Fecha</th>
                    <th align="center" width="25%" style="border:1px solid black;">Valor</th>
                    <th align="center" width="25%" style="border:1px solid black;">Método</th>
                    <th align="center" width="25%" style="border:1px solid black;">Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($service->payments as $payment)
                    <tr>
                        <td align="center" style="border:1px solid black;">
                            {{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td align="center" style="border:1px solid black;">
                           $ {{ number_format($payment['amount'], 0, ',', '.') }}</td>
                        <td align="center" style="border:1px solid black;">{{ $payment->payment->name ?? 'N/A' }}
                        </td>
                        <td align="center" style="border:1px solid black;">{{ $payment['user']['name'] ?? 'N/A' }}
                        </td>
                    </tr>
                @endforeach
            <tbody>
        </table>

        <hr class="my-6">
        <table width="100%">
            <tr>
                <td class="text-right">
                    <table>
                        <tr>
                            <td class="text-right">
                                Subtotal: {{ number_format($subtotal, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                Descuento: {{ number_format($discount, 2) }}
                            </td>
                        </tr>
                         @if ($iva>0)
                        <tr>
                            <td class="text-right">
                                IVA: {{ number_format($iva, 2) }}
                            </td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td class="font-bold text-right">
                                TOTAL A PAGAR: {{ number_format($total, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                Pagado: {{ number_format($pagado, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold text-right">
                                Saldo: {{ number_format($saldo, 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>




</body>

</html>
