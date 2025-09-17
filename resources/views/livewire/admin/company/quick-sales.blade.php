<div>
    <x-wireui.card title="Ventas rápidas">

        <x-wireui.errors />

        <div class="space-y-6">

            <div class="flex justify-between">
                <p class="text-sm text-slate-600">Activa o desactiva la impresión de factura</p>
                <x-buttons.switch wire:model.defer="company.print" active="Activado" inactive="Desactivado" width="w-24"/>
            </div>

            <div class="flex justify-between">
                <p class="text-sm text-slate-600">Activa o desactiva la ventana de recibir efectivo</p>
                <x-buttons.switch wire:model.defer="company.change" active="Activado" inactive="Desactivado" width="w-24"/>
            </div>

            <div class="flex justify-between">
                <p class="text-sm text-slate-600">Activa o desactiva el uso de mesas</p>
                <x-buttons.switch wire:model.defer="company.tables" active="Activado" inactive="Desactivado" width="w-24"/>
            </div>

        </div>
        <x-slot:footer>
            <div class="text-right">
                <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualizando..." />
            </div>
        </x-slot:footer>
    </x-wireui.card>
</div>
