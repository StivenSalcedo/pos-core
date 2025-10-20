<div>
    <div class="flex justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-700">Pagos y abonos</h3>
        <x-wireui.button sm icon="plus" primary text="Registrar Pago"
            x-on:click="$wire.emitTo('admin.services.add-payment', 'openAddPayment', {{ $service->id }})" />
    </div>

    <table class="min-w-full border text-sm text-gray-700">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2">Fecha</th>
                <th class="px-3 py-2 text-right">Valor</th>
                <th class="px-3 py-2">Método</th>
                <th class="px-3 py-2">Usuario</th>
                <th class="px-3 py-2 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr class="border-b">
                    <td class="px-3 py-2">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-3 py-2">{{ ucfirst($payment->method ?? 'N/A') }}</td>
                    <td class="px-3 py-2">{{ $payment->user->name ?? 'N/A' }}</td>
                    <td class="px-3 py-2 text-center">
                        <x-wireui.button sm icon="trash" red wire:click="removePayment({{ $payment->id }})" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-400">No hay pagos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <livewire:admin.services.add-payment />
</div>
