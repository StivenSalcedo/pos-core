<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="4xl">
        <x-wireui.card title="Nuevo Servicio Técnico">
            <x-wireui.errors />

            <div class="grid sm:grid-cols-2 gap-6">
                <!-- Fecha ingreso -->
                <x-wireui.input label="Fecha de ingreso" type="date" wire:model.defer="date_entry" class="w-full" />

                <!-- Fecha vencimiento -->
                <x-wireui.input label="Fecha de vencimiento" type="date" wire:model.defer="date_due" class="w-full" />

                <!-- Número de documento -->
                <x-wireui.input label="N° Documento" wire:model.defer="document_number" placeholder="Opcional" />

                <!-- Responsable -->
                <x-wireui.native-select label="Responsable" placeholder="Seleccione un responsable" :options="$responsibles"
                    wire:model.defer="responsible_id" optionKeyValue="true" />

                <!-- Técnico asignado -->
                <x-wireui.native-select label="Técnico Asignado" placeholder="Seleccione técnico" :options="$technicians"
                    wire:model.defer="tech_assigned_id" optionKeyValue="true" />

                <!-- Cliente -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <div class="relative">
                        <x-wireui.input placeholder="Buscar cliente por nombre o identificación..."
                            wire:model.debounce.400ms="searchCustomer" icon="search" class="w-full" />

                        <!-- Dropdown resultados -->
                        @if (!empty($customerResults))
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-200 rounded-md mt-1 max-h-48 overflow-auto shadow-lg">
                                @forelse($customerResults as $c)
                                    <li wire:click="selectCustomer({{ $c->id }})"
                                        class="px-3 py-2 cursor-pointer hover:bg-blue-50">
                                        {{ $c->names }}
                                        <span
                                            class="text-xs text-gray-500">({{ $c->no_identification ?? 'sin ID' }})</span>
                                    </li>
                                @empty
                                    <li class="px-3 py-2 text-gray-500 text-sm">Sin resultados</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>

                    @if ($selectedCustomer)
                        <div class="mt-2 text-sm text-gray-700">
                            <strong>Cliente Seleccionado:</strong> {{ $selectedCustomer->names }}
                            <x-wireui.button flat sm icon="trash" wire:click="clearCustomer" class="ml-2" />
                        </div>
                    @endif
                   

                    @error('customer_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="$wire.openCreate = false" text="Cerrar" />
                    <x-wireui.button wire:click="store" text="Guardar y continuar" primary />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
