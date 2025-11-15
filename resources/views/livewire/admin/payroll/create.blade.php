<div>
    <x-wireui.modal wire:model.defer="openCreate" >
        <x-wireui.card title="Crear pago">

            <x-wireui.errors />

            <div class="text-right">
                <button wire:click="$emitTo('admin.staff.modal', 'openModal')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded font-semibold text-sm inline-flex items-center">
                    <i class="ico icon-user mr-1"></i>
                    Buscar Empleado
                </button>
            </div>
            <div class="grid grid-cols-2 mt-2 gap-3">
                <x-wireui.input label="No Identificación" value="{{ $staff->no_identification }}" placeholder="Identificación" readonly  />
                <x-wireui.input label="Nombre" value="{{ $staff->names }}" placeholder="Nombre" readonly />
            </div>

            <hr class="my-4">

            <div class="space-y-4">
                <x-wireui.input onlyNumbers label="Cantidad formato numérico" wire:model.defer="price" placeholder="Ejemplo: 40000"  />
                <x-wireui.textarea label="Descripción" wire:model.defer="description" placeholder="" />
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
