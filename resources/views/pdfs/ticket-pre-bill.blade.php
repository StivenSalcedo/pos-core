@push('html')
<div x-data="alpineTicketPreBill()" class="print">
    <div x-show="show" class="absolute -z-40 font-roboto top-0 left-0 bg-white py-10 !text-[12px] px-4 w-full">

        <div class="flex justify-center">
            <img class="h-28" src="{{ getUrlLogo() }}">
        </div>

        {{-- Informacion de la empresa --}}
        <ul class="flex flex-col items-center leading-4">
            <li class="font-semibold">
                {{ session('config')->name }}
            </li>
            <li>
                NIT: {{ session('config')->nit }}
            </li>
            <li>
                Dirección: {{ session('config')->direction }}
            </li>
            <li>
                Celular: {{ session('config')->phone }}
            </li>
        </ul>

        <hr class="border border-slate-400 my-3">

        {{-- Informacion de la factura --}}
        <div class="overflow-hidden">
            <ul class="leading-4 whitespace-nowrap">
                <li>
                    <span class="w-14 inline-block">Fecha</span>
                    :
                    <span class="font-medium" x-text="bill.format_created_at"></span>
                </li>
                <li class="">
                    <span class="w-14 inline-block">Cajero</span>
                    :
                    <span class="font-medium truncate" x-text="bill.user_name"></span>
                </li>
                <li>
                    <span class="w-14 inline-block">C.C / NIT</span>
                    :
                    <span class="font-medium" x-text="customer.identification"></span>
                </li>
                <li>
                    <span class="w-14 inline-block">Cliente</span>
                    :
                    <span class="font-medium" x-text="customer.names"></span>
                </li>
                <li>
                    <span class="w-14 inline-block">Domicilio: </span>
                    :
                    <span class="font-medium" x-text="delivery_address"></span>
                </li>
            </ul>
        </div>

        <hr class="border border-slate-400 my-3">

        {{-- Productos --}}
        <table class="w-full leading-3">
            <thead>
                <tr>
                    <th width="70%"  class="text-left font-medium">
                        Producto o servicio
                    </th>
                    <th width="10%">
                        Cant
                    </th>
                    <th width="20%" class="text-right font-medium">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                <template x-for="item in products">
                    <tr>
                        <td class="text-left">
                            <span x-text="strLimit(item.name, 60)"></span>
                        </td>
                        <td class="text-center">
                            <span x-text="item.amount"></span>
                        </td>
                        <td class="text-right">
                            <span x-text="formatToCop(item.total)"></span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <h1 class="border-b-2 border-dotted my-5 border-slate-400"></h1>

        {{-- Totales --}}
        <ul class="leading-4">
            <li class="flex justify-end">
                <span>Servicio voluntario:</span>
                <span class="font-medium w-20 inline-block text-right" x-text="formatToCop(bill.tip)"></span>
            </li>

            <li class="flex justify-end">
                <span>Total al pagar:</span>
                <span class="font-medium w-20 inline-block text-right" x-text="formatToCop(bill.subtotal)"></span>
            </li>

            <li class="flex justify-end">
                <span>Total + servicio voluntario:</span>
                <span class="font-medium w-20 inline-block text-right" x-text="formatToCop(bill.total)"></span>
            </li>
        </ul>

        <hr class="border border-slate-400 my-3">

        {{-- footer --}}
        <div class="text-xs mt-4">
            <p class="text-center leading-4">Elaborador por: HALLTEC</p>
            <p class="text-center leading-4">www.halltec.co NIT: 900825759-7</p>
        </div>

    </div>
</div>
@endpush
