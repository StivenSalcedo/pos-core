<div x-data="{ tab: 'main' }" class="space-y-6">

    {{-- 🔹 Tarjeta principal: Datos iniciales --}}
    <x-wireui.card title="Datos del servicio">

        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                <button class="absolute top-0 right-0" title="Crear nuevo tipo" wire:click='$emitTo("admin.equipment-types.create", "openCreate", "{{ $this->getName() }}")'>
                    <i class="ico icon-add text-blue-600 text-lg"></i>
                </button>
            </div>
            {{-- Marca --}}
            <div class="relative">
                <x-wireui.native-select class="w-full" label="Marca" placeholder="Seleccione una marca" :options="$brands"
                wire:model.defer="service.brand_id" optionKeyValue="true" />
                {{-- Botón para crear nuevo cliente --}}
                <button class="absolute top-0 right-0" title="Crear nuevo tipo" wire:click='$emitTo("admin.brands.create", "openCreate", "{{ $this->getName() }}")'>
                    <i class="ico icon-add text-blue-600 text-lg"></i>
                </button>
            </div>

            <x-wireui.native-select class="w-full" label="Estado" :options="$states" wire:model.defer="service.state_id"
                optionKeyValue="true" />            

        </div>
        {{-- <button wire:click='$emitTo("admin.brands.create", "openCreate", "{{ $this->getName() }}")'
            class="h-10 w-10 bg-indigo-500 text-white rounded-lg" title="Crear Marca">
            <i class="ico icon-add"></i>
        </button>
        <button wire:click='$emitTo("admin.equipment-types.create", "openCreate", "{{ $this->getName() }}")'
            class="h-10 w-10 bg-indigo-500 text-white rounded-lg" title="Crear Tipo">
            <i class="ico icon-add"></i>
        </button> --}}
        {{-- 🔹 Cliente (buscador) --}}
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
            <div class="flex items-center space-x-2">
                <x-wireui.input wire:model.debounce.500ms="searchCustomer"
                    placeholder="Buscar cliente por nombre o documento..." class="flex-1" />
                <button title="Registrar nuevo cliente"
                    x-on:click="
                        setTimeout(() => {
                            Livewire.emitTo('admin.customers.create', 'openCreate');
                        }, 300);
                    "
                    class="p-2 border rounded-md shadow hover:bg-gray-100 transition">
                    <i class="ico icon-add-user text-xl text-primary-600"></i>
                </button>
            </div>

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
                <div
                    class="mt-2 bg-gray-50 border rounded-lg p-2 text-sm text-gray-700 flex items-center justify-between">
                    <div>
                        <strong>{{ $selectedCustomer['names'] ?? '' }}</strong>
                        <span class="text-gray-500 text-xs">
                            ({{ $selectedCustomer['no_identification'] ?? '' }})
                        </span>
                    </div>

                    <x-wireui.button flat sm icon="trash" wire:click="clearCustomer" class="ml-2" />

                </div>
            @endif
        </div>

    </x-wireui.card>

    {{-- 🔹 Tarjeta secundaria: Datos complementarios --}}
    <x-wireui.card title="Datos adicionales del equipo y diagnóstico">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-wireui.input label="Modelo" wire:model.defer="service.model" />
            <x-wireui.input label="Usuario" wire:model.defer="service.user" />
            <div x-data="{ show: false }" class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Clave</label>

                <input :type="show ? 'text' : 'password'" wire:model.defer="service.password"
                    autocomplete="new-password"
                    class="block w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                    placeholder="Clave (opcional)" />

                <!-- Botón ojo -->
                <button type="button" x-on:click="show = !show"
                    class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-500 hover:text-gray-700"
                    :title="show ? 'Ocultar clave' : 'Mostrar clave'">
                    <!-- eye / eye-off SVG -->
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.974 9.974 0 012.223-3.328M6.1 6.1L17.9 17.9M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-wireui.input label="Accesorios" wire:model.defer="service.accessories" />
        </div>

        <div class="mt-6 grid sm:grid-cols-2 gap-6">
            <x-wireui.textarea label="Descripción del problema" wire:model.defer="service.problem_description"
                rows="4" />
            <x-wireui.textarea label="Diagnóstico" wire:model.defer="service.diagnosis" rows="4" />
        </div>

        <div class="mt-6 text-right">
            <x-wireui.button primary wire:click="update" text="Guardar cambios" icon="check" spinner="update" />
        </div>
    </x-wireui.card>
    <livewire:admin.customers.create />
    <livewire:admin.equipment-types.create />
    <livewire:admin.brands.create />
</div>
