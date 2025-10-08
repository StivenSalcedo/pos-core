<div>
  <h3 class="text-lg font-semibold text-gray-700 mb-3">Historial de notificaciones</h3>

  <table class="min-w-full border text-sm text-gray-700">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-3 py-2">Fecha</th>
        <th class="px-3 py-2">Tipo</th>
        <th class="px-3 py-2">Destino</th>
        <th class="px-3 py-2">Mensaje</th>
      </tr>
    </thead>
    <tbody>
      @forelse($service->notifications as $note)
        <tr class="border-b">
          <td class="px-3 py-2">{{ $note->created_at->format('d/m/Y H:i') }}</td>
          <td class="px-3 py-2">{{ strtoupper($note->type ?? '-') }}</td>
          <td class="px-3 py-2">{{ $note->recipient ?? '-' }}</td>
          <td class="px-3 py-2">{{ Str::limit($note->message, 60) }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center py-4 text-gray-400">No hay notificaciones registradas</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
