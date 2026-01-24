@if ($this->audits->isEmpty())
    <div class="alert alert-secondary">
        No hay registros de auditoría.
    </div>
@else
    <table class="table table-sm table-hover">
        <thead class="table-light">
            <tr>
                <th>Acción</th>
                <th>Entidad</th>
                <th>Detalle</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->audits as $audit)
                <tr>
                    <td>
                        <span
                            class="badge bg-{{ $audit->event === 'created' ? 'success' : ($audit->event === 'updated' ? 'warning' : 'danger') }}">
                            {{ \App\Services\Audit\AuditMessageService::eventLabel($audit->event) }}
                        </span>
                    </td>

                    <td>
                        {{ class_basename($audit->auditable_type) }}
                    </td>

                    <td>
                        @foreach (\App\Services\Audit\AuditMessageService::message($audit) as $change)
                            <li>
                                <strong>{{ $change['label']?$change['label'] . ':':'' }}</strong>
                                {{ $change['old']?$change['old'] . '→':'' }}  {{ $change['new'] }}
                            </li>
                        @endforeach
                    </td>

                    <td>
                        {{ $audit->user->name ?? 'Sistema' }}
                    </td>

                    <td>
                        {{ $audit->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
