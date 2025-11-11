<div>
    <div class="flex items-center justify-between border-b pb-4 mb-4">
        <h3 class="font-medium whitespace-normal text-lg">Historial de notificaciones</h3>
        {{-- <div class="text-right">
            <x-wireui.button primary wire:click="openPhotoUpload" text="Agregar fotografía" icon="add"
                spinner="update" />
        </div> --}}
    </div>
    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full border text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Fecha</th>
                    <th class="px-3 py-2 text-left">Tipo</th>
                    <th class="px-3 py-2 text-left">Destino</th>
                    <th class="px-3 py-2 text-left">Mensaje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($service->notifications as $note)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $note->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">{{ strtoupper($note->channel ?? '-') }}</td>
                        <td class="px-3 py-2">{{ $note->destination ?? '-' }}</td>
                        <td class="px-3 py-2">{{ Str::limit($note->message, 60) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-400">No hay notificaciones registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
