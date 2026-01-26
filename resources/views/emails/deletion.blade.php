<h2>⚠️ Eliminación registrada</h2>

<p><strong>Servicio:</strong> #{{ $audit->parent_id }}</p>

<p><strong>Usuario:</strong>
    {{ $audit->user->name ?? 'Sistema' }}
</p>

<p><strong>Fecha:</strong>
    {{ $audit->created_at->format('d/m/Y H:i') }}
</p>

<hr>

@foreach($messages as $msg)
    <p>{{ $msg['new'] }}</p>
@endforeach
