<!DOCTYPE html>
<html lang="es">

<body>
    <div width="100%">

        <table width="100%">
            <tr>
                <td class="pt-10" width="80%">
                    <img class="h-28" src="{{ getUrlLogo() }}">
                </td>
                <td class="pt-10">
                    <table>
                        <tr>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($service->date_entry)->format('d/m/Y h:i:s a') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold">
                                N° Servicio
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-bold text-red-600">
                                {{ $service->id }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" class="mt-4">
            <tr>
                <td width="15%">
                    Cliente:
                </td>
                <td class="font-bold">
                    {{ $service->customer->names }}
                </td>
                <td width="15%">
                    Fecha Vencimiento:
                </td>
                <td class="font-bold">
                    {{ \Carbon\Carbon::parse($service->date_due)->format('d/m/Y') }}
                </td>
            </tr>
        </table>

        <h1 class="font-bold mt-8">Datos Iniciales</h1>
        <hr>
        <table width="100%">
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Tecnico
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->techassigned->name }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Responsable
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->responsible->name }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Tipo de equipo
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->equipmentType->name }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Marca
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->brand->name }}
                </td>
            </tr>


        </table>
        <h1 class="font-bold mt-8">Detalles tecnicos</h1>
        <hr>
        <table width="100%">
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Modelo
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->model ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Usuario
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->user ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Accesorios
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->accesories ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    problema
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->problem_description ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="pt-4 font-bold text-right" style="width: 80%">
                    Diagnóstico
                </td>
                <td class="pt-4 font-bold text-right" style="width: 20%">
                    {{ $service->diagnosis ?? '' }}
                </td>
            </tr>


        </table>
        <h1 class="font-bold mt-8">Componentes</h1>
        <hr class="mt-3">
        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th align="center">
                                    Componente
                                </th>
                                <th align="center">
                                    Marca
                                </th>
                                <th align="center">
                                    Referencia
                                </th>
                                <th align="center">
                                    Capacidad
                                </th>
                                <th align="center">
                                    Unidades
                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($service->details as $item)
                                <tr>
                                    <td align="center">
                                        {{ $item->component->name ?? 'N/A' }}
                                    </td>
                                    <td align="center">
                                        {{ $item->brand->name ?? 'Sin marca' }}
                                    </td>
                                    <td align="center">
                                        {{ $item->reference ?? 'SIN REFERENCIA' }}
                                    </td>
                                    <td align="center">
                                        {{ $item->capacity ?? 'N/A' }}
                                    </td>
                                    <td align="center">
                                        {{ $item->quantity ?? 1 }}
                                    </td>

                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                </td>
            </tr>
        </table>

        <h1 class="font-bold mt-8">Productos</h1>
        <hr class="mt-3">
        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th align="center">
                                    Producto
                                </th>
                                <th align="center">
                                    Cantidad
                                </th>
                                <th align="center">
                                    Valor unidad
                                </th>
                                <th align="center">
                                    Descuento
                                </th>
                                <th align="center">
                                    Total
                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($service->products as $item)
                                <tr>
                                    <td align="center">
                                        {{ $item->product->name ?? 'N/A' }}
                                    </td>
                                    <td align="center">
                                        {{ $item->quantity }}
                                    </td>
                                    <td align="center">
                                        {{ number_format($item['unit_price'], 0, ',', '.') }}
                                    </td>
                                    <td align="center">
                                        {{ number_format($item['discount'], 0, ',', '.') }}
                                    </td>
                                    <td align="center">
                                        {{ number_format($item['total'], 0, ',', '.') }}
                                    </td>

                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                </td>
            </tr>
        </table>

        <h1 class="font-bold mt-8">Pagos</h1>
        <hr class="mt-3">
        <table width="100%" style="color: rgb(30, 41, 59)">
            <tr>
                <td class="pt-4" style="width: 70%">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left">Fecha</th>
                                <th class="px-3 py-2 text-left">Valor</th>
                                <th class="px-3 py-2 text-left">Método</th>
                                <th class="px-3 py-2 text-left">Usuario</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($service->payments as $payment)
                                <tr>
                                    <td class="px-3 py-2">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-2 text-left">
                                        {{ number_format($payment['amount'], 0, ',', '.') }}</td>
                                    <td class="px-3 py-2">{{ $payment->payment->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2">{{ $payment['user']['name'] ?? 'N/A' }}</td>

                                </tr>
                            @endforeach
                        <tbody>
                    </table>
                </td>
            </tr>
        </table>




</body>

</html>
