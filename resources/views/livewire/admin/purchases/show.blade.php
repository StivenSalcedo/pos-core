<div class="text-gray-700 max-w-7xl mx-auto px-4 pb-14 pt-8">

    <div class="space-y-5">

        <x-wireui.card title="Información de la compra">
            <div class="grid grid-cols-3 gap-3">
                <x-wireui.input label="Fecha" value="{{ $purchase->created_at->format('d-m-Y H:m:s') }}" readonly />
                <x-wireui.input label="N° de compra" value="{{ $purchase->id }}" readonly />
            </div>
            <x-slot:footer>
                <div class="text-right">
                    <x-wireui.button href="{{ route('admin.purchases.download', $purchase) }}" text="Descargar" />
                    @if ($purchase->status === '0')
                        <x-wireui.button danger onclick="cancelPurchase()" load text="Anular" textLoad="Anulando..." />
                    @endif
                </div>
            </x-slot:footer>
        </x-wireui.card>

        <x-wireui.card title="Información del proveedor">
            <div class="grid grid-cols-3 gap-3">
                <x-wireui.input label="N° Identificación" value="{{ $purchase->provider->no_identification }}" readonly />
                <x-wireui.input label="Nombre" value="{{ $purchase->provider->name }}" readonly />
                <x-wireui.input label="Teléfono" value="{{ $purchase->provider->phone }}" readonly />
            </div>
        </x-wireui.card>

        <x-commons.table-responsive>
            <table class="table">
                <thead>
                    <tr>
                        <th left>
                            Referencia
                        </th>
                        <th left>
                            Nombre
                        </th>
                        <th>
                            Cantidad
                        </th>
                        <th>
                            Unidades
                        </th>
                        <th left>
                            Costo
                        </th>
                        <th left>
                            Costo unidad
                        </th>
                        <th left>
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchase->details as  $item)
                        <tr wire:key="product-{{ $item['product_id'] }}">
                            <td left>
                                {{ $item->product->reference }}
                            </td>
                            <td left>
                                {{ $item->product->name }}
                            </td>
                            <td>
                                {{ $item->amount }}
                            </td>
                            <td>
                                {{ $item->units }}
                            </td>
                            <td left>
                                @formatToCop($item->cost)
                            </td>
                            <td left>
                                @formatToCop($item->cost_unit)
                            </td>
                            <td left>
                                @formatToCop($item->total)
                            </td>
                        </tr>
                    @empty
                        <x-commons.table-empty />
                    @endforelse
                <tbody>
            </table>
        </x-commons.table-responsive>

        <div class="text-right mt-4 pr-4">
            <h1 class="font-bold text-xl">TOTAL @formatToCop($purchase->total)</h1>
        </div>
    </div>
</div>

<script>
    function cancelPurchase(){
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
                return @this.cancelPurchase(@json($purchase->id));
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    };
</script>
