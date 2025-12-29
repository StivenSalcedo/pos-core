@push('html')
    <div x-data="alpineLabelService()" class="print">
        <div x-show="show" class="absolute -z-40 font-roboto top-0 left-0 bg-white py-10 !text-[12px] px-4 w-full">

            <div class="grid gap-4 grid-cols-2 content-center">
                <div>
                    <img class="h-14" src="{{ getUrlLogo() }}">
                </div>
                <ul class="leading-4 text-right">
                    <li>
                        <div>Orden de servicio: <span x-text="service.id"></span></div>
                    </li>
                    <li>
                        <div>Fecha: <span x-text="formatDate(service.date_entry, 'DD/MM/YYYY')"></span></div>
                    </li>
                    <li>
                        <div>Vendedor: <span x-text="[service?.responsible?.name].filter(Boolean).join('-')"></span></div>
                    </li>
                </ul>

            </div>

            {{-- Informacion de la factura --}}

            <hr class="border border-slate-400 my-1">

            {{-- Informacion cliente --}}

            <ul class="flex flex-col leading-4">
                <li>
                    <div>Cliente: <span x-text="customer.names"></span></div>
                </li>
                <li>
                    <div>Documento: <span x-text="customer.no_identification"></span></div>
                </li>
                <li>
                    <div>Teléfono: <span x-text="customer.phone"></span></div>
                </li>
            </ul>

            <hr class="border border-slate-400 my-1">

            {{-- Descripción equipos --}}

            <div class="uppercase font-bold">Descripción del equipo</div>

            <ul class="flex flex-col leading-4">
                <li>
                    <div>Equipo: <span
                            x-text="[service?.equipment_type?.name + '-'+ service?.brand?.name].filter(Boolean).join('-')"></span>
                    </div>
                </li>
                <li>
                    <div>Estado: <span x-text="[service?.state?.name].filter(Boolean).join('-')"></span></div>
                </li>
                <li>
                    <div>Fecha estimada de entrega: <span x-text="service.date_due"></span></div>
                </li>
            </ul>
        </div>
    </div>
@endpush
