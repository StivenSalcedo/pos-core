@push('html')
    <div x-data="alpineTicketService()" class="print">
        <div x-show="show" class="absolute -z-40 font-roboto top-0 left-0 bg-white py-10 !text-[12px] px-4 w-full">
            <div class="flex justify-center">
                <img class="h-28" src="{{ getUrlLogo() }}">
            </div>

            {{-- Informacion de la empresa --}}
            <ul class="flex flex-col items-center leading-4">
                <li class="font-semibold">
                    <span x-text="company.name"></span>
                </li>
                <li>
                    <span x-text="company.nit"></span>
                </li>
                <li>
                    <span x-text="company.direction"></span>
                </li>
                <li>
                    <span x-text="company.phone"></span>
                </li>
            </ul>

            <hr class="border border-slate-400 my-3">

            {{-- Informacion de la factura --}}

            <ul class="flex flex-col leading-4">
                <li>
                    <div>Orden de servicio: <span x-text="service.id"></span></div>
                </li>
                <li>
                    <div>Fecha: <span x-text="service.date_entry"></span></div>
                </li>
                <li>
                    <div>Vendedor: <span x-text="service.responsible.name"></span></div>
                </li>
            </ul>


            <hr class="border border-slate-400 my-3">

            {{-- Informacion cliente --}}

            <ul class="flex flex-col leading-4">
                <li>
                    <div>Nombre: <span x-text="customer.names"></span></div>
                </li>
                <li>
                    <div>Documento: <span x-text="customer.no_identification"></span></div>
                </li>
                <li>
                    <div>Teléfono: <span x-text="customer.phone"></span></div>
                </li>
            </ul>

            <hr class="border border-slate-400 my-3">

            {{-- Descripción equipos --}}

            <div class="uppercase font-bold mb-3">Descripción del equipo</div>

            <ul class="flex flex-col leading-4">
                <li>
                    <div>Equipo: <span
                            x-text="service.equipment_type.name + '-'+ service.brand.name + '-' + service.model"></span>
                    </div>
                </li>
                <li>
                    <div>Accesorios: <span x-text="service.accessories"></span></div>
                </li>
                <li>
                    <div>Descripción: <span x-text="service.problem_description"></span></div>
                </li>
                <li>
                    <div>Estado: <span x-text="service.state.name"></span></div>
                </li>
                <li>
                    <div>Fecha estimada de entrega: <span x-text="service.date_due"></span></div>
                </li>
                <li>
                    <div>Diagnostico: <span x-text="service.diagnosis"></span></div>
                </li>
            </ul>

            <hr class="border border-slate-400 my-3">

            {{-- Descripción productos --}}

            <div class="uppercase font-bold mb-3">Descripción de productos/servicios</div>

            <table class="w-full leading-3">
                <thead>
                    <tr>
                        <th width="10%">
                            Cant
                        </th>
                        <th width="70%" class="text-left font-medium px-2">
                            Producto o servicio
                        </th>
                        <th width="20%" class="text-right font-medium">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in service.products">
                        <tr>
                            <td class="text-center">
                                <span x-text="item.quantity"></span>
                            </td>
                            <td class="text-left px-2">
                                <span x-text="strLimit(item.product.name, 60)"></span>
                            </td>
                            <td class="text-right">
                                <span x-text="formatToCop(item.total)"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <h1 class="border-b-2 border-dotted my-3 border-slate-400"></h1>

            {{-- Totales --}}
            <ul class="flex flex-col items-end leading-4">
                <li class="">
                    <span>Valor:</span>
                    <span class="font-medium w-24" x-text="formatToCop(service.subtotal)"></span>
                </li>

                <li class="">
                    <span>Descuento:</span>
                    <span class="font-medium w-24" x-text="formatToCop(service.discount)"></span>
                </li>
                <li x-if="service.iva>0" class="">
                    <span>IVA:</span>
                    <span class="font-medium w-24" x-text="formatToCop(service.iva)"></span>
                </li>
                <li class="">
                    <span class="uppercase font-bold">Total a pagar:</span>
                    <span class="font-medium w-24" x-text="formatToCop(service.total)"></span>
                </li>

            </ul>

            <h1 class="border-b-2 border-dotted my-3 border-slate-400"></h1>



            {{-- resolucion rango de numeracion --}}
            <template x-if="isElectronic && Object.keys(range).length">
                <div class="mt-1">
                    <p class="leading-3 text-center">
                        Resolución DIAN <span x-text="range.resolution_number"></span>
                        autorizada el <span x-text="range.start_date"></span>
                        prefijo <span x-text="range.prefix"></span>
                        del <span x-text="range.from"></span>
                        al <span x-text="range.to"></span>
                        Vig <span x-text="range.months"></span> meses
                    </p>
                </div>
            </template>

            <template x-if="isElectronic">
                <div class="flex justify-center mt-1">
                    <img class="max-w-[140px]" :src="electronic_bill.qr_image">
                </div>
            </template>
            <template x-if="isElectronic">
                <div>
                    <p class="text-center font-semibold text-xs">CUFE</p>
                    <p class="break-all leading-3" x-text="electronic_bill.cufe"></p>
                </div>
            </template>
            <div class="font-bold text-center">*** Gracias por su compra ***</div>
        </div>
    </div>
@endpush
