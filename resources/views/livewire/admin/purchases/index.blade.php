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
                8 => 'Rango de fechas']" />

        <x-wireui.button icon="excel" wire:click="exportPurchase" text="Exportar a excel" load success textLoad="Exportando..." />
        <x-wireui.button icon="purchases" href="{{ route('admin.purchases.create') }}"   text="Agregar compra" />

    </x-commons.header>

    <x-commons.table-responsive>

        <x-slot:top title="Compras">
            <x-commons.tag tooltip="Total de compras" label="Total" :value="formatToCop($total)" />
        </x-slot:top>

        <x-slot:header>
            <x-wireui.search placeholder="Buscar..." />
            <x-wireui.native-select wire:model.defer="filter" optionKeyValue="true" :options="$filters" />
            <x-wireui.native-select label="Estado" optionKeyValue wire:model="status" :options="[0 => 'Todas', 1=>'Activas', 2=>'Anuladas']" width="8"/>
            @if ($filterDate == 8)
                <x-wireui.input label="Desde" wire:model="startDate" type="date" onkeydown="return false" />
                <x-wireui.input label="Hasta" wire:model="endDate" type="date" onkeydown="return false" />
            @endif
        </x-slot:header>

        <table class="table">
            <thead>
                <tr>
                    <th left>
                        N° Compra
                    </th>
                    <th left>
                        Proveedor
                    </th>
                    <th left>
                        Fecha
                    </th>
                    <th left>
                        Total
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
                @forelse ($purchases as $item)
                    <tr wire:key="purchase-{{ $item->id }}">
                        <td left>
                            {{$item->id}}
                        </td>
                        <td left>
                            {{ $item->provider->name }}
                        </td>
                        <td left>
                            {{ $item->created_at->format('d-m-Y') }}
                        </td>
                        <td left>
                            @formatToCop($item->total)
                        </td>
                        <td>
                            <x-commons.status :status="$item->status" inactive="anulada"/>
                        </td>
                        <td actions>
                            <x-buttons.download href="{{ route('admin.purchases.pdf', $item->id) }}" target="_blank" title="Descargar" />
                            <x-buttons.show href="{{route('admin.purchases.show', $item->id )}}" title="Visualizar" />
                            @if ($item->status === '0')
                                <x-buttons.ban wire:click="$emit('cancelPurchase', {{ $item->id }})" title="Anular" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <x-commons.table-empty />
                @endforelse
            <tbody>
        </table>

        @if ($purchases->hasPages())
            <div class="p-3">
                {{ $purchases->links() }}
            </div>
        @endif
    </x-commons.table-responsive>


</div>

@push('js')

    <script>
        document.addEventListener('livewire:load', function () {

            Livewire.on('cancelPurchase', id => {
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: '¿Quires anular esta compra?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, aceptar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return @this.cancelPurchase(id);
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
@endpush


