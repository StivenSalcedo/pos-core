<div class="container">

    <x-commons.header >
        <div class="flex space-x-4">
            <x-wireui.range-date wire:model="filterDate" :options="[
                0 => 'Todos',
                1 => 'Hoy',
                2 => 'Esta semana',
                3 => 'Últimos 7 días',
                4 => 'La semana pasada',
                5 => 'Hace 15 días',
                6 => 'Este mes',
                7 => 'El mes pasado',
                8 => 'Rango de fechas']" />
        </div>

        <x-wireui.button wire:click="exportFinances" icon="excel" success text="Exportar a excel" load textLoad="Exportando..." />

    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Financiaciones">
            <x-commons.tag tooltip="Total de cartera" label="Cartera" :value="formatToCop($wallet)" />
            <x-commons.tag tooltip="Total financiamientos" label="Total" :value="formatToCop($total)" />
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select label="Buscar por" optionKeyValue wire:model="filter" :options="$filters" width="13" />
            <x-wireui.native-select label="Estado" optionKeyValue wire:model="filterStatus" :options="[0 => 'Todos', 1 => 'Pagados', 2 => 'Pendientes', 3 => 'Vencidos']" width="13" />
            @if ($filterDate == 8)
                <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
            @endif
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th>
                        Fecha / N° factura
                    </th>
                    <th>
                        Fecha de vencimiento
                    </th>
                    <th left>
                        Nombre
                    </th>
                    <th>
                        Pagos
                    </th>
                    <th>
                        Total
                    </th>
                    <th>
                        Pendiente
                    </th>
                    <th>
                        Pagado
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($finances as $item)
                    <tr wire:key="finance-{{ $item->id }}"
                        class="{{ getDays($item->created_at, $item->due_date, false) == 0 && $item->status ? 'text-red-600' : '' }}">

                        <td class="leading-none">
                            <span class="text-xs font-bold leading-none">{{ $item->created_at->format('d-m-Y') }}</span> <br>
                            {{ $item->bill->number}}
                        </td>
                        <td class="leading-none">
                            <span class="text-xs font-bold leading-none">{{ $item->expiresIn }}</span> <br>
                            {{ getDays($item->created_at, $item->due_date) }}
                        </td>
                        <td left class="leading-none">
                            <span class="text-xs font-bold leading-none">{{ $item->customer->no_identification }}</span> <br>
                            {{ $item->customer->names }}
                        </td>
                        <td>
                            {{ $item->details_count }}
                        </td>
                        <td>
                            @formatToCop($item->bill->total)
                        </td>
                        <td>
                            @formatToCop($item->pending)
                        </td>
                        <td>
                            @formatToCop($item->paid)
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" active="Pagado" inactive="Pendiente" />
                        </td>
                        <td actions>
                            <x-buttons.icon icon="money text-2xl" wire:click="$emitTo('admin.finances.show', 'openShow', {{ $item->id }})" title="Agregar abono" />
                            <x-buttons.delete wire:click="$emit('deleteFinance', {{ $item->id }})" title="Eliminar" />
                        </td>

                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>
    </x-commons.table-responsive>

    @if ($finances->hasPages())
        <div class="p-3">
            {{ $finances->links() }}
        </div>
    @endif

    <livewire:admin.finances.show>

</div>

@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('deleteFinance', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires eliminar esta financiación?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.deleteFinance(id);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
@endpush


