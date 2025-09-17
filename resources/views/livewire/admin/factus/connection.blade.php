<div class="container">
    <x-commons.header>
        <div class="flex items-center" wire:click='updateApiStatus'>
            <div @class([
                'w-8 sm:w-12 h-4 sm:h-5 rounded-full flex items-center px-1 cursor-pointer transition-colors duration-300',
                'bg-blue-600' => $isApiEnabled,
                'bg-slate-300' => !$isApiEnabled,
            ])>
                <span @class(['h-3 w-3 sm:h-4 sm:w-4 bg-white rounded-full duration-300 transform', 'translate-x-3.5 sm:translate-x-6' => $isApiEnabled])></span>
            </div>
            <span class="ml-0.5">{{ $isApiEnabled ? 'Activado' : 'Desactivado' }}</span>
        </div>
        <x-wireui.button icon="eart" wire:click="testConnection" text="Probar conexión" />
    </x-commons.header>

    <x-wireui.card title="Credenciales API">

        <div class="space-y-4">
            <x-wireui.input label="URL" wire:model.defer="api.url" />
            <x-wireui.input label="Client ID" wire:model.defer="api.client_id" />
            <x-wireui.input label="Client Secret" wire:model.defer="api.client_secret" />
            <x-wireui.input label="Email" wire:model.defer="api.email" />
            <x-wireui.input label="Password" wire:model.defer="api.password" />
        </div>

        <x-slot:footer>
            <div class="text-right space-x-3">
                <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando.." />
            </div>
        </x-slot:footer>
    </x-wireui.card>

    <x-loads.panel-fixed text="Cambiando estado..." class="no-print z-[999]" wire:loading />
</div>
