<div class="container">

    <x-commons.header>
        <x-wireui.range-date wire:model="filterDate" :options="[
            0 => 'Todos',
            2 => 'Esta semana',
            3 => 'Ultimos 7 días',
            4 => 'La semana pasada',
            5 => 'Hace 15 días',
            6 => 'Este mes',
            7 => 'El mes padado',
            8 => 'Rango de fechas',
        ]" />
        <x-wireui.button :href="$this->downloadPDF()" target="_blank" icon="pdf" text="Exportar a PDF" />
        <x-wireui.button wire:click="exportSales" icon="excel" success text="Exportar a excel" load textLoad="Exportando..." />
    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Reporte de ventas diarias">
            <x-commons.tag tooltip="Subtotal" label="Subtotal" :value="formatToCop($subtotal)" />
            <x-commons.tag tooltip="Descuento" label="Descuento" :value="formatToCop($discount)" />
            <x-commons.tag tooltip="IVA" label="IVA" :value="formatToCop($iva)" />
            <x-commons.tag tooltip="INC" label="INC" :value="formatToCop($inc)" />
            <x-commons.tag tooltip="Total" label="Total" :value="formatToCop($total)" />
        </x-slot:top>

        @if ($filterDate == 8)
            <x-slot:header>
                <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
            </x-slot:header>
        @endif


        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Fecha
                    </th>
                    <th right>
                        Desde
                    </th>
                    <th right>
                        Hasta
                    </th>
                    <th right>
                        Subtotal
                    </th>
                    <th right>
                        Descuento
                    </th>
                    <th right>
                        IVA
                    </th>
                    <th right>
                        INC
                    </th>
                    <th right>
                        Total
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $item)
                    <tr wire:key="daily-sales-{{ $item->id }}">
                        <td left>
                            {{ $item->format_creation_date }}
                        </td>
                        <td right>
                            {{ $item->from }}
                        </td>
                        <td right>
                            {{ $item->to }}
                        </td>
                        <td right>
                            @formatToCop($item->subtotal_amount)
                        </td>
                        <td right>
                            @formatToCop($item->discount_amount)
                        </td>
                        <td right>
                            @formatToCop($item->iva_amount)
                        </td>
                        <td right>
                            @formatToCop($item->inc_amount)
                        </td>
                        <td right>
                            @formatToCop($item->total_amount)
                        </td>
                        <td actions>
                            <a href="{{ route('admin.daily-sales.pdf', $item->id) }}" target="_blank">
                                <i class="ico icon-pdf text-xl text-blue-700"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    @if ($sales->hasPages())
        <div class="p-3">
            {{ $sales->links() }}
        </div>
    @endif
</div>
