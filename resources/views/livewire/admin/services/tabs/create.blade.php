<div x-data="{ tab: 'create' }" class="space-y-6">


    {{-- 🔹 Tarjeta principal: Datos iniciales --}}
    <div>
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="font-medium whitespace-normal text-lg">Datos del servicio</h3>
            <div class="text-right">
                <x-wireui.button primary wire:click="update" text="{{ $service->id ? 'Actualizar' : 'Crear' }}"
                    icon="check" spinner="update" />
            </div>
        </div>

        <x-wireui.errors class="mb-6" />
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Fecha de ingreso --}}
            <x-wireui.input class="w-full" label="Fecha de ingreso" wire:model.defer="service.date_entry" type="date"
                disabled />

            {{-- Fecha de vencimiento --}}
            <x-wireui.input class="w-full" label="Fecha de vencimiento" wire:model.defer="service.date_due"
                type="date" />

            {{-- Número de documento --}}
            {{-- <x-wireui.input label="N° Documento" wire:model.defer="service.document_number" placeholder="(opcional)" /> --}}

            {{-- Responsable --}}
            <x-wireui.native-select class="w-full" label="Responsable" placeholder="Seleccione un responsable"
                :options="$responsibles" wire:model.defer="service.responsible_id" optionKeyValue="true" />

            {{-- Técnico asignado --}}
            <x-wireui.native-select class="w-full" label="Técnico Asignado" placeholder="Seleccione un técnico"
                :options="$technicians" wire:model.defer="service.tech_assigned_id" optionKeyValue="true" />

            {{-- Tipo de equipo --}}
            <div class="relative">
                <x-wireui.native-select class="w-full" label="Tipo de Equipo" placeholder="Seleccione un tipo"
                    :options="$equipmentTypes" wire:model.defer="service.equipment_type_id" optionKeyValue="true" />
                {{-- Botón para crear nuevo cliente --}}
                <button class="absolute top-0 right-0" title="Crear nuevo tipo"
                    wire:click='$emitTo("admin.equipment-types.create", "openCreate", "{{ $this->getName() }}")'>
                    <i class="ico icon-add text-blue-600 text-sm"></i>
                </button>
            </div>
             {{-- Marca --}}
            <div class="relative">
                <x-wireui.native-select class="w-full" label="Marca" placeholder="Seleccione una marca" :options="$brands"
                wire:model.defer="service.brand_id" optionKeyValue="true" />
                {{-- Botón para crear nuevo cliente --}}
                <button class="absolute top-0 right-0" title="Crear nuevo tipo" wire:click='$emitTo("admin.brands.create", "openCreate", "{{ $this->getName() }}")'>
                    <i class="ico icon-add text-blue-600 text-sm"></i>
                </button>
            </div>
            <x-wireui.native-select class="w-full" label="Estado" :options="$states" wire:model.defer="service.state_id"
                optionKeyValue="true" />


        </div>

        {{-- 🔹 Cliente (buscador) --}}
        <div class="mt-6">
            <div class="flex justify-between">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                {{-- Botón para crear nuevo cliente --}}
                <button title="Crear nuevo cliente" x-on:click="setTimeout(() => {Livewire.emitTo('admin.customers.create', 'openCreate');}, 300);">
                    <i class="ico icon-add-user text-blue-600 text-xl"></i>
                </button>
            </div>
            <x-wireui.input class="w-full" wire:model.debounce.500ms="searchCustomer" placeholder="Buscar cliente por nombre o documento..."/>

            {{-- Resultados búsqueda --}}
            @if ($customers && count($customers) > 0)
                <div class="border mt-2 rounded-md shadow bg-white max-h-40 overflow-y-auto">
                    @foreach ($customers as $customer)
                        <div wire:click="selectCustomer({{ $customer->id }})"
                            class="px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm">
                            {{ $customer->names }}
                            <span class="text-gray-500 text-xs">({{ $customer->no_identification }})</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Cliente seleccionado --}}
            @if ($selectedCustomer)
                <div class="bg-gray-50 border rounded-lg p-2 text-sm text-gray-700 flex items-center justify-between my-4">
                    <div>
                        <strong>{{ $selectedCustomer['names'] ?? '' }}</strong>
                        <span class="text-gray-500 text-xs">
                            ({{ $selectedCustomer['no_identification'] ?? '' }})
                        </span>
                    </div>
                    <x-buttons.delete wire:click="clearCustomer" title="Eliminar"/>

                    {{-- <x-wireui.button flat sm icon="trash"  class="ml-2" /> --}}

                </div>
            @endif
            @error('service.customer_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

    </div>
    <livewire:admin.customers.create />
    <livewire:admin.equipment-types.create />
      <livewire:admin.brands.create />

</div>
@push('js')
    <script>
        document.addEventListener('redirect-after-success', (event) => {
            const {
                url
            } = event.detail;
            // Espera 1.5 segundos antes de redirigir (suficiente para mostrar alerta)
            setTimeout(() => {
                window.location.href = url;
            }, 1000);
        });
    </script>
@endpush
