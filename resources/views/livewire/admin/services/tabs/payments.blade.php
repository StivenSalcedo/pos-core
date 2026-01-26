<div>
    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h3 class="font-medium whitespace-normal text-lg">Pagos y abonos</h3>
        <div class="text-right">
            <x-wireui.button primary
                x-on:click="$wire.emitTo('admin.services.add-payment', 'openAddPayment', {{ $service->id }})"
                text="Agregar pago" icon="add" spinner="update" />
        </div>
    </div>
    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Fecha</th>
                    <th class="px-3 py-2 text-left">Valor</th>
                    <th class="px-3 py-2 text-left">Método</th>
                    <th class="px-3 py-2 text-left">Usuario</th>
                    <th class="px-3 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2 text-left">{{ number_format($p['amount'], 0, ',', '.') }}</td>
                        <td class="px-3 py-2">{{ $p['paymentMethod']['name'] ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $p['user']['name'] ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-center">
                            @if (!$service->isValidated && auth()->user()->can('borrar pago en servicio'))
                                <x-buttons.delete wire:click="removePayment({{ $p['id'] }})" title="Eliminar" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-400">No hay resgistros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <livewire:admin.services.add-payment />
</div>
