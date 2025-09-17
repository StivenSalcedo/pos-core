<div>
    <x-wireui.modal wire:model.defer="openEdit" max-width="2xl">
        <x-wireui.card title="Actualizar usuario">

            <x-wireui.errors />

            <div class="grid grid-cols-2 gap-6">
                <x-wireui.input label="Nombres" wire:model.defer="user.name" placeholder="Nombre del usuario" />
                <x-wireui.input onlyNumbers label="Celular" wire:model.defer="user.phone" placeholder="Celular del empleado"  />
                @if ($user->id !== 1)
                    <x-wireui.native-select label="Rol" wire:model.defer="role" optionKeyValue="true" placeholder="Selecciona el rol" :options="$roles"  class="w-full"/>
                @endif
                <x-wireui.input label="Email" wire:model.defer="user.email" placeholder="Email del empleado"  />
                <x-wireui.input label="Contraseña" type="password" wire:model.defer="password" placeholder="Contraseña" />
                <x-wireui.input label="Confirma la contraseña" type="password" wire:model.defer="password_confirmation" placeholder="Confirma la contraseña" />
                @if ($user->id !== 1)
                    <x-buttons.switch wire:model.defer="user.status" active="activo" inactive="Inactivo" />
                @endif
            </div>

            <x-slot:footer>
                <div class="text-right space-x-3">
                    <x-wireui.button secondary x-on:click="show=false" text="Cerrar" />
                    <x-wireui.button wire:click="update" text="Actualizar" load textLoad="Actualiando.." />
                </div>
            </x-slot:footer>
        </x-wireui.card>
    </x-wireui.modal>
</div>
