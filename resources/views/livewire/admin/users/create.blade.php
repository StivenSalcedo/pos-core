<div>
    <x-wireui.modal wire:model.defer="openCreate" max-width="2xl">
        <x-wireui.card title="Crear usuario">

            <x-wireui.errors />

            <div class="grid grid-cols-2 gap-6">
                <x-wireui.input label="Nombres" wire:model.defer="name" placeholder="Nombre del usuario" />
                <x-wireui.input onlyNumbers label="Celular" wire:model.defer="phone" placeholder="Celular del empleado"  />
                <x-wireui.native-select label="Rol" wire:model.defer="role" optionKeyValue="true" placeholder="Selecciona el rol" :options="$roles"  class="w-full"/>
                <x-wireui.input label="Email" wire:model.defer="email" placeholder="Email del empleado"  />
                <x-wireui.input label="Contraseña" type="password" wire:model.defer="password" placeholder="Contraseña" />
                <x-wireui.input label="Confirma la contraseña" type="password" wire:model.defer="password_confirmation" placeholder="Confirma la contraseña" />
            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                    <x-wireui.button wire:click="store" text="Guardar" load textLoad="Guardando.." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
