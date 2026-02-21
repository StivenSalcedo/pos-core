<div class="container">
    <x-commons.header>
        <x-wireui.range-date wire:model="filterDate" :options="[
            0 => 'Todos',
            1 => 'Hoy',
            2 => 'Esta semana',
            3 => 'Ultimos 7 días',
            4 => 'La semana pasada',
            5 => 'Hace 15 días',
            6 => 'Este mes',
            7 => 'El mes padado',
            8 => 'Rango de fechas',
        ]" />

        <x-wireui.button wire:click="exportBills" icon="excel" success text="Exportar a excel" load
            textLoad="Exportando..." />

        <x-wireui.button icon="quote" href="{{ route('admin.bills.create') }}" text="Crear factura" />

    </x-commons.header>
    <x-commons.table-responsive>

        <x-slot:top title="Facturas">
            @can('ver totales de venta')
                <x-commons.tag tooltip="Total de facturas" label="Total" :value="formatToCop($total)" />
            @endcan
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select optionKeyValue label="Buscar por" wire:model="filter" :options="$filters"
                width="13" />
            @can('ver todas las sedes')
                <x-wireui.native-select optionKeyValue label="Sede" wire:model="terminal_id" :options="$terminals"
                    placeholder="Todas" width="8" />
            @endcan
            <x-wireui.native-select optionKeyValue label="Estado" wire:model="status" :options="[0 => 'Todas', 1 => 'Activas', 2 => 'Anuladas']"
                width="8" />
            @if ($filterDate == 8)
                <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
            @endif
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        N° factura
                    </th>
                    <th left>
                        Cajero
                    </th>
                    <th left>
                        Cliente
                    </th>
                    <th left>
                        Fecha
                    </th>
                    <th left>
                        Medio de pago
                    </th>
                    <th>
                        Financiación
                    </th>

                    @if (App\Services\FactusConfigurationService::isApiEnabled(true))
                        <th>
                            Factura
                        </th>
                    @endif

                    @if (App\Services\FactusConfigurationService::isApiEnabled(true))
                        <th>
                            Nota
                        </th>
                    @endif

                    <th>
                        Estado
                    </th>
                    <th>
                        Total
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bills as $item)
                    <tr wire:key="bill-{{ $item->id }}">
                        <td left class="font-bold">
                            {{ $item->number }}
                        </td>
                        <td left class="leading-none">
                            {{ $item->user->name }} <br>
                            <span class="text-xs font-bold leading-none">{{ $item->terminal->name }}</span>
                        </td>
                        <td left>
                            {{ $item->customer->names }}
                        </td>
                        <td left class="leading-none">
                            <span class="text-xs">{{ $item->created_at->format('d-m-Y') }}</span>
                            <br>
                            <span
                                class="text-xs font-bold leading-none">{{ $item->created_at->format('h:i:s A') }}</span>
                        </td>
                        <td left>
                            {{ $item->paymentMethod->name }}
                        </td>
                        <td>
                            @if ($item->finance)
                                <x-commons.status :status="$item->finance->status" inactive="Pendiente" active="Pagada" />
                            @else
                                <span>No aplica</span>
                            @endif
                        </td>

                        @if (App\Services\FactusConfigurationService::isApiEnabled(true))
                            <td class="text-center">
                                <div class="flex justify-center">
                                    @if ($item->isValidated)
                                        <x-icons.factus class="h-6 w-6 text-indigo-800" title="Validada" />
                                    @else
                                        <button wire:click='validateElectronicBill({{ $item->id }})'>
                                            <x-icons.factus class="h-6 w-6 text-red-500"
                                                title="Pendiente por validar" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @endif

                        @if (App\Services\FactusConfigurationService::isApiEnabled(true))
                            <td class="text-center">
                                <div class="flex justify-center">
                                    @if ($item->electronicCreditNote)
                                        @if ($item->electronicCreditNote->is_validated)
                                            <x-icons.factus class="h-6 w-6 text-indigo-800" title="Validada" />
                                        @else
                                            <button wire:click='cancelBill({{ $item->id }})'>
                                                <x-icons.factus class="h-6 w-6 text-red-500"
                                                    title="Pendiente por validar" />
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        @endif

                        <td>
                            <x-commons.status :status="$item->status" active='Activa' inactive="Anulada" />
                        </td>
                        <td>
                            @formatToCop($item->total)
                        </td>
                        <td x-data actions>
                            <x-buttons.download @click="$dispatch('print-ticket', {{ $item->id }})" href='#'
                                title="Descargar" />
                            <x-buttons.show href="{{ route('admin.bills.show', $item->id) }}" title="Visualizar" />
                            @if (
                                $item->status === '0' &&
                                    (!App\Services\FactusConfigurationService::isApiEnabled(true) ||
                                        ($item->electronicBill && $item->electronicBill->is_validated)))
                                <x-buttons.ban wire:click="$emit('cancelBill', {{ $item->id }})" title="Anular" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

        @if ($bills->hasPages())
            <div class="p-3">
                {{ $bills->links() }}
            </div>
        @endif
    </x-commons.table-responsive>

    @include('pdfs.ticket-bill')

    <x-loads.panel-fixed text="Cancelando factura" class="no-print z-[999]" wire:loading wire:target='cancelBill' />
    <x-loads.panel-fixed text="Validando factura..." class="no-print z-[999]" wire:loading
        wire:target='validateElectronicBill' />

</div>

@push('js')
    <script>
        document.addEventListener('livewire:load', function() {

            Livewire.on('cancelBill', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires anular esta factura?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.cancelBill(id);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
@endpush
