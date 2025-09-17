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
                7 => 'El mes pasado',
                8 => 'Rango de fechas']" />
    </x-commons.header>

    <div>
        @include('livewire.admin.sales.products')
    </div>

    <x-loads.panel-fixed text="Cargando..." class="z-40" wire:loading/>

    <x-commons.table-responsive>

        <x-slot:top title="Productos vendidos">
        </x-slot:top>

        <x-slot:header>
            <div class="flex-1 flex justify-between">
                <div class="flex space-x-4 items-end">
                    <x-wireui.search placeholder="Buscar..." />
                    @if ($filterDate == 8)
                        <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                        <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
                    @endif
                </div>

                <div class="flex items-end space-x-3">
                    <x-wireui.input label="Total" :value="formatToCop($total)" readonly class="text-right"/>
                    <x-wireui.button wire:click="getToday()" text="Actualizar datos" load textLoad="Actualizando" />
                </div>

            </div>
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        Referencia
                    </th>
                    <th left>
                        Nombres
                    </th>
                    <th>
                        Cantidad
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
                            {{ $item->product->reference }}
                        </td>
                        <td left>
                            {{ $item->product->name }}
                        </td>
                        <td>
                            {{ $item->quantity }} - {{ $item->units }}
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
    </x-commons.table-responsive>

    @if ($products->hasPages())
        <div class="p-3">
            {{ $products->links() }}
        </div>
    @endif

</div>
