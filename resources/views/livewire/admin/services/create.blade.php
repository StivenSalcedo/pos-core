<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="4xl">
        <x-wireui.card title="Nuevo servicio técnico">
            <x-wireui.errors class="mb-6" />

            <div class="grid sm:grid-cols-3 gap-6">
                <!-- Fecha ingreso -->
                <x-wireui.input class="w-full" label="Fecha de ingreso" type="date" wire:model.defer="date_entry" />

                <!-- Fecha vencimiento -->
                <x-wireui.input class="w-full" label="Fecha de vencimiento" type="date" wire:model.defer="date_due" />

                <!-- Número de documento -->
                {{-- <x-wireui.input label="N° Documento" wire:model.defer="document_number" placeholder="Opcional" /> --}}

                <!-- Responsable -->
                <x-wireui.native-select class="w-full" label="Responsable" placeholder="Seleccione un responsable" :options="$responsibles"
                    wire:model.defer="responsible_id" optionKeyValue="true" />

                <!-- Técnico asignado -->
                <x-wireui.native-select class="w-full" label="Técnico asignado" placeholder="Seleccione técnico" :options="$technicians"
                    wire:model.defer="tech_assigned_id" optionKeyValue="true" />

                <!-- Tipo de equipo -->
                <x-wireui.native-select class="w-full" label="Tipo de equipo" placeholder="Seleccione equipo" :options="$equipmenttypes"
                    wire:model.defer="equipment_type_id" optionKeyValue="true" />

                <!-- Cliente -->
                <div class="col-span-3">
                    <div class="flex justify-between">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        {{-- Botón para crear nuevo cliente --}}
                        <button title="Crear nuevo cliente"
                            x-on:click="$wire.emitTo('admin.customers.create', 'openCreate');">
                            <i class="ico icon-add-user text-blue-600 text-xl"></i>
                        </button>
                    </div>

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
    <livewire:admin.customers.create />
</div>
