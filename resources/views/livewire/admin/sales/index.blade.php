<div class="container">

    <x-commons.header>
        @can('ver todas las sedes')
            <x-wireui.native-select optionKeyValue wire:model="terminal_id" :options="$terminals" placeholder="Todas las sedes"
                width="8" />
        @endcan
        <x-wireui.range-date wire:model="filterDate" :options="[
            0 => 'Todos',
            1 => 'Hoy',
            2 => 'Esta semana',
            3 => 'Ultimos 7 días',
            4 => 'La semana pasada',
            5 => 'Hace 15 días',
            6 => 'Este mes',
            7 => 'El mes pasado',
            8 => 'Rango de fechas',
        ]" />
        <x-dropdown align="right" width="w-48">
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center border border-transparent leading-6 font-medium rounded-md text-white transition ease-in-out duration-150 text-xs sm:text-sm px-4 py-1 sm:py-1.5 bg-blue-500 hover:bg-blue-600 hover:ring-blue-500 disabled:opacity-60"
                    title="Perfil">
                    <div class="flex items-center">
                        <i class="ico icon-download mr-2"></i>
                        <div>Exportar</div>
                        <div class="button-dropdown"></div>
                    </div>
                </button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link class="text-right cursor-pointer" wire:click="exportExcel">
                    Productos vendidos
                </x-dropdown-link>
                <x-dropdown-link class="text-right cursor-pointer" wire:click="exportExcelPaymentMethodSummary">
                    Resumen pagos
                </x-dropdown-link>
                <x-dropdown-link class="text-right cursor-pointer" wire:click="exportExcelEmployeeSummarySummary">
                    Resumen por empleado
                </x-dropdown-link>
                <x-dropdown-link class="text-right cursor-pointer" wire:click="exportExcelCashRegisterDailySummary">
                    Arqueo de caja
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>

        {{-- <x-wireui.button wire:click="exportExcel" icon="download" text="Exportar Productos vendidos" />
        <x-wireui.button wire:click="exportExcelPaymentMethodSummary" icon="download" text="Exportar Resumen pagos" />
        <x-wireui.button wire:click="exportExcelEmployeeSummarySummary" icon="download" text="Exportar por empleado" />
        <x-wireui.button wire:click="exportExcelCashRegisterDailySummary" icon="download"
            text="Exportar Arqueo de caja" /> --}}

    </x-commons.header>

    <div>
        @include('livewire.admin.sales.products')
    </div>

    <x-loads.panel-fixed text="Cargando..." class="z-40" wire:loading />

    <x-commons.table-responsive>

        <x-slot:top title="Productos vendidos">
            <div><strong>Total:</strong> {{ formatToCop($total) }}</div>
        </x-slot:top>

        <x-slot:header>

            {{-- <div class="grid grid-cols-3 gap-4">
                <div class="row-span-4">
                    <h2 class="font-medium whitespace-normal text-lg">Productos vendidos</h2>
            </div> --}}
            <x-wireui.search placeholder="Buscar..." />
            <div class="flex-1 flex justify-between">

                <div class="flex space-x-4 items-end">

                    @if ($filterDate == 8)
                        <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                        <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
                    @endif
                </div>


                <div class="flex items-end space-x-3 ml-4">

                    {{-- <x-wireui.input label="Total" :value="formatToCop($total)" readonly class="text-right" /> --}}
                    <x-wireui.button class="w-36" wire:click="refreshData()" text="Actualizar datos" load textLoad="Actualizando" />


                </div>



            </div>
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Forma de Pago
                    </th>
                    <th right>
                        Valor
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportPayments as $item)
                    <tr wire:key="payment-{{ $item->payment_method_id }}">
                        <td left>
                            {{ $item->name }}
                        </td>
                        <td right>
                            $ {{ $item->total }}
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th left >
                        Sede
                    </th>
                    <th left>
                        Referencia
                    </th>
                    <th left>
                        Nombres
                    </th>
                    <th class="cursor-pointer select-none" wire:click="toggleOrderUnits">
                        Cantidad
                        @if ($orderUnits === 'asc')
                            ↑
                        @elseif ($orderUnits === 'desc')
                            ↓
                        @endif
                    </th>
                    <th right>
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $key => $item)
                    <tr wire:key="sale-{{ $key }}">
                        <td left>
                            {{ $item->terminal_name }}
                        </td>
                        <td left>
                            {{ $item->reference }}
                        </td>
                        <td left>
                            {{ $item->name }}
                        </td>
                        <td>
                            {{ $item->quantity }}
                        </td>
                        <td right>
                            @formatToCop($item->total)
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

        @if ($products->hasPages())
            <div class="p-3">
                {{ $products->links() }}
            </div>
        @endif
    </x-commons.table-responsive>



</div>
