<div>
    <x-wireui.modal wire:model.defer="open"   max-width="4xl">
        <x-wireui.card title="Historial de cambios">

            @if (!$customer || $customer->audits->isEmpty())
                <div class="text-center text-gray-500 py-6">
                    No hay registros de auditoría.
                </div>
            @else
                <div class="space-y-4 max-h-[60vh] overflow-y-auto">
                    @foreach ($customer->audits as $audit)
                        <div class="border-b pb-3">

                            <div class="text-sm text-gray-500 mb-1">
                                {{ \App\Services\Audit\AuditMessageService::eventLabel($audit->event) }}
                                • {{ $audit->user->name ?? 'Sistema' }}
                                • {{ $audit->created_at->format('d/m/Y H:i') }}
                            </div>

                            <ul class="ml-4 list-disc text-sm">
                                @foreach (\App\Services\Audit\AuditMessageService::message($audit) as $change)
                                    <li>
                                        <strong>{{ $change['label'] }}</strong>:
                                        {{ $change['old'] }} → {{ $change['new'] }}
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    @endforeach
                </div>
            @endif

            <x-slot name="footer">
                <div class="text-right space-x-2">
                    <x-wireui.button secondary text="Cerrar" x-on:click="$wire.open = false" />

                </div>
            </x-slot>

        </x-wireui.card>
    </x-wireui.modal>
</div>
