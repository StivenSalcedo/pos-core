<div x-data="{ tab: 'main' }" class="space-y-6">

    {{-- 🔹 Tarjeta secundaria: Datos complementarios --}}
    <div>
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="font-medium whitespace-normal text-lg">Datos adicionales del equipo y diagnóstico</h3>
            <div class="text-right">
                <x-wireui.button primary wire:click="update" text="Actualizar" icon="check" spinner="update" />
            </div>
        </div>
        <x-wireui.errors class="mb-6" />
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
                    class="" style="position: absolute;top: 35px;right: 10px;"
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
    </div>
</div>
